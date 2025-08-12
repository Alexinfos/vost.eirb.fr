<?php
require_once __DIR__ . '/../../include/config.php';

if (!$session->isLoggedIn()) {
    header('Location: /protect/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

if (!$session->isAdmin()) {
    http_response_code(403);
    exit();
}

$acceptedFormats = ["image/png", "image/jpeg", "image/webp"];

if (!isset($_GET['id'])
    || !isset($_FILES['thumbnail'])
    || !is_uploaded_file($_FILES['thumbnail']['tmp_name'])
    || !in_array($_FILES['thumbnail']['type'], $acceptedFormats)
    )
{
    http_response_code(422);
    exit();
}

$tmpLocation = $_FILES['thumbnail']['tmp_name'];
$type = $_FILES['thumbnail']['type'];

$srcImg = null;
switch ($type) {
    case 'image/png':
        $srcImg = imagecreatefrompng($tmpLocation);
        break;

    case 'image/jpeg':
        $srcImg = imagecreatefromjpeg($tmpLocation);
        break;

    case 'image/webp':
        $srcImg = imagecreatefromwebp($tmpLocation);
        break;
    
    default:
        throw new UnexpectedValueException("Error processing image: invalid MIME type", 1);
        break;
}

// Reorient image
$orientation = exif_read_data($tmpLocation)['Orientation'];
if (empty($orientation)) {
    $orientation = 0;
}

switch($orientation) {
    case 3:
        $srcImg = imagerotate($srcImg, 180, 0);
        break;
    case 6:
        $srcImg = imagerotate($srcImg, -90, 0);
        break;
    case 8:
        $srcImg = imagerotate($srcImg, 90, 0);
        break;
}

// Check dimensions
$srcWidth = imagesx($srcImg);
$srcHeight = imagesy($srcImg);

$destWidth = 720;
$destHeight = 405;

if ($srcWidth < $destWidth || $srcHeight < $destHeight) {
    echo "Dimensions invalides. L'image doit faire au-moins " . $destWidth . " par " . $destHeight . " pixels.";
    exit(1);
}

// Resize image
$wRatio = $srcWidth / $destWidth;
$hRatio = $srcHeight / $destHeight;

$srcCropWidth = $srcWidth;
$srcCropHeight = $srcHeight;
$srcX = 0;
$srcY = 0;

if ($wRatio > $hRatio) {
    // Keep original height, crop width
    $srcCropWidth = $destWidth * $hRatio;
    $srcX = ($srcWidth - $srcCropWidth) / 2;
} else if ($wRatio < $hRatio) {
    // Keep original width, crop height
    $srcCropHeight = $destHeight * $wRatio;
    $srcY = ($srcHeight - $srcCropHeight) / 2;
}

$destImg = imagecreatetruecolor($destWidth, $destHeight);
imagecopyresampled($destImg, $srcImg, 0, 0, $srcX, $srcY, $destWidth, $destHeight, $srcCropWidth, $srcCropHeight);

// Save image to disk at 70% quality
$destPath = "/assets/thumbs/" . (int)$_GET['id'] . ".webp";
$success = imagewebp($destImg, __DIR__ . "/../.." . $destPath, 70);

if (!$success) {
    http_response_code(500);
    exit();
}

// Add thumbnail to database
$req = $database->prepare('UPDATE `videos` SET `thumbnail` = ? WHERE `id` = ?;');
$req->execute([$destPath, (int)$_GET['id']]);


header('Location: /adm/video.php?id=' . (int)$_GET['id']);
?>