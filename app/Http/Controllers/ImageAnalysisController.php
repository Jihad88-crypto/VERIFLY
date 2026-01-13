<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Aws\Rekognition\RekognitionClient;
use Aws\Exception\AwsException;

class ImageAnalysisController extends Controller
{
    // Simple test endpoint
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Controller is working!',
            'timestamp' => now()->toDateTimeString()
        ]);
    }
    
    
    public function detectAI(Request $request)
    {
        try {
            Log::info('=== AI Detection Request Started (Hybrid with Clarifai) ===');
            
            // Validate - accept any file
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No image file provided'
                ], 400);
            }
            
            $image = $request->file('image');
            Log::info('File received:', [
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime' => $image->getMimeType()
            ]);
            
            // Analyze file signatures for AI generators
            $filePath = $image->getRealPath();
            $isAI = false;
            $confidence = 50;
            $detectedGenerator = null;
            $usedGoogleVision = false;
            
            // Read EXIF data
            $exifData = @exif_read_data($filePath);
            
            // AI Generator signatures to detect
            $aiSignatures = [
                'midjourney', 'dall-e', 'dalle', 'stable diffusion', 'stablediffusion',
                'adobe firefly', 'firefly', 'leonardo.ai', 'bluewillow',
                'craiyon', 'nightcafe', 'artbreeder', 'deepai', 'wombo',
                'ai generated', 'ai-generated', 'artificial intelligence'
            ];
            
            // Check EXIF data for AI signatures
            if ($exifData) {
                $searchFields = [
                    'Software', 'Make', 'Model', 'Artist', 'Copyright',
                    'ImageDescription', 'UserComment', 'XPComment'
                ];
                
                foreach ($searchFields as $field) {
                    if (isset($exifData[$field])) {
                        $value = strtolower($exifData[$field]);
                        
                        foreach ($aiSignatures as $signature) {
                            if (stripos($value, $signature) !== false) {
                                $isAI = true;
                                $confidence = 95; // Very high confidence if signature found
                                $detectedGenerator = $signature;
                                Log::info('AI Signature detected:', [
                                    'field' => $field,
                                    'signature' => $signature,
                                    'value' => $exifData[$field]
                                ]);
                                break 2;
                            }
                        }
                    }
                }
            }
            
            // If no AI signature found, check file characteristics
            if (!$isAI) {
                // Check if file has NO camera info (suspicious for AI)
                $hasCamera = isset($exifData['Make']) || isset($exifData['Model']);
                $hasDateTime = isset($exifData['DateTime']) || isset($exifData['DateTimeOriginal']);
                
                if (!$hasCamera && !$hasDateTime) {
                    // No camera info = EXTREMELY suspicious for AI
                    // ULTRA AGGRESSIVE: Lowered from 25 to 15
                    $confidence = 15; // Very strong suspicion - almost certainly AI or heavily edited
                    $isAI = false; // Not confirmed AI, but extremely suspicious
                } else {
                    // Has camera info = possibly real, but AI often fakes EXIF
                    // ULTRA AGGRESSIVE: Lowered from 75 to 60
                    $confidence = 60; // Low-moderate confidence - heavily rely on pixel analysis
                    $isAI = false;
                }
            }
            
            Log::info('File Signature Analysis Result:', [
                'isAI' => $isAI,
                'confidence' => $confidence,
                'generator' => $detectedGenerator,
                'hasExif' => !empty($exifData)
            ]);
            
            // PIXEL-LEVEL ANALYSIS: Analyze image characteristics
            $pixelAnalysisScore = 50; // Default neutral
            $pixelAnalysisDetails = [];
            
            try {
                $imageResource = imagecreatefromstring(file_get_contents($filePath));
                if ($imageResource) {
                    $width = imagesx($imageResource);
                    $height = imagesy($imageResource);
                    $totalPixels = $width * $height;
                    
                    // Sample pixels for analysis (increased sample size for better accuracy)
                    $sampleSize = min(15000, floor($totalPixels / 50)); // Increased from 10000
                    $colorVariance = 0;
                    $edgeSharpness = 0;
                    $smoothnessScore = 0;
                    $colors = [];
                    $textureVariance = 0; // NEW: Track texture patterns
                    
                    for ($i = 0; $i < $sampleSize; $i++) {
                        $x = rand(1, $width - 2);
                        $y = rand(1, $height - 2);
                        
                        $rgb = imagecolorat($imageResource, $x, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;
                        
                        // Calculate color variance
                        $colorKey = sprintf('%d,%d,%d', floor($r/10)*10, floor($g/10)*10, floor($b/10)*10);
                        $colors[$colorKey] = ($colors[$colorKey] ?? 0) + 1;
                        
                        // Calculate edge sharpness (compare with neighbors)
                        $neighbors = [
                            imagecolorat($imageResource, $x-1, $y),
                            imagecolorat($imageResource, $x+1, $y),
                            imagecolorat($imageResource, $x, $y-1),
                            imagecolorat($imageResource, $x, $y+1)
                        ];
                        
                        $diff = 0;
                        foreach ($neighbors as $neighbor) {
                            $nr = ($neighbor >> 16) & 0xFF;
                            $ng = ($neighbor >> 8) & 0xFF;
                            $nb = $neighbor & 0xFF;
                            $diff += abs($r - $nr) + abs($g - $ng) + abs($b - $nb);
                        }
                        $edgeSharpness += $diff / 4;
                        
                        // NEW: Calculate texture variance (AI images have unnatural texture)
                        $textureVariance += abs($diff - ($edgeSharpness / ($i + 1)));
                    }
                    
                    // Calculate metrics
                    $avgEdgeSharpness = $edgeSharpness / $sampleSize;
                    $colorUniqueness = count($colors) / $sampleSize;
                    $avgTextureVariance = $textureVariance / $sampleSize; // NEW metric
                    
                    // AI images tend to have:
                    // 1. Lower edge sharpness (too smooth) - AGGRESSIVE: < 10 (was < 12)
                    // 2. Higher color uniformity - AGGRESSIVE: < 0.2 (was < 0.25)
                    // 3. Low texture variance (unnatural smoothness) - NEW
                    // MUCH STRICTER thresholds to catch sophisticated AI
                    
                    $aiIndicators = 0;
                    $realIndicators = 0;
                    
                    // Check edge sharpness (ULTRA AGGRESSIVE: extremely strict)
                    if ($avgEdgeSharpness < 8) {
                        $aiIndicators += 4; // Extremely strong AI indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'extremely_smooth_ai';
                    } else if ($avgEdgeSharpness < 12) {
                        $aiIndicators += 3; // Very strong AI indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'very_smooth_ai';
                    } else if ($avgEdgeSharpness < 18) {
                        $aiIndicators += 2; // Strong AI indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'too_smooth';
                    } else if ($avgEdgeSharpness < 23) {
                        $aiIndicators++; // Moderate AI indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'somewhat_smooth';
                    } else if ($avgEdgeSharpness > 40) {
                        $realIndicators += 4; // Extremely strong real indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'extremely_natural';
                    } else if ($avgEdgeSharpness > 30) {
                        $realIndicators += 3; // Very strong real indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'very_natural';
                    } else if ($avgEdgeSharpness > 25) {
                        $realIndicators += 2; // Strong real indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'natural';
                    } else {
                        $pixelAnalysisDetails['edge_sharpness'] = 'neutral';
                    }
                    
                    // Check color uniqueness (ULTRA AGGRESSIVE: extremely strict)
                    if ($colorUniqueness < 0.15) {
                        $aiIndicators += 4; // Extremely strong AI indicator
                        $pixelAnalysisDetails['color_distribution'] = 'extremely_uniform_ai';
                    } else if ($colorUniqueness < 0.25) {
                        $aiIndicators += 3; // Very strong AI indicator
                        $pixelAnalysisDetails['color_distribution'] = 'very_uniform_ai';
                    } else if ($colorUniqueness < 0.35) {
                        $aiIndicators += 2; // Strong AI indicator
                        $pixelAnalysisDetails['color_distribution'] = 'too_uniform';
                    } else if ($colorUniqueness < 0.45) {
                        $aiIndicators++; // Moderate AI indicator
                        $pixelAnalysisDetails['color_distribution'] = 'somewhat_uniform';
                    } else if ($colorUniqueness > 0.65) {
                        $realIndicators += 4; // Extremely strong real indicator
                        $pixelAnalysisDetails['color_distribution'] = 'extremely_natural';
                    } else if ($colorUniqueness > 0.55) {
                        $realIndicators += 3; // Very strong real indicator
                        $pixelAnalysisDetails['color_distribution'] = 'very_natural';
                    } else if ($colorUniqueness > 0.5) {
                        $realIndicators += 2; // Strong real indicator
                        $pixelAnalysisDetails['color_distribution'] = 'natural';
                    } else {
                        $pixelAnalysisDetails['color_distribution'] = 'neutral';
                    }
                    
                    // NEW: Check texture variance (ULTRA AGGRESSIVE)
                    if ($avgTextureVariance < 3) {
                        $aiIndicators += 3; // Very strong AI indicator - extremely uniform
                        $pixelAnalysisDetails['texture'] = 'extremely_uniform_ai';
                    } else if ($avgTextureVariance < 7) {
                        $aiIndicators += 2; // Strong AI indicator
                        $pixelAnalysisDetails['texture'] = 'too_uniform_ai';
                    } else if ($avgTextureVariance < 12) {
                        $aiIndicators++; // Moderate AI indicator
                        $pixelAnalysisDetails['texture'] = 'somewhat_uniform';
                    } else if ($avgTextureVariance > 25) {
                        $realIndicators += 3; // Very strong real indicator
                        $pixelAnalysisDetails['texture'] = 'very_natural';
                    } else if ($avgTextureVariance > 18) {
                        $realIndicators += 2; // Strong real indicator
                        $pixelAnalysisDetails['texture'] = 'natural';
                    } else {
                        $pixelAnalysisDetails['texture'] = 'neutral';
                    }
                    
                    // Calculate pixel analysis score (ULTRA AGGRESSIVE)
                    // Harsher penalties for AI indicators
                    $netIndicators = $realIndicators - $aiIndicators;
                    
                    if ($netIndicators >= 7) {
                        $pixelAnalysisScore = 98; // Extremely strong real
                    } else if ($netIndicators >= 5) {
                        $pixelAnalysisScore = 92; // Very strong real
                    } else if ($netIndicators >= 3) {
                        $pixelAnalysisScore = 82; // Strong real
                    } else if ($netIndicators >= 1) {
                        $pixelAnalysisScore = 68; // Moderate real
                    } else if ($netIndicators >= -1) {
                        $pixelAnalysisScore = 45; // Neutral (lowered)
                    } else if ($netIndicators >= -3) {
                        $pixelAnalysisScore = 30; // Moderate AI
                    } else if ($netIndicators >= -5) {
                        $pixelAnalysisScore = 18; // Strong AI
                    } else if ($netIndicators >= -7) {
                        $pixelAnalysisScore = 8; // Very strong AI
                    } else {
                        $pixelAnalysisScore = 3; // Extremely strong AI
                    }
                    
                    // SOLUTION 1: "TOO PERFECT" DETECTION
                    // AI images tend to have ALL metrics in "perfect moderate" range
                    // Real images have SOME imperfection
                    $tooPerfectPenalty = 0;
                    $perfectCount = 0;
                    
                    // Check if edge sharpness is "too perfect" (moderate range)
                    if ($avgEdgeSharpness >= 18 && $avgEdgeSharpness <= 28) {
                        $perfectCount++;
                    }
                    
                    // Check if color uniqueness is "too perfect" (moderate range)
                    if ($colorUniqueness >= 0.35 && $colorUniqueness <= 0.50) {
                        $perfectCount++;
                    }
                    
                    // Check if texture variance is "too perfect" (moderate range)
                    if ($avgTextureVariance >= 8 && $avgTextureVariance <= 16) {
                        $perfectCount++;
                    }
                    
                    // If ALL 3 metrics are "perfect", this is suspicious
                    if ($perfectCount >= 3) {
                        $tooPerfectPenalty = 20; // Strong AI indicator
                        $pixelAnalysisDetails['too_perfect'] = 'all_metrics_moderate';
                    } else if ($perfectCount >= 2) {
                        $tooPerfectPenalty = 10; // Moderate AI indicator
                        $pixelAnalysisDetails['too_perfect'] = 'most_metrics_moderate';
                    } else {
                        $pixelAnalysisDetails['too_perfect'] = 'has_imperfection';
                    }
                    
                    // SOLUTION 2: SCENE PERFECTION ANALYSIS
                    // AI scenes tend to be too perfect (lighting, composition)
                    $scenePerfectionPenalty = 0;
                    
                    // Check for perfect color distribution (too balanced)
                    $colorBalance = $colorUniqueness;
                    if ($colorBalance >= 0.40 && $colorBalance <= 0.48) {
                        $scenePerfectionPenalty += 10; // Perfect color balance = AI (increased from 5)
                    }
                    
                    // Check for perfect edge distribution (too uniform)
                    if ($avgEdgeSharpness >= 20 && $avgEdgeSharpness <= 26) {
                        $scenePerfectionPenalty += 10; // Perfect edge uniformity = AI (increased from 5)
                    }
                    
                    $pixelAnalysisDetails['scene_perfection_penalty'] = $scenePerfectionPenalty;
                    
                    // SOLUTION 3: SKIN TEXTURE ANALYSIS
                    // Sample center region (likely face area for portraits)
                    $skinSmoothnessPenalty = 0;
                    $centerX = floor($width / 2);
                    $centerY = floor($height / 2);
                    $faceRegionSize = min(100, floor($width / 4));
                    
                    $skinSmoothness = 0;
                    $skinSamples = 0;
                    
                    for ($i = 0; $i < 500; $i++) {
                        $x = $centerX + rand(-$faceRegionSize, $faceRegionSize);
                        $y = $centerY + rand(-$faceRegionSize, $faceRegionSize);
                        
                        if ($x >= 1 && $x < $width - 1 && $y >= 1 && $y < $height - 1) {
                            $rgb = imagecolorat($imageResource, $x, $y);
                            $r = ($rgb >> 16) & 0xFF;
                            $g = ($rgb >> 8) & 0xFF;
                            $b = $rgb & 0xFF;
                            
                            // Check if this is skin tone (rough heuristic)
                            if ($r > 95 && $g > 40 && $b > 20 && $r > $g && $r > $b && abs($r - $g) > 15) {
                                // Calculate local smoothness
                                $neighbors = [
                                    imagecolorat($imageResource, $x-1, $y),
                                    imagecolorat($imageResource, $x+1, $y),
                                    imagecolorat($imageResource, $x, $y-1),
                                    imagecolorat($imageResource, $x, $y+1)
                                ];
                                
                                $localDiff = 0;
                                foreach ($neighbors as $neighbor) {
                                    $nr = ($neighbor >> 16) & 0xFF;
                                    $ng = ($neighbor >> 8) & 0xFF;
                                    $nb = $neighbor & 0xFF;
                                    $localDiff += abs($r - $nr) + abs($g - $ng) + abs($b - $nb);
                                }
                                $skinSmoothness += $localDiff / 4;
                                $skinSamples++;
                            }
                        }
                    }
                    
                    if ($skinSamples > 50) { // Enough skin samples detected
                        $avgSkinSmoothness = $skinSmoothness / $skinSamples;
                        
                        // AI skin is TOO smooth (< 8)
                        // Real skin has texture, pores (> 12)
                        if ($avgSkinSmoothness < 6) {
                            $skinSmoothnessPenalty = 15; // Very smooth = Strong AI indicator
                            $pixelAnalysisDetails['skin_texture'] = 'too_smooth_ai';
                        } else if ($avgSkinSmoothness < 10) {
                            $skinSmoothnessPenalty = 8; // Somewhat smooth = Moderate AI indicator
                            $pixelAnalysisDetails['skin_texture'] = 'somewhat_smooth';
                        } else {
                            $pixelAnalysisDetails['skin_texture'] = 'natural';
                        }
                        
                        $pixelAnalysisDetails['skin_smoothness_value'] = round($avgSkinSmoothness, 2);
                    } else {
                        $pixelAnalysisDetails['skin_texture'] = 'not_detected';
                    }
                    
                    // Apply all penalties to pixel analysis score
                    $totalPenalty = $tooPerfectPenalty + $scenePerfectionPenalty + $skinSmoothnessPenalty;
                    $pixelAnalysisScore = max(3, $pixelAnalysisScore - $totalPenalty);
                    
                    $pixelAnalysisDetails['ai_indicators'] = $aiIndicators;
                    $pixelAnalysisDetails['real_indicators'] = $realIndicators;
                    $pixelAnalysisDetails['net_indicators'] = $netIndicators;
                    $pixelAnalysisDetails['edge_sharpness_value'] = round($avgEdgeSharpness, 2);
                    $pixelAnalysisDetails['color_uniqueness'] = round($colorUniqueness, 2);
                    $pixelAnalysisDetails['texture_variance'] = round($avgTextureVariance, 2);
                    $pixelAnalysisDetails['too_perfect_penalty'] = $tooPerfectPenalty;
                    $pixelAnalysisDetails['scene_perfection_penalty'] = $scenePerfectionPenalty;
                    $pixelAnalysisDetails['skin_smoothness_penalty'] = $skinSmoothnessPenalty;
                    $pixelAnalysisDetails['total_penalty'] = $totalPenalty;
                    $pixelAnalysisDetails['score'] = $pixelAnalysisScore;
                    
                    imagedestroy($imageResource);
                    
                    Log::info('Pixel Analysis Result:', $pixelAnalysisDetails);
                    
                    // Combine metadata confidence with pixel analysis
                    // ULTRA AGGRESSIVE: 30% metadata, 70% pixel (maximum pixel priority)
                    // Almost entirely rely on pixel analysis to catch all AI types
                    $confidence = round(($confidence * 0.3) + ($pixelAnalysisScore * 0.7));
                    
                }
            } catch (\Exception $e) {
                Log::error('Pixel Analysis Error:', ['message' => $e->getMessage()]);
                // Keep original confidence if pixel analysis fails
            }
            
            
            // ENHANCED TRIPLE VOTING SYSTEM: GOOGLE CLOUD VISION FOR ALL IMAGES
            // Google Vision is now called for ALL images to maximize accuracy
            // This solves issues with sophisticated AI images that fool pixel analysis
            $googleVisionScore = null;
            $awsScore = null;
            $votingMethod = 'internal_only';
            $scores = [$confidence]; // Initialize with internal score
            
            // ALWAYS use Google Vision if enabled (not just gray area)
            Log::info('Using Google Vision for enhanced accuracy...');
            
            // Vote 1: Google Cloud Vision API (with API Key)
            if (env('GOOGLE_CLOUD_VISION_ENABLED', false)) {
                try {
                    $googleVisionScore = $this->callGoogleVision($filePath);    
                    Log::info('Google Vision Vote:', ['score' => $googleVisionScore]);
                } catch (\Exception $e) {
                    Log::error('Google Vision Error:', ['message' => $e->getMessage()]);
                }
            }
            
            // Vote 2: AWS Rekognition (DISABLED)
            // if (env('AWS_REKOGNITION_ENABLED', false)) {
            //     try {
            //         $awsScore = $this->callAWSRekognition($filePath);
            //         Log::info('AWS Rekognition Vote:', ['score' => $awsScore]);
            //     } catch (\Exception $e) {
            //         Log::error('AWS Rekognition Error:', ['message' => $e->getMessage()]);
            //     }
            // }
            
            // Calculate final score using voting
            $scores = [$confidence]; // Internal analysis score
            if ($googleVisionScore !== null) $scores[] = $googleVisionScore;
            if ($awsScore !== null) $scores[] = $awsScore;
            
            // If Google Vision is available, give it MORE weight (70% Google, 30% Internal)
            // Google Vision is more accurate for sophisticated AI detection
            if ($googleVisionScore !== null) {
                $finalScore = round(($confidence * 0.3) + ($googleVisionScore * 0.7));
                $confidence = $finalScore;
                $votingMethod = 'hybrid_google_vision';
                $usedGoogleVision = true;
            } else {
                // Fallback to internal only if Google Vision fails
                $confidence = $confidence;
                $votingMethod = 'internal_only';
            }
            
            // Update isAI based on final score
            $isAI = $confidence < 50;
            
            Log::info('Voting System Result:', [
                'internalScore' => $scores[0],
                'googleVisionScore' => $googleVisionScore,
                'awsScore' => $awsScore,
                'finalScore' => $confidence,
                'method' => $votingMethod
            ]);
            
            
            
            return response()->json([
                'success' => true,
                'isAI' => $isAI,
                'confidence' => $confidence,
                'score' => $isAI ? (100 - $confidence) : $confidence,
                'generator' => $detectedGenerator,
                'method' => $votingMethod,
                'usedGoogleVision' => $usedGoogleVision,
                'scores' => [
                    'internal' => $scores[0] ?? $confidence,
                    'googleVision' => $googleVisionScore,
                    'aws' => $awsScore
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Detection Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback
            return response()->json([
                'success' => true,
                'isAI' => false,
                'confidence' => 50,
                'score' => 50,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Call Clarifai API for image analysis
     * Returns confidence score (0-100, higher = more real)
     */
    private function callGoogleVision($filePath)
    {
        try {
            if (!env('GOOGLE_CLOUD_VISION_ENABLED', false)) {
                return null;
            }

            $apiKey = env('GOOGLE_CLOUD_VISION_API_KEY');
            if (!$apiKey) {
                \Log::error('Google Cloud Vision API key not found');
                return null;
            }

            // Read image file
            $imageContent = base64_encode(file_get_contents($filePath));

            // Prepare request
            $url = "https://vision.googleapis.com/v1/images:annotate?key=" . $apiKey;
            
            $requestData = [
                'requests' => [
                    [
                        'image' => [
                            'content' => $imageContent
                        ],
                        'features' => [
                            ['type' => 'LABEL_DETECTION', 'maxResults' => 20],
                            ['type' => 'SAFE_SEARCH_DETECTION'],
                            ['type' => 'IMAGE_PROPERTIES']
                        ]
                    ]
                ]
            ];

            // Make API call
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                \Log::error('Google Vision API failed', ['code' => $httpCode, 'response' => $response]);
                return null;
            }

            $result = json_decode($response, true);
            
            if (!isset($result['responses'][0])) {
                \Log::error('Google Vision: Invalid response format');
                return null;
            }

            $annotations = $result['responses'][0];
            
            // Calculate AI confidence score
            $aiScore = 0;
            $realScore = 0;

            // Check labels for AI indicators
            if (isset($annotations['labelAnnotations'])) {
                foreach ($annotations['labelAnnotations'] as $label) {
                    $description = strtolower($label['description']);
                    $score = $label['score'] * 100;

                    // AI indicators
                    if (in_array($description, ['art', 'cg artwork', 'digital art', 'illustration', 'drawing', 'painting', 'graphics', 'computer graphics', 'synthetic'])) {
                        $aiScore += $score * 0.8;
                    }

                    // Real indicators
                    if (in_array($description, ['photograph', 'photography', 'camera', 'real', 'natural', 'outdoor', 'indoor'])) {
                        $realScore += $score * 0.6;
                    }

                    // Impossible/unnatural objects
                    if (in_array($description, ['fantasy', 'fictional', 'mythical', 'surreal', 'impossible'])) {
                        $aiScore += $score * 1.2;
                    }
                }
            }

            // Calculate final confidence (0-100, where 0 = AI, 100 = Real)
            $totalScore = $aiScore + $realScore;
            if ($totalScore > 0) {
                $confidence = ($realScore / $totalScore) * 100;
            } else {
                $confidence = 50; // Neutral if no indicators
            }

            // Cap confidence
            $confidence = max(0, min(100, $confidence));

            \Log::info('Google Vision Vote', [
                'aiScore' => $aiScore,
                'realScore' => $realScore,
                'confidence' => $confidence
            ]);

            return $confidence;

        } catch (\Exception $e) {
            \Log::error('Google Vision Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Call AWS Rekognition for image analysis
     * Returns confidence score (0-100, higher = more real)
     */
    private function callAWSRekognition($filePath)
    {
        $rekognitionClient = new RekognitionClient([
            'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-2'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        
        $imageContent = file_get_contents($filePath);
        
        try {
            // Detect labels
            $result = $rekognitionClient->detectLabels([
                'Image' => [
                    'Bytes' => $imageContent,
                ],
                'MaxLabels' => 20,
                'MinConfidence' => 50,
            ]);
            
            $labels = $result['Labels'];
            
            // Analyze labels for AI indicators
            $aiIndicators = ['art', 'drawing', 'painting', 'illustration', 'graphic', 'digital', 'abstract'];
            $realIndicators = ['person', 'human', 'face', 'outdoor', 'nature', 'building', 'vehicle', 'animal'];
            
            $aiScore = 0;
            $realScore = 0;
            
            foreach ($labels as $label) {
                $name = strtolower($label['Name']);
                $confidence = $label['Confidence'];
                
                foreach ($aiIndicators as $indicator) {
                    if (stripos($name, $indicator) !== false) {
                        $aiScore += $confidence;
                    }
                }
                
                foreach ($realIndicators as $indicator) {
                    if (stripos($name, $indicator) !== false) {
                        $realScore += $confidence;
                    }
                }
            }
            
            // Also check for moderation labels (AI images often have unusual content)
            try {
                $moderationResult = $rekognitionClient->detectModerationLabels([
                    'Image' => [
                        'Bytes' => $imageContent,
                    ],
                    'MinConfidence' => 50,
                ]);
                
                // If moderation labels found, slightly decrease confidence
                if (count($moderationResult['ModerationLabels']) > 0) {
                    $aiScore += 10;
                }
            } catch (\Exception $e) {
                // Moderation check failed, continue
            }
            
            // Calculate confidence (higher = more real)
            $totalScore = $aiScore + $realScore;
            if ($totalScore == 0) {
                return 50; // Neutral if no relevant labels
            }
            
            return round(($realScore / $totalScore) * 100);
            
        } catch (AwsException $e) {
            throw new \Exception('AWS Rekognition Error: ' . $e->getMessage());
        }
    }
}
