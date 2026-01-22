<?php
// 1. Receive Base64 input (example from POST)
print_r($_GET);die;
$base64Pdf = $_POST['pdf_base64'] ?? '';
 
if (!$base64Pdf) {
    die("No base64 PDF input provided.");
}
 
// 2. Clean the base64 string (strip the data URI if present)
$base64Pdf = preg_replace('/^data:application\/pdf;base64,/', '', $base64Pdf);
$pdfData = base64_decode($base64Pdf);
 
// 3. Save to temporary file
$tempPdfPath = sys_get_temp_dir() . '/input_' . uniqid() . '.pdf';
file_put_contents($tempPdfPath, $pdfData);
 
// 4. Convert to image using Imagick
try {
    $imagick = new Imagick();
    $imagick->setResolution(150, 150); // DPI
    $imagick->readImage($tempPdfPath . '[0]'); // Only first page
    $imagick->setImageFormat('jpg');
 
    // Optional: Resize image
    $imagick->resizeImage(800, 0, Imagick::FILTER_LANCZOS, 1);
 
    // Output the image (base64)
    header("Content-Type: image/jpeg");
    echo $imagick;
    // OR save to file:
    // $imagick->writeImage('/path/to/output.jpg');
 
    $imagick->clear();
    $imagick->destroy();
 
    // Cleanup
    unlink($tempPdfPath);
 
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>