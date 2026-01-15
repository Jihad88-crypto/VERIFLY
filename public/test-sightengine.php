<?php
// Test Sightengine Integration
require __DIR__ . '/../vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

echo "<h1>Sightengine Integration Test</h1>";

// Check if enabled
$enabled = $_ENV['SIGHTENGINE_ENABLED'] ?? false;
echo "<p><strong>Enabled:</strong> " . ($enabled ? 'YES ✅' : 'NO ❌') . "</p>";

// Check credentials
$apiUser = $_ENV['SIGHTENGINE_API_USER'] ?? null;
$apiSecret = $_ENV['SIGHTENGINE_API_SECRET'] ?? null;

echo "<p><strong>API User:</strong> " . ($apiUser ? $apiUser . ' ✅' : 'NOT SET ❌') . "</p>";
echo "<p><strong>API Secret:</strong> " . ($apiSecret ? substr($apiSecret, 0, 10) . '... ✅' : 'NOT SET ❌') . "</p>";

// Test API call
if ($enabled && $apiUser && $apiSecret) {
    echo "<hr>";
    echo "<h2>Testing API Connection...</h2>";
    
    try {
        $client = new \Sightengine\SightengineClient($apiUser, $apiSecret);
        
        // Test with a sample image URL
        $testImageUrl = 'https://sightengine.com/assets/img/examples/example-prop-c1.jpg';
        
        echo "<p>Testing with sample image: <a href='$testImageUrl' target='_blank'>$testImageUrl</a></p>";
        
        $output = $client->check(['genai'])->set_url($testImageUrl);
        
        echo "<h3>API Response:</h3>";
        echo "<pre>" . json_encode($output, JSON_PRETTY_PRINT) . "</pre>";
        
        if (isset($output->status) && $output->status === 'success') {
            echo "<p style='color: green; font-weight: bold;'>✅ SIGHTENGINE IS WORKING!</p>";
            
            $aiProb = $output->type->ai_generated ?? 0;
            echo "<p><strong>AI Probability:</strong> " . round($aiProb * 100, 2) . "%</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ API call failed</p>";
        }
        
    } catch (\Exception $e) {
        echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "<hr>";
    echo "<p style='color: orange;'><strong>⚠️ Sightengine not fully configured</strong></p>";
    echo "<p>Please check your .env file</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Back to Home</a></p>";
