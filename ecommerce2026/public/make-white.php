<?php
$sourcePath = 'C:/xampp/htdocs/ecommerce2026/public/images/logo.png';
$destPath = 'C:/xampp/htdocs/ecommerce2026/public/images/logo-white.png';

$source = imagecreatefrompng($sourcePath);
if (!$source) {
    die("Failed to load image");
}

imagealphablending($source, false);
imagesavealpha($source, true);

$width = imagesx($source);
$height = imagesy($source);

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $color = imagecolorat($source, $x, $y);
        $alpha = ($color >> 24) & 0x7F;
        
        if ($alpha < 127) {
            $white = imagecolorallocatealpha($source, 255, 255, 255, $alpha);
            imagesetpixel($source, $x, $y, $white);
        }
    }
}

imagepng($source, $destPath);
imagedestroy($source);
echo "Image processed successfully.\n";
