<?php
echo "<h3>Deployment Verification Script</h3>";

$profileResourcePath = __DIR__ . '/../app/Http/Resources/ProfileResource.php';
$salesIndexPath = __DIR__ . '/../resources/views/handcash/sales/index.blade.php';

function checkFile($name, $path, $searchString) {
    echo "<b>Checking $name:</b><br>";
    if (!file_exists($path)) {
        echo "❌ File does not exist at path: $path<br><br>";
        return;
    }
    
    $mtime = filemtime($path);
    echo "Last modified: " . date("Y-m-d H:i:s", $mtime) . " (" . (time() - $mtime) . " seconds ago)<br>";
    echo "MD5 Hash: " . md5_file($path) . "<br>";
    
    $content = file_get_contents($path);
    if (strpos($content, $searchString) !== false) {
        echo "✅ New changes are PRESENT in the file content.<br>";
    } else {
        echo "❌ New changes are NOT present in the file content.<br>";
    }
    echo "<br>";
}

checkFile("ProfileResource.php", $profileResourcePath, "store_administrator");
checkFile("index.blade.php", $salesIndexPath, "flex-wrap: wrap");
