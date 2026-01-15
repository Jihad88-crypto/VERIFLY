<?php
/**
 * MANUAL EXIF TEST SCRIPT
 * Test apakah PHP bisa baca EXIF dari foto
 */

echo "<h1>EXIF Test Script</h1>";
echo "<hr>";

// Check if EXIF extension loaded
echo "<h2>1. Check EXIF Extension</h2>";
if (extension_loaded('exif')) {
    echo "✅ <strong>EXIF extension is LOADED</strong><br>";
} else {
    echo "❌ <strong>EXIF extension is NOT LOADED</strong><br>";
    echo "Please enable EXIF in php.ini<br>";
    exit;
}

echo "<hr>";

// Upload form
if (!isset($_FILES['image'])) {
    ?>
    <h2>2. Upload Test Image</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Test EXIF</button>
    </form>
    <?php
    exit;
}

// Process uploaded file
echo "<h2>2. File Upload Info</h2>";
$file = $_FILES['image'];
echo "File Name: <strong>{$file['name']}</strong><br>";
echo "File Size: <strong>" . number_format($file['size']) . " bytes</strong><br>";
echo "File Type: <strong>{$file['type']}</strong><br>";
echo "Temp Path: <strong>{$file['tmp_name']}</strong><br>";

echo "<hr>";

// Try to read EXIF
echo "<h2>3. EXIF Data Extraction</h2>";

$exifData = @exif_read_data($file['tmp_name']);

if ($exifData === false) {
    echo "❌ <strong>FAILED to read EXIF data</strong><br>";
    echo "Possible reasons:<br>";
    echo "- Image has no EXIF data<br>";
    echo "- Image format not supported<br>";
    echo "- File corrupted<br>";
} else {
    echo "✅ <strong>SUCCESS! EXIF data found</strong><br><br>";
    
    echo "<h3>Important Fields:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    
    $importantFields = ['Make', 'Model', 'DateTime', 'DateTimeOriginal', 'Software', 'FileSize'];
    
    foreach ($importantFields as $field) {
        $value = isset($exifData[$field]) ? $exifData[$field] : '<em>NOT SET</em>';
        echo "<tr><td><strong>$field</strong></td><td>$value</td></tr>";
    }
    
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>All EXIF Data (Raw):</h3>";
    echo "<pre style='background: #f5f5f5; padding: 15px; overflow: auto; max-height: 400px;'>";
    print_r($exifData);
    echo "</pre>";
}

echo "<hr>";
echo "<a href='test-exif-manual.php'>← Test Another Image</a>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        background: #f9f9f9;
    }
    h1 {
        color: #333;
    }
    h2 {
        color: #666;
        margin-top: 20px;
    }
    input[type="file"] {
        padding: 10px;
        margin: 10px 0;
    }
    button {
        background: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    button:hover {
        background: #45a049;
    }
</style>
