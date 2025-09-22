<?php
// public_html/submit.php (root proxy)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed.';
    exit;
}

// 1) Try to include the real handler if it exists at the expected path
$targetFsPath = __DIR__ . '/feedback/submit.php';
if (is_file($targetFsPath)) {
    // Internal include keeps the URL stable and avoids extra hops
    require $targetFsPath;
    exit;
}

// 2) Fallback: 307 redirect the POST to the /feedback handler (preserves method/body)
$targetUrl = '/feedback/submit.php';
header('Location: ' . $targetUrl, true, 307);
exit;


