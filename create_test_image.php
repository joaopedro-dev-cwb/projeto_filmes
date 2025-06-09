<?php
// Create a 100x100 image
$image = imagecreatetruecolor(100, 100);

// Set background to blue
$blue = imagecolorallocate($image, 0, 0, 255);
imagefill($image, 0, 0, $blue);

// Add some text
$white = imagecolorallocate($image, 255, 255, 255);
imagestring($image, 5, 10, 40, 'Test Image', $white);

// Save as PNG
imagepng($image, __DIR__ . '/test_image.png');
imagedestroy($image);
