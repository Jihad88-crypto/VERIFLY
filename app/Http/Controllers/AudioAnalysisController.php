<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AudioAnalysisController extends Controller
{
    /**
     * Detect AI in audio file
     */
    public function detectAI(Request $request)
    {
        try {
            Log::info('=== Audio AI Detection Request Started ===');
            
            // Validate audio file
            if (!$request->hasFile('audio')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No audio file provided'
                ], 400);
            }
            
            $audio = $request->file('audio');
            Log::info('Audio received:', [
                'name' => $audio->getClientOriginalName(),
                'size' => $audio->getSize(),
                'mime' => $audio->getMimeType()
            ]);
            
            // Validate audio format
            $allowedMimes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/mp4', 'audio/x-m4a'];
            if (!in_array($audio->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid audio format. Supported: MP3, WAV, OGG, M4A'
                ], 400);
            }
            
            // Save audio temporarily
            $audioPath = $audio->store('temp_audio');
            $fullPath = storage_path('app/' . $audioPath);
            
            // STEP 1: Extract metadata
            $metadata = $this->extractMetadata($fullPath);
            $metadataScore = $this->analyzeMetadata($metadata);
            
            Log::info('Metadata Analysis:', [
                'metadata' => $metadata,
                'score' => $metadataScore
            ]);
            
            // STEP 2: Try Aurigin.ai API (if enabled)
            $auriginScore = null;
            $usedAurigin = false;
            
            if (env('AURIGIN_ENABLED', false) && env('AURIGIN_API_KEY')) {
                try {
                    Log::info('Attempting Aurigin.ai API analysis...');
                    $auriginScore = $this->analyzeWithAurigin($fullPath);
                    $usedAurigin = true;
                    Log::info('Aurigin.ai API Success:', ['score' => $auriginScore]);
                } catch (\Exception $e) {
                    Log::warning('Aurigin.ai API failed, using metadata only:', [
                        'error' => $e->getMessage()
                    ]);
                    $auriginScore = null;
                    $usedAurigin = false;
                }
            }
            
            // STEP 3: Calculate final score
            // If Aurigin available, use 100% Aurigin (it's accurate enough)
            // Otherwise: 100% Metadata
            
            if ($auriginScore !== null) {
                // AURIGIN MODE: Use Aurigin score directly
                $finalScore = $auriginScore;
                $method = 'aurigin';
            } else {
                // METADATA ONLY
                $finalScore = $metadataScore;
                $method = 'metadata_only';
            }
            
            // Determine verdict (LOWERED THRESHOLD to reduce false positives)
            $isAI = $finalScore < 40;
            
            
            Log::info('Final Result:', [
                'metadataScore' => $metadataScore,
                'auriginScore' => $auriginScore,
                'finalScore' => $finalScore,
                'isAI' => $isAI,
                'method' => $method
            ]);
            
            $response = response()->json([
                'success' => true,
                'isAI' => $isAI,
                'confidence' => $finalScore,
                'score' => $finalScore,
                'method' => $method,
                'usedAurigin' => $usedAurigin,
                'details' => [
                    'metadata' => [
                        'score' => $metadataScore,
                        'data' => $metadata
                    ],
                    'aurigin' => $auriginScore ? [
                        'score' => $auriginScore,
                        'used' => true
                    ] : null
                ]
            ]);
            
            // Cleanup temporary file AFTER all analysis is complete
            Storage::delete($audioPath);
            
            return $response;
            
        } catch (\Exception $e) {
            // Cleanup on error
            if (isset($audioPath)) {
                Storage::delete($audioPath);
            }
            
            Log::error('Audio detection error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Extract audio metadata using FFprobe
     */
    private function extractMetadata($audioPath)
    {
        try {
            // Try to use FFprobe to extract metadata
            $command = "ffprobe -v quiet -print_format json -show_format -show_streams \"$audioPath\" 2>&1";
            $output = shell_exec($command);
            
            if ($output) {
                $data = json_decode($output, true);
                
                if ($data && isset($data['format'])) {
                    return [
                        'format' => $data['format']['format_name'] ?? 'unknown',
                        'duration' => $data['format']['duration'] ?? 0,
                        'bit_rate' => $data['format']['bit_rate'] ?? 0,
                        'encoder' => $data['format']['tags']['encoder'] ?? null,
                        'sample_rate' => $data['streams'][0]['sample_rate'] ?? null,
                        'channels' => $data['streams'][0]['channels'] ?? null,
                        'codec' => $data['streams'][0]['codec_name'] ?? null
                    ];
                }
            }
            
            // Fallback: basic metadata
            return [
                'format' => 'unknown',
                'duration' => 0,
                'bit_rate' => 0,
                'encoder' => null,
                'sample_rate' => null,
                'channels' => null,
                'codec' => null
            ];
            
        } catch (\Exception $e) {
            Log::error('Metadata extraction error:', ['message' => $e->getMessage()]);
            return [
                'format' => 'unknown',
                'duration' => 0,
                'bit_rate' => 0,
                'encoder' => null,
                'sample_rate' => null,
                'channels' => null,
                'codec' => null
            ];
        }
    }
    
    /**
     * Analyze metadata for AI signatures
     */
    private function analyzeMetadata($metadata)
    {
        // BALANCED: Start neutral, penalize AI patterns
        $score = 45; // Base score (balanced)
        
        // Check for AI voice generator signatures
        $aiSignatures = [
            'elevenlabs', 'resemble', 'descript', 'murf', 'play.ht',
            'speechify', 'lovo', 'wellsaid', 'synthesia', 'tts'
        ];
        
        $encoder = strtolower($metadata['encoder'] ?? '');
        foreach ($aiSignatures as $signature) {
            if (strpos($encoder, $signature) !== false) {
                $score = 10; // Strong AI signature
                Log::info('AI signature detected in encoder:', ['encoder' => $encoder]);
                break;
            }
        }
        
        // Check codec patterns
        $codec = strtolower($metadata['codec'] ?? '');
        if (in_array($codec, ['aac', 'mp3'])) {
            // Check for perfect bitrates (AI tends to use standard bitrates)
            $bitRate = intval($metadata['bit_rate'] ?? 0);
            $perfectBitrates = [128000, 192000, 256000, 320000]; // Common AI bitrates
            
            if (in_array($bitRate, $perfectBitrates)) {
                $score -= 10; // Suspicious perfect bitrate
            }
        }
        
        // Check sample rate consistency
        $sampleRate = intval($metadata['sample_rate'] ?? 0);
        $perfectSampleRates = [44100, 48000]; // AI tends to use these
        
        if (in_array($sampleRate, $perfectSampleRates)) {
            $score -= 3; // Slightly suspicious (reduced penalty)
        }
        
        // Unknown format is slightly suspicious
        if ($metadata['format'] === 'unknown' || empty($metadata['format'])) {
            $score -= 5; // Reduced penalty
        } else {
            // Known format with metadata = likely real
            if (!empty($metadata['codec']) && $metadata['duration'] > 0) {
                $score += 15; // Bonus for real characteristics
            }
        }
        
        // Ensure score is within bounds
        return max(0, min(100, $score));
    }
    
    
    /**
     * Analyze audio with Aurigin.ai API
     */
    private function analyzeWithAurigin($audioPath)
    {
        $apiKey = env('AURIGIN_API_KEY');
        if (!$apiKey) {
            throw new \Exception('Aurigin API key not configured');
        }
        
        // Check if file exists
        if (!file_exists($audioPath)) {
            throw new \Exception('Audio file not found: ' . $audioPath);
        }
        
        // Aurigin API endpoint
        $apiUrl = 'https://api.aurigin.ai/v1/detect';
        
        // Call Aurigin API with file upload
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])
            ->attach('file', file_get_contents($audioPath), basename($audioPath))
            ->post($apiUrl);
        
        if ($response->successful()) {
            $result = $response->json();
            
            Log::info('Aurigin API Response:', ['result' => $result]);
            
            // Parse result
            // Expected format: {"is_deepfake": false, "confidence": 0.95, "score": 95.0}
            // or {"is_deepfake": true, "deepfake_probability": 0.85}
            
            if (isset($result['is_deepfake'])) {
                $isDeepfake = $result['is_deepfake'];
                
                // Get confidence/probability
                $confidence = $result['confidence'] ?? $result['deepfake_probability'] ?? 0.5;
                
                // Convert to 0-100 scale (higher = more real)
                if ($isDeepfake) {
                    // If deepfake, score should be low (0-40)
                    $score = round((1 - $confidence) * 100);
                    $score = max(5, min(40, $score)); // Clamp to 5-40 for deepfakes
                } else {
                    // If real, score should be high (60-95)
                    $score = round($confidence * 100);
                    $score = max(60, min(95, $score)); // Clamp to 60-95 for real
                }
                
                Log::info('Aurigin Audio Result:', [
                    'is_deepfake' => $isDeepfake,
                    'confidence' => $confidence,
                    'score' => $score
                ]);
                
                return $score;
            }
            
            throw new \Exception('Invalid Aurigin API response format');
            
        } else {
            Log::warning('Aurigin API Error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Aurigin API request failed: ' . $response->status());
        }
    }
}
