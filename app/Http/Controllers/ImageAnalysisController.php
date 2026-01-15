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
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::info('🔍 NEW IMAGE ANALYSIS REQUEST - ' . now()->format('Y-m-d H:i:s'));
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            
            // Validate - accept any file
            if (!$request->hasFile('image')) {
                Log::error('❌ NO FILE PROVIDED IN REQUEST');
                return response()->json([
                    'success' => false,
                    'error' => 'No image file provided'
                ], 400);
            }
            
            $image = $request->file('image');
            Log::info('📁 FILE RECEIVED:', [
                'name' => $image->getClientOriginalName(),
                'size' => number_format($image->getSize()) . ' bytes',
                'mime' => $image->getMimeType(),
                'extension' => $image->getClientOriginalExtension()
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
                
                // Check for phone camera indicators
                $isPhoneCamera = false;
                if ($exifData) {
                    // EXPANDED: Added more brands (30+ total)
                    $phoneBrands = [
                        'samsung', 'apple', 'iphone', 'xiaomi', 'oppo', 'vivo', 
                        'huawei', 'oneplus', 'google', 'pixel', 'motorola', 'nokia',
                        'lg', 'sony', 'asus', 'realme', 'poco', 'redmi',
                        // NEW BRANDS:
                        'infinix', 'tecno', 'lenovo', 'zte', 'honor', 'meizu',
                        'coolpad', 'blackberry', 'htc', 'sharp', 'panasonic',
                        'fujitsu', 'kyocera', 'mi', 'galaxy'
                    ];
                    
                    $make = strtolower($exifData['Make'] ?? '');
                    $model = strtolower($exifData['Model'] ?? '');
                    
                    // DEBUG LOGGING: Track actual Make/Model values
                    Log::info('Metadata Make/Model:', [
                        'make' => $make ?: 'EMPTY',
                        'model' => $model ?: 'EMPTY',
                        'hasCamera' => $hasCamera,
                        'hasDateTime' => $hasDateTime
                    ]);
                    
                    foreach ($phoneBrands as $brand) {
                        if (stripos($make, $brand) !== false || stripos($model, $brand) !== false) {
                            $isPhoneCamera = true;
                            Log::info('Phone camera detected:', [
                                'make' => $exifData['Make'] ?? 'unknown',
                                'model' => $exifData['Model'] ?? 'unknown',
                                'matched_brand' => $brand
                            ]);
                            break;
                        }
                    }
                    
                    // If not detected, log for debugging
                    if (!$isPhoneCamera && ($hasCamera || $hasDateTime)) {
                        Log::warning('Phone camera NOT detected despite having metadata:', [
                            'make' => $exifData['Make'] ?? 'NOT SET',
                            'model' => $exifData['Model'] ?? 'NOT SET'
                        ]);
                    }
                }
                
                // Determine confidence based on metadata
                if ($isPhoneCamera) {
                    // Phone camera detected = high confidence it's real
                    $confidence = 80; // High confidence for phone cameras
                } else if ($hasCamera && ($hasDateTime || isset($exifData['Software']))) {
                    // Has camera Make/Model + additional metadata = likely real camera
                    // FIXED: Only give 75% if has actual camera info (not just DateTime)
                    $confidence = 75; // Moderate-high confidence
                    $isAI = false;
                } else {
                    // No camera info OR only has DateTime = SUSPICIOUS
                    // Common for downloaded/shared AI images
                    $confidence = 52; // Neutral/slightly favor real
                    $isAI = false;
                }
            }
            
            Log::info('File Signature Analysis Result:', [
                'isAI' => $isAI,
                'confidence' => $confidence,
                'generator' => $detectedGenerator,
                'hasExif' => !empty($exifData)
            ]);
            
            // ============================================
            // SIGHTENGINE AI DETECTION (MOVED HERE TO ENSURE EXECUTION)
            // ============================================
            Log::info('🔍 CHECKING SIGHTENGINE INTEGRATION...');
            
            $sightengineScore = null;
            $sightengineEnabled = env('SIGHTENGINE_ENABLED', false);
            $sightengineUser = env('SIGHTENGINE_API_USER');
            $sightengineSecret = env('SIGHTENGINE_API_SECRET');
            
            Log::info('Sightengine Config:', [
                'enabled' => $sightengineEnabled,
                'has_user' => !empty($sightengineUser),
                'has_secret' => !empty($sightengineSecret),
                'user_value' => $sightengineUser
            ]);
            
            if ($sightengineEnabled) {
                try {
                    Log::info('✅ Sightengine ENABLED - Starting AI detection...');
                    
                    if ($sightengineUser && $sightengineSecret) {
                        // Initialize Sightengine client
                        $client = new \Sightengine\SightengineClient($sightengineUser, $sightengineSecret);
                        
                        // Check for AI-generated content and text (watermarks)
                        $output = $client->check(['genai', 'text'])->set_file($filePath);
                        
                        Log::info('Sightengine API Response:', (array)$output);
                        
                        if (isset($output->status) && $output->status === 'success') {
                            // AI-generated detection score (0-1, higher = more likely AI)
                            $aiProbability = $output->type->ai_generated ?? 0;
                            
                            // Convert to our scale (0-100, lower = more likely AI)
                            $sightengineScore = round((1 - $aiProbability) * 100);
                            
                            // Check for watermarks/text
                            $hasWatermark = false;
                            if (isset($output->text) && isset($output->text->has_text) && $output->text->has_text) {
                                $hasWatermark = true;
                                Log::info('Watermark/Text detected in image');
                            }
                            
                            Log::info('Sightengine Analysis:', [
                                'ai_probability' => $aiProbability,
                                'sightengine_score' => $sightengineScore,
                                'has_watermark' => $hasWatermark
                            ]);
                        }
                    } else {
                        Log::warning('Sightengine credentials missing!');
                    }
                } catch (\Exception $e) {
                    Log::error('Sightengine API failed:', [
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                Log::info('❌ Sightengine DISABLED in config');
            }
            
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
                    
                    // AI indicators (signs of AI generation)
                    if ($avgEdgeSharpness > 30) $aiIndicators++; // Too sharp
                    if ($colorUniqueness < 0.12) $aiIndicators++; // STRICTER: Too uniform colors (was 0.15)
                    if ($avgTextureVariance < 30) $aiIndicators++; // Too smooth texture
                    
                    // Real indicators (signs of real photo)
                    if ($avgEdgeSharpness >= 15 && $avgEdgeSharpness <= 30) $realIndicators++; // Natural sharpness
                    if ($colorUniqueness >= 0.12) $realIndicators++; // ADJUSTED: Natural color variety (was 0.15)
                    if ($avgTextureVariance >= 40) $realIndicators++; // Natural texture variance
                    
                    // Check edge sharpness (BALANCED: less aggressive)
                    if ($avgEdgeSharpness < 5) {
                        $aiIndicators += 3; // Strong AI indicator (reduced from 4)
                        $pixelAnalysisDetails['edge_sharpness'] = 'extremely_smooth_ai';
                    } else if ($avgEdgeSharpness < 10) {
                        $aiIndicators += 2; // Moderate AI indicator (reduced from 3)
                        $pixelAnalysisDetails['edge_sharpness'] = 'very_smooth_ai';
                    } else if ($avgEdgeSharpness < 15) {
                        $aiIndicators++; // Weak AI indicator (reduced from 2)
                        $pixelAnalysisDetails['edge_sharpness'] = 'somewhat_smooth';
                    } else if ($avgEdgeSharpness > 35) {
                        $realIndicators += 4; // Extremely strong real indicator
                        $pixelAnalysisDetails['edge_sharpness'] = 'extremely_natural';
                    } else if ($avgEdgeSharpness > 28) {
                        $realIndicators += 3; // Very strong real indicator (easier)
                        $pixelAnalysisDetails['edge_sharpness'] = 'very_natural';
                    } else if ($avgEdgeSharpness > 22) {
                        $realIndicators += 2; // Strong real indicator (easier)
                        $pixelAnalysisDetails['edge_sharpness'] = 'natural';
                    } else if ($avgEdgeSharpness > 16) {
                        $realIndicators++; // NEW: Reward moderate sharpness
                        $pixelAnalysisDetails['edge_sharpness'] = 'moderate';
                    } else {
                        $pixelAnalysisDetails['edge_sharpness'] = 'neutral';
                    }
                    
                    // Check color uniqueness (BALANCED: less aggressive)
                    if ($colorUniqueness < 0.12) {
                        $aiIndicators += 3; // Strong AI indicator (reduced from 4)
                        $pixelAnalysisDetails['color_distribution'] = 'extremely_uniform_ai';
                    } else if ($colorUniqueness < 0.20) {
                        $aiIndicators += 2; // Moderate AI indicator (reduced from 3)
                        $pixelAnalysisDetails['color_distribution'] = 'very_uniform_ai';
                    } else if ($colorUniqueness < 0.30) {
                        $aiIndicators++; // Weak AI indicator (reduced from 2)
                        $pixelAnalysisDetails['color_distribution'] = 'somewhat_uniform';
                    } else if ($colorUniqueness > 0.60) {
                        $realIndicators += 4; // Extremely strong real indicator (easier)
                        $pixelAnalysisDetails['color_distribution'] = 'extremely_natural';
                    } else if ($colorUniqueness > 0.50) {
                        $realIndicators += 3; // Very strong real indicator (easier)
                        $pixelAnalysisDetails['color_distribution'] = 'very_natural';
                    } else if ($colorUniqueness > 0.42) {
                        $realIndicators += 2; // Strong real indicator (easier)
                        $pixelAnalysisDetails['color_distribution'] = 'natural';
                    } else if ($colorUniqueness > 0.35) {
                        $realIndicators++; // NEW: Reward moderate variety
                        $pixelAnalysisDetails['color_distribution'] = 'moderate';
                    } else {
                        $pixelAnalysisDetails['color_distribution'] = 'neutral';
                    }
                    
                    // Check texture variance (BALANCED: less aggressive)
                    if ($avgTextureVariance < 2) {
                        $aiIndicators += 3; // Strong AI indicator (reduced from 3)
                        $pixelAnalysisDetails['texture'] = 'extremely_uniform_ai';
                    } else if ($avgTextureVariance < 5) {
                        $aiIndicators += 2; // Moderate AI indicator (reduced from 2)
                        $pixelAnalysisDetails['texture'] = 'too_uniform_ai';
                    } else if ($avgTextureVariance < 10) {
                        $aiIndicators++; // Weak AI indicator (reduced from 1)
                        $pixelAnalysisDetails['texture'] = 'somewhat_uniform';
                    } else if ($avgTextureVariance > 22) {
                        $realIndicators += 3; // Very strong real indicator (easier)
                        $pixelAnalysisDetails['texture'] = 'very_natural';
                    } else if ($avgTextureVariance > 16) {
                        $realIndicators += 2; // Strong real indicator (easier)
                        $pixelAnalysisDetails['texture'] = 'natural';
                    } else if ($avgTextureVariance > 11) {
                        $realIndicators++; // NEW: Reward moderate texture
                        $pixelAnalysisDetails['texture'] = 'moderate';
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
                    
                    // STRICTER: If too many metrics are "perfect", apply heavy penalty
                    if ($perfectCount >= 3) {
                        $tooPerfectPenalty = 20; // INCREASED from 15 - AI images are suspiciously perfect
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
                    
                    // SOLUTION 3: SKIN TEXTURE ANALYSIS (for portraits)
                    // AI tends to over-smooth skin
                    $skinSmoothnessValue = 0;
                    $skinPixelCount = 0;
                    $skinSmoothnessPenalty = 0;
                    
                    // Detect skin tones and analyze smoothness
                    for ($i = 0; $i < min(5000, $sampleSize); $i++) {
                        $x = rand(1, $width - 2);
                        $y = rand(1, $height - 2);
                        
                        $rgb = imagecolorat($imageResource, $x, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;
                        
                        // Detect skin tones (simplified)
                        if ($r > 95 && $g > 40 && $b > 20 && 
                            $r > $g && $r > $b && 
                            abs($r - $g) > 15) {
                            $skinPixelCount++;
                            
                            // Calculate local variance (smoothness)
                            $variance = 0;
                            for ($dx = -1; $dx <= 1; $dx++) {
                                for ($dy = -1; $dy <= 1; $dy++) {
                                    if ($dx == 0 && $dy == 0) continue;
                                    $nx = min(max($x + $dx, 0), $width - 1);
                                    $ny = min(max($y + $dy, 0), $height - 1);
                                    $nrgb = imagecolorat($imageResource, $nx, $ny);
                                    $nr = ($nrgb >> 16) & 0xFF;
                                    $variance += abs($r - $nr);
                                }
                            }
                            $skinSmoothnessValue += $variance / 8;
                        }
                    }
                    
                    if ($skinPixelCount > 100) {
                        $avgSkinSmoothness = $skinSmoothnessValue / $skinPixelCount;
                        
                        // STRICTER: AI skin is TOO smooth (low variance)
                        if ($avgSkinSmoothness > 25) { // LOWERED from 30 - more strict
                            $realIndicators++;
                            $pixelAnalysisDetails['skin_texture'] = 'natural';
                        } else {
                            $aiIndicators++;
                            $skinSmoothnessPenalty = 10;
                            $pixelAnalysisDetails['skin_texture'] = 'too_smooth_ai';
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
                    
                    // Save metadata-only score before combining with pixel
                    $metadataOnlyScore = $confidence;
                    
                    // Combine metadata confidence with pixel analysis
                    // OPTIMIZED: Increased metadata weight for better accuracy
                    if ($hasCamera || $hasDateTime) {
                        // Has metadata: 70% metadata, 30% pixel
                        // Prioritize reliable EXIF data over visual analysis
                        $confidence = round(($confidence * 0.7) + ($pixelAnalysisScore * 0.3));
                    } else {
                        // No metadata: 50% metadata, 50% pixel (balanced)
                        $confidence = round(($confidence * 0.5) + ($pixelAnalysisScore * 0.5));
                    }
                    
                }
            } catch (\Exception $e) {
                Log::error('Pixel Analysis Error:', ['message' => $e->getMessage()]);
                // Keep original confidence if pixel analysis fails
            }
            
            // SIGHTENGINE AI DETECTION
            Log::info('🔍 CHECKING SIGHTENGINE INTEGRATION...');
            
            $sightengineScore = null;
            $sightengineEnabled = env('SIGHTENGINE_ENABLED', false);
            $sightengineUser = env('SIGHTENGINE_API_USER');
            $sightengineSecret = env('SIGHTENGINE_API_SECRET');
            
            Log::info('Sightengine Config:', [
                'enabled' => $sightengineEnabled,
                'has_user' => !empty($sightengineUser),
                'has_secret' => !empty($sightengineSecret),
                'user_value' => $sightengineUser
            ]);
            
            if ($sightengineEnabled) {
                try {
                    Log::info('✅ Sightengine ENABLED - Starting AI detection...');
                    
                    if ($sightengineUser && $sightengineSecret) {
                        // Initialize Sightengine client
                        $client = new \Sightengine\SightengineClient($sightengineUser, $sightengineSecret);
                        
                        // Check for AI-generated content and text (watermarks)
                        $output = $client->check(['genai', 'text'])->set_file($filePath);
                        
                        Log::info('Sightengine API Response:', (array)$output);
                        
                        if (isset($output->status) && $output->status === 'success') {
                            // AI-generated detection score (0-1, higher = more likely AI)
                            $aiProbability = $output->type->ai_generated ?? 0;
                            
                            // Convert to our scale (0-100, lower = more likely AI)
                            $sightengineScore = round((1 - $aiProbability) * 100);
                            
                            // Check for watermarks/text
                            $hasWatermark = false;
                            if (isset($output->text) && isset($output->text->has_text) && $output->text->has_text) {
                                $hasWatermark = true;
                                Log::info('Watermark/Text detected in image');
                            }
                            
                            Log::info('Sightengine Analysis:', [
                                'ai_probability' => $aiProbability,
                                'sightengine_score' => $sightengineScore,
                                'has_watermark' => $hasWatermark
                            ]);
                            
                            // Combine Sightengine with existing confidence
                            // Weight: 40% Sightengine, 60% internal (metadata + pixel)
                            $beforeSightengine = $confidence;
                            $confidence = round(($confidence * 0.6) + ($sightengineScore * 0.4));
                            
                            Log::info('Combined confidence after Sightengine:', [
                                'before' => $beforeSightengine,
                                'sightengine' => $sightengineScore,
                                'after' => $confidence
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Sightengine API failed:', [
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // CRITICAL FIX: Initialize $scores AFTER pixel+metadata combination
            // This ensures $scores[0] contains the FINAL combined score
            $scores = [$confidence]; // Internal analysis score (metadata + pixel combined)
            
            // ENHANCED TRIPLE VOTING SYSTEM: GOOGLE CLOUD VISION FOR ALL IMAGES
            // Google Vision is now called for ALL images to maximize accuracy
            // This solves issues with sophisticated AI images that fool pixel analysis
            $googleVisionScore = null;
            $awsScore = null;
            $votingMethod = 'internal_only';
            
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
            // $scores already initialized above with combined metadata+pixel score
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
            // BALANCED: Threshold 45% (reduced from 50%)
            $isAI = $confidence < 45;
            
            Log::info('Voting System Result:', [
                'internalScore' => $scores[0],
                'googleVisionScore' => $googleVisionScore,
                'awsScore' => $awsScore,
                'finalScore' => $confidence,
                'method' => $votingMethod
            ]);
            
            
            // CRITICAL DEBUG: Check $exifData state before building response
            Log::info('🔬 EXIF DATA STATE BEFORE RESPONSE:', [
                'exifData_is_array' => is_array($exifData),
                'exifData_is_false' => $exifData === false,
                'exifData_empty' => empty($exifData),
                'Make_value' => $exifData['Make'] ?? 'NOT SET',
                'Model_value' => $exifData['Model'] ?? 'NOT SET'
            ]);
            
            // Log metadata being sent to frontend
            Log::info('📤 SENDING RESPONSE TO FRONTEND:', [
                'confidence' => $confidence,
                'isAI' => $isAI,
                'metadata_make' => $exifData['Make'] ?? 'NULL',
                'metadata_model' => $exifData['Model'] ?? 'NULL',
                'metadata_datetime' => $exifData['DateTime'] ?? $exifData['DateTimeOriginal'] ?? 'NULL',
                'metadata_software' => $exifData['Software'] ?? 'NULL'
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
                    'internal' => $scores[0] ?? $confidence,  // Final combined score
                    'metadata' => $metadataOnlyScore ?? 80,   // Metadata-only score (before pixel combination)
                    'pixel' => $pixelAnalysisScore ?? 50,     // Pixel-only score
                    'sightengine' => $sightengineScore,       // Sightengine AI detection score
                    'googleVision' => $googleVisionScore,
                    'aws' => $awsScore
                ],
                'metadata' => [
                    'make' => $exifData['Make'] ?? null,
                    'model' => $exifData['Model'] ?? null,
                    'datetime' => $exifData['DateTime'] ?? $exifData['DateTimeOriginal'] ?? null,
                    'software' => $exifData['Software'] ?? null,
                    'width' => $exifData['COMPUTED']['Width'] ?? $exifData['ExifImageWidth'] ?? null,
                    'height' => $exifData['COMPUTED']['Height'] ?? $exifData['ExifImageLength'] ?? null,
                    'filesize' => $exifData['FileSize'] ?? null,
                    'iso' => $exifData['ISOSpeedRatings'] ?? null,
                    'aperture' => $exifData['FNumber'] ?? $exifData['ApertureValue'] ?? null,
                    'exposure' => $exifData['ExposureTime'] ?? null,
                    'gps_latitude' => isset($exifData['GPSLatitude']) ? $this->formatGPS($exifData['GPSLatitude'], $exifData['GPSLatitudeRef'] ?? 'N') : null,
                    'gps_longitude' => isset($exifData['GPSLongitude']) ? $this->formatGPS($exifData['GPSLongitude'], $exifData['GPSLongitudeRef'] ?? 'E') : null
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
    
    /**
     * Format GPS coordinates from EXIF format to decimal degrees
     */
    private function formatGPS($coordinate, $ref)
    {
        if (!is_array($coordinate) || count($coordinate) < 3) {
            return null;
        }
        
        // Convert degrees/minutes/seconds to decimal
        $degrees = $this->evalFraction($coordinate[0]);
        $minutes = $this->evalFraction($coordinate[1]);
        $seconds = $this->evalFraction($coordinate[2]);
        
        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        
        // Apply direction (N/S for latitude, E/W for longitude)
        if ($ref == 'S' || $ref == 'W') {
            $decimal *= -1;
        }
        
        return round($decimal, 6);
    }
    
    /**
     * Evaluate fraction string (e.g., "123/1" to 123)
     */
    private function evalFraction($fraction)
    {
        if (is_numeric($fraction)) {
            return $fraction;
        }
        
        $parts = explode('/', $fraction);
        if (count($parts) == 2 && $parts[1] != 0) {
            return $parts[0] / $parts[1];
        }
        
        return 0;
    }
}
