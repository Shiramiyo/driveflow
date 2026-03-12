<?php

$publicPath = __DIR__.'/public';
$publicRoot = realpath($publicPath);
$requestPath = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$candidatePath = $requestPath === '/' ? false : realpath($publicPath.$requestPath);

// Let Vercel serve generated assets and public files directly through the function.
if (
    $candidatePath !== false &&
    $publicRoot !== false &&
    str_starts_with($candidatePath, $publicRoot) &&
    is_file($candidatePath)
) {
    $mimeType = mime_content_type($candidatePath) ?: 'application/octet-stream';

    header("Content-Type: {$mimeType}");
    readfile($candidatePath);

    return;
}

require __DIR__.'/public/index.php';
