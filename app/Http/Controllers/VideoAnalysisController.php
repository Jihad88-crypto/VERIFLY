<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoAnalysisController extends Controller
{
    /**
     * Detect AI in uploaded video
     */
    public function detectAI(Request $request)
    {
        try {
            Log::info('=== Video AI Detection Request Started ===');
            
            // Validate video file
            if (!$request->hasFile('video')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No video file provided'
                ], 400);
            }
            
            $video = $request->file('video');
            Log::info('Video received:', [
                'name' => $video->getClientOriginalName(),
                'size' => $video->getSize(),
                'mime' => $video->getMimeType()
            ]);
            
            // Validate video format
            $allowedMimes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
            if (!in_array($video->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid video format. Supported: MP4, MOV, AVI, WEBM'
                ], 400);
            }
            
            // Save video temporarily
            $videoPath = $video->store('temp_videos');
            $fullPath = storage_path('app/' . $videoPath);
            
            // STEP 1: Extract video metadata
            $metadata = $this->extractMetadata($fullPath);
            $metadataScore = $this->analyzeMetadata($metadata);
            
            Log::info('Metadata Analysis:', [
                'metadata' => $metadata,
                'score' => $metadataScore
            ]);
            
            // STEP 2: Extract frames from video
            $frameCount = 10; // Extract 10 frames
            $frames = $this->extractFrames($fullPath, $frameCount);
            
            if (empty($frames)) {
                // Cleanup
                Storage::delete($videoPath);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to extract frames. Please ensure FFmpeg is installed.'
                ], 500);
            }
            
            Log::info('Frames extracted:', ['count' => count($frames)]);
            
            // STEP 2.5: Try Hugging Face API (if enabled)
            $huggingFaceScore = null;
            $usedHuggingFace = false;
            
            if (env('HUGGINGFACE_ENABLED', false) && env('HUGGINGFACE_API_KEY')) {
                try {
                    Log::info('Attempting Hugging Face API analysis...');
                    $huggingFaceScore = $this->analyzeWithHuggingFace($frames);
                    $usedHuggingFace = true;
                    Log::info('Hugging Face API Success:', ['score' => $huggingFaceScore]);
                } catch (\Exception $e) {
                    Log::warning('Hugging Face API failed, using internal only:', [
                        'error' => $e->getMessage()
                    ]);
                    $huggingFaceScore = null;
                    $usedHuggingFace = false;
                }
            }
            
            // STEP 3: Analyze each frame (internal analysis)
            $frameScores = $this->analyzeFrames($frames);
            $frameAnalysisScore = array_sum($frameScores) / count($frameScores);
            
            Log::info('Frame Analysis:', [
                'scores' => $frameScores,
                'average' => $frameAnalysisScore
            ]);
            
            // STEP 4: Check temporal consistency
            $temporalScore = $this->checkTemporalConsistency($frameScores);
            
            Log::info('Temporal Analysis:', [
                'variance' => $this->calculateVariance($frameScores),
                'score' => $temporalScore
            ]);
            
            // STEP 5: Calculate final score
            // HYBRID: If HF available, use 60% HF + 40% Internal
            // Otherwise: 30% Metadata + 60% Frame + 10% Temporal
            
            if ($huggingFaceScore !== null) {
                // HYBRID MODE: 60% Hugging Face + 40% Internal
                $internalScore = round(
                    ($metadataScore * 0.3) + 
                    ($frameAnalysisScore * 0.6) + 
                    ($temporalScore * 0.1)
                );
                $finalScore = round(
                    ($huggingFaceScore * 0.6) + 
                    ($internalScore * 0.4)
                );
                $method = 'hybrid';
            } else {
                // INTERNAL ONLY: 30% Metadata + 60% Frame + 10% Temporal
                $finalScore = round(
                    ($metadataScore * 0.3) + 
                    ($frameAnalysisScore * 0.6) + 
                    ($temporalScore * 0.1)
                );
                $method = 'internal_only';
            }
            
            // Determine verdict
            $isAI = $finalScore < 50;
            
            // Cleanup temporary files
            Storage::delete($videoPath);
            foreach ($frames as $frame) {
                if (file_exists($frame)) {
                    unlink($frame);
                }
            }
            
            Log::info('Final Result:', [
                'metadataScore' => $metadataScore,
                'frameScore' => $frameAnalysisScore,
                'temporalScore' => $temporalScore,
                'finalScore' => $finalScore,
                'isAI' => $isAI
            ]);
            
            return response()->json([
                'success' => true,
                'isAI' => $isAI,
                'confidence' => $finalScore,
                'score' => $isAI ? (100 - $finalScore) : $finalScore,
                'details' => [
                    'metadata' => [
                        'score' => $metadataScore,
                        'info' => $metadata
                    ],
                    'frameAnalysis' => [
                        'score' => round($frameAnalysisScore),
                        'frameCount' => count($frameScores),
                        'frameScores' => $frameScores
                    ],
                    'temporal' => [
                        'score' => $temporalScore,
                        'variance' => round($this->calculateVariance($frameScores), 2)
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Video AI Detection Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Video analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Extract video metadata using FFprobe
     */
    private function extractMetadata($videoPath)
    {
        $metadata = [
            'duration' => null,
            'codec' => null,
            'fps' => null,
            'width' => null,
            'height' => null,
            'software' => null,
            'camera' => null
        ];
        
        // Try to get metadata using FFprobe
        $ffprobeCmd = "ffprobe -v quiet -print_format json -show_format -show_streams \"$videoPath\" 2>&1";
        $output = shell_exec($ffprobeCmd);
        
        if ($output) {
            $data = json_decode($output, true);
            
            if (isset($data['format'])) {
                $metadata['duration'] = $data['format']['duration'] ?? null;
                
                // Check for software/encoder tags
                $tags = $data['format']['tags'] ?? [];
                $metadata['software'] = $tags['encoder'] ?? $tags['software'] ?? null;
                $metadata['camera'] = $tags['make'] ?? $tags['model'] ?? null;
            }
            
            if (isset($data['streams'][0])) {
                $stream = $data['streams'][0];
                $metadata['codec'] = $stream['codec_name'] ?? null;
                $metadata['width'] = $stream['width'] ?? null;
                $metadata['height'] = $stream['height'] ?? null;
                
                // Calculate FPS
                if (isset($stream['r_frame_rate'])) {
                    $fps = $stream['r_frame_rate'];
                    if (strpos($fps, '/') !== false) {
                        list($num, $den) = explode('/', $fps);
                        $metadata['fps'] = $den > 0 ? round($num / $den, 2) : null;
                    }
                }
            }
        }
        
        return $metadata;
    }
    
    /**
     * Analyze metadata for AI indicators
     */
    private function analyzeMetadata($metadata)
    {
        $score = 50; // Start neutral
        
        // AI video generator signatures (expanded list)
        $aiSignatures = [
            'runway', 'pika', 'synthesia', 'gen-2', 'gen-3', 'gen2', 'gen3',
            'stable diffusion', 'deforum', 'ai', 'generated', 'midjourney',
            'dall-e', 'dalle', 'sora', 'luma', 'haiper', 'kling', 'veo',
            'animatediff', 'zeroscope', 'modelscope', 'cogvideo'
        ];
        
        // Check software/encoder
        if ($metadata['software']) {
            $software = strtolower($metadata['software']);
            
            foreach ($aiSignatures as $signature) {
                if (stripos($software, $signature) !== false) {
                    return 5; // Very high confidence it's AI
                }
            }
            
            // Real video software
            if (stripos($software, 'ffmpeg') !== false || 
                stripos($software, 'handbrake') !== false ||
                stripos($software, 'adobe') !== false) {
                $score += 10;
            }
        }
        
        // Check for camera info (stronger indicator)
        if ($metadata['camera']) {
            $score += 35; // Has camera info = likely real
        } else {
            $score -= 25; // No camera info = suspicious
        }
        
        // Check codec patterns
        // AI videos often use specific codecs
        if ($metadata['codec']) {
            $codec = strtolower($metadata['codec']);
            
            // Common real video codecs
            if (in_array($codec, ['h264', 'hevc', 'h265'])) {
                $score += 5;
            }
        }
        
        // Check FPS
        // AI videos often use 24fps (cinematic)
        // Real phone videos use 30fps or 60fps
        if ($metadata['fps']) {
            if ($metadata['fps'] == 24) {
                $score -= 10; // Suspicious (cinematic FPS)
            } else if ($metadata['fps'] >= 30) {
                $score += 10; // Likely real
            }
        }
        
        // Cap score between 0-100
        return max(0, min(100, $score));
    }
    
    /**
     * Extract frames from video using FFmpeg
     */
    private function extractFrames($videoPath, $count = 10)
    {
        $frames = [];
        $tempDir = storage_path('app/temp_frames');
        
        // Create temp directory if not exists
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        // Get video duration
        $durationCmd = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 \"$videoPath\" 2>&1";
        $duration = (float) shell_exec($durationCmd);
        
        if ($duration <= 0) {
            Log::error('Failed to get video duration');
            return [];
        }
        
        // Calculate interval between frames
        $interval = $duration / ($count + 1);
        
        // Extract frames at intervals
        for ($i = 1; $i <= $count; $i++) {
            $timestamp = $interval * $i;
            $framePath = $tempDir . '/frame_' . $i . '_' . time() . '.jpg';
            
            $cmd = "ffmpeg -ss $timestamp -i \"$videoPath\" -vframes 1 -q:v 2 \"$framePath\" 2>&1";
            shell_exec($cmd);
            
            if (file_exists($framePath)) {
                $frames[] = $framePath;
            }
        }
        
        return $frames;
    }
    
    /**
     * Analyze extracted frames
     */
    private function analyzeFrames($frames)
    {
        $scores = [];
        
        foreach ($frames as $framePath) {
            // Analyze frame using pixel analysis (similar to image analysis)
            $score = $this->analyzeFrame($framePath);
            $scores[] = $score;
        }
        
        return $scores;
    }
    
    /**
     * Analyze single frame (COMPREHENSIVE - same as image analysis)
     */
    private function analyzeFrame($framePath)
    {
        try {
            $imageResource = imagecreatefromjpeg($framePath);
            if (!$imageResource) {
                return 50; // Neutral if can't analyze
            }
            
            $width = imagesx($imageResource);
            $height = imagesy($imageResource);
            
            // Sample pixels (increased for better accuracy)
            $sampleSize = min(15000, floor(($width * $height) / 10));
            $edgeSharpness = 0;
            $colors = [];
            $textureVariance = 0;
            
            for ($i = 0; $i < $sampleSize; $i++) {
                $x = rand(1, $width - 2);
                $y = rand(1, $height - 2);
                
                $rgb = imagecolorat($imageResource, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Color variance
                $colorKey = sprintf('%d,%d,%d', floor($r/10)*10, floor($g/10)*10, floor($b/10)*10);
                $colors[$colorKey] = ($colors[$colorKey] ?? 0) + 1;
                
                // Edge sharpness
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
                
                // Texture variance (new)
                $localVariance = 0;
                for ($dx = -1; $dx <= 1; $dx++) {
                    for ($dy = -1; $dy <= 1; $dy++) {
                        if ($dx == 0 && $dy == 0) continue;
                        $nx = min(max($x + $dx, 0), $width - 1);
                        $ny = min(max($y + $dy, 0), $height - 1);
                        $nrgb = imagecolorat($imageResource, $nx, $ny);
                        $nr = ($nrgb >> 16) & 0xFF;
                        $localVariance += abs($r - $nr);
                    }
                }
                $textureVariance += $localVariance / 8;
            }
            
            // Calculate metrics
            $avgEdgeSharpness = $edgeSharpness / $sampleSize;
            $colorUniqueness = count($colors) / $sampleSize;
            $avgTextureVariance = $textureVariance / $sampleSize;
            
            // Base score (EXTREME: assume AI by default)
            $score = 30;
            
            // Edge sharpness analysis (AI tends to be smoother)
            if ($avgEdgeSharpness < 8) {
                $score -= 25; // Extreme smooth = Very Strong AI
            } else if ($avgEdgeSharpness < 12) {
                $score -= 20; // Very smooth = Strong AI
            } else if ($avgEdgeSharpness < 18) {
                $score -= 10; // Somewhat smooth = Moderate AI
            } else if ($avgEdgeSharpness > 40) {
                $score += 25; // Very sharp = Very Strong Real
            } else if ($avgEdgeSharpness > 30) {
                $score += 20; // Sharp = Strong Real
            } else if ($avgEdgeSharpness > 25) {
                $score += 10; // Moderate sharp = Moderate Real
            }
            
            // Color uniqueness analysis (AI tends to be more uniform)
            if ($colorUniqueness < 0.15) {
                $score -= 25; // Extreme uniform = Very Strong AI
            } else if ($colorUniqueness < 0.25) {
                $score -= 20; // Very uniform = Strong AI
            } else if ($colorUniqueness < 0.4) {
                $score -= 10; // Somewhat uniform = Moderate AI
            } else if ($colorUniqueness > 0.7) {
                $score += 25; // Very varied = Very Strong Real
            } else if ($colorUniqueness > 0.6) {
                $score += 20; // Varied = Strong Real
            } else if ($colorUniqueness > 0.5) {
                $score += 10; // Moderate varied = Moderate Real
            }
            
            // Texture variance analysis (new)
            if ($avgTextureVariance < 3) {
                $score -= 20; // Too smooth = Strong AI
            } else if ($avgTextureVariance < 7) {
                $score -= 10; // Somewhat smooth = Moderate AI
            } else if ($avgTextureVariance > 25) {
                $score += 20; // Very textured = Strong Real
            } else if ($avgTextureVariance > 18) {
                $score += 10; // Textured = Moderate Real
            }
            
            // SOLUTION 1: TOO PERFECT DETECTION
            $tooPerfectPenalty = 0;
            $perfectCount = 0;
            
            if ($avgEdgeSharpness >= 18 && $avgEdgeSharpness <= 28) {
                $perfectCount++;
            }
            if ($colorUniqueness >= 0.35 && $colorUniqueness <= 0.50) {
                $perfectCount++;
            }
            if ($avgTextureVariance >= 8 && $avgTextureVariance <= 16) {
                $perfectCount++;
            }
            
            if ($perfectCount >= 3) {
                $tooPerfectPenalty = 30; // All metrics perfect = AI (EXTREME)
            } else if ($perfectCount >= 2) {
                $tooPerfectPenalty = 15; // Most metrics perfect = Suspicious
            }
            
            // SOLUTION 2: SCENE PERFECTION ANALYSIS
            $scenePerfectionPenalty = 0;
            
            if ($colorUniqueness >= 0.40 && $colorUniqueness <= 0.48) {
                $scenePerfectionPenalty += 15; // Perfect color balance (EXTREME)
            }
            if ($avgEdgeSharpness >= 20 && $avgEdgeSharpness <= 26) {
                $scenePerfectionPenalty += 15; // Perfect edge uniformity (EXTREME)
            }
            
            // SOLUTION 3: SKIN TEXTURE ANALYSIS (for video portraits)
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
                    
                    // Check if skin tone
                    if ($r > 95 && $g > 40 && $b > 20 && $r > $g && $r > $b && abs($r - $g) > 15) {
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
            
            if ($skinSamples > 50) {
                $avgSkinSmoothness = $skinSmoothness / $skinSamples;
                
                if ($avgSkinSmoothness < 6) {
                    $skinSmoothnessPenalty = 25; // Very smooth skin = AI (EXTREME)
                } else if ($avgSkinSmoothness < 10) {
                    $skinSmoothnessPenalty = 12; // Somewhat smooth = Suspicious
                }
            }
            
            // Apply all penalties
            $totalPenalty = $tooPerfectPenalty + $scenePerfectionPenalty + $skinSmoothnessPenalty;
            $score = max(0, min(100, $score - $totalPenalty));
            
            imagedestroy($imageResource);
            
            return $score;
            
        } catch (\Exception $e) {
            Log::error('Frame analysis error:', ['message' => $e->getMessage()]);
            return 50;
        }
    }
    
    /**
     * Check temporal consistency across frames
     */
    private function checkTemporalConsistency($frameScores)
    {
        $variance = $this->calculateVariance($frameScores);
        
        // AI videos have low variance (too consistent)
        // Real videos have higher variance (natural changes)
        // Improved thresholds based on testing
        
        if ($variance < 2) {
            return 10; // Extremely consistent = Very strong AI indicator
        } else if ($variance < 4) {
            return 25; // Very consistent = Strong AI indicator
        } else if ($variance < 6) {
            return 40; // Somewhat consistent = Moderate AI indicator
        } else if ($variance > 12) {
            return 95; // Very high variance = Strong Real indicator
        } else if ($variance > 8) {
            return 80; // High variance = Moderate Real indicator
        } else {
            // Linear interpolation between 6-8
            return round(40 + (($variance - 6) / 2) * 40);
        }
    }
    
    /**
     * Calculate variance of scores
     */
    private function calculateVariance($scores)
    {
        $count = count($scores);
        if ($count == 0) return 0;
        
        $mean = array_sum($scores) / $count;
        $variance = 0;
        
        foreach ($scores as $score) {
            $variance += pow($score - $mean, 2);
        }
        
        return sqrt($variance / $count);
    }
    
    /**
     * Analyze video frames with Hugging Face API
     */
    private function analyzeWithHuggingFace($frames)
    {
        $apiKey = env('HUGGINGFACE_API_KEY');
        if (!$apiKey) {
            throw new \Exception('Hugging Face API key not configured');
        }
        
        // Use deepfake detection model
        $apiUrl = 'https://api-inference.huggingface.co/models/dima806/deepfake_vs_real_image_detection';
        
        // Select 5 representative frames (evenly distributed)
        $totalFrames = count($frames);
        $selectedFrames = [];
        $step = max(1, floor($totalFrames / 5));
        
        for ($i = 0; $i < min(5, $totalFrames); $i++) {
            $index = min($i * $step, $totalFrames - 1);
            $selectedFrames[] = $frames[$index];
        }
        
        Log::info('Hugging Face: Selected frames', ['count' => count($selectedFrames)]);
        
        $scores = [];
        
        foreach ($selectedFrames as $framePath) {
            try {
                // Read frame file
                $imageData = file_get_contents($framePath);
                
                // Call Hugging Face API
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                    ])
                    ->withBody($imageData, 'image/jpeg')
                    ->post($apiUrl);
                
                if ($response->successful()) {
                    $result = $response->json();
                    
                    // Parse result
                    // Response format: [{"label": "REAL", "score": 0.99}, {"label": "FAKE", "score": 0.01}]
                    if (is_array($result) && count($result) > 0) {
                        $realScore = 0;
                        $fakeScore = 0;
                        
                        foreach ($result as $item) {
                            if (isset($item['label']) && isset($item['score'])) {
                                if (strtoupper($item['label']) === 'REAL') {
                                    $realScore = $item['score'];
                                } elseif (strtoupper($item['label']) === 'FAKE') {
                                    $fakeScore = $item['score'];
                                }
                            }
                        }
                        
                        // Convert to 0-100 scale (higher = more real)
                        $frameScore = round($realScore * 100);
                        $scores[] = $frameScore;
                        
                        Log::info('HF Frame Result:', [
                            'real' => $realScore,
                            'fake' => $fakeScore,
                            'score' => $frameScore
                        ]);
                    }
                } else {
                    Log::warning('HF API Error:', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
                
            } catch (\Exception $e) {
                Log::warning('HF Frame Error:', ['error' => $e->getMessage()]);
                // Continue with other frames
            }
        }
        
        if (empty($scores)) {
            throw new \Exception('All Hugging Face API calls failed');
        }
        
        // Average scores
        $avgScore = array_sum($scores) / count($scores);
        
        Log::info('HF Final Score:', [
            'scores' => $scores,
            'average' => $avgScore
        ]);
        
        return round($avgScore);
    }
}
