<?php
header('Content-Type: application/json');

if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode([
        'success' => false,
        'message' => 'URL is required.'
    ]);
    exit;
}

$url = trim($_GET['url']);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid URL.'
    ]);
    exit;
}

function getDomain($url) {
    $parts = parse_url($url);
    return $parts['host'] ?? '';
}

function makeAbsoluteUrl($base, $relative) {
    if (empty($relative)) return '';
    if (preg_match('/^https?:\/\//i', $relative)) return $relative;
    if (strpos($relative, '//') === 0) {
        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
        return $scheme . ':' . $relative;
    }

    $baseParts = parse_url($base);
    $scheme = $baseParts['scheme'] ?? 'https';
    $host = $baseParts['host'] ?? '';
    $path = $baseParts['path'] ?? '/';

    if (strpos($relative, '/') === 0) {
        return $scheme . '://' . $host . $relative;
    }

    $dir = rtrim(str_replace(basename($path), '', $path), '/');
    return $scheme . '://' . $host . $dir . '/' . $relative;
}

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 5,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_USERAGENT => 'Mozilla/5.0 Website Scanner',
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HEADER => true
]);

$startTime = microtime(true);
$response = curl_exec($ch);
$loadTime = round((microtime(true) - $startTime) * 1000, 2);

if (curl_errno($ch)) {
    echo json_encode([
        'success' => false,
        'message' => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
$html = substr($response, $headerSize);
curl_close($ch);

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$loaded = $dom->loadHTML($html);
libxml_clear_errors();

if (!$loaded) {
    echo json_encode([
        'success' => false,
        'message' => 'Unable to parse HTML.'
    ]);
    exit;
}

$xpath = new DOMXPath($dom);

$title = '';
$titleNodes = $xpath->query('//title');
if ($titleNodes->length > 0) {
    $title = trim($titleNodes->item(0)->nodeValue);
}

$metaDescription = '';
$metaNodes = $xpath->query('//meta[@name="description"]');
if ($metaNodes->length > 0) {
    $metaDescription = trim($metaNodes->item(0)->getAttribute('content'));
}

$canonical = '';
$canonicalNodes = $xpath->query('//link[@rel="canonical"]');
if ($canonicalNodes->length > 0) {
    $canonical = trim($canonicalNodes->item(0)->getAttribute('href'));
}

$h1Tags = [];
$h1Nodes = $xpath->query('//h1');
foreach ($h1Nodes as $node) {
    $text = trim($node->textContent);
    if ($text !== '') {
        $h1Tags[] = $text;
    }
}

$imagesWithoutAlt = [];
$imageNodes = $xpath->query('//img');
$totalImages = $imageNodes->length;
foreach ($imageNodes as $img) {
    $alt = trim($img->getAttribute('alt'));
    if ($alt === '') {
        $src = $img->getAttribute('src');
        $imagesWithoutAlt[] = makeAbsoluteUrl($finalUrl, $src);
    }
}

$baseDomain = getDomain($finalUrl);
$internalLinksCount = 0;
$externalLinksCount = 0;

$linkNodes = $xpath->query('//a[@href]');
foreach ($linkNodes as $link) {
    $href = trim($link->getAttribute('href'));

    if ($href === '' || strpos($href, 'javascript:') === 0 || strpos($href, '#') === 0 || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0) {
        continue;
    }

    $absolute = makeAbsoluteUrl($finalUrl, $href);
    $linkDomain = getDomain($absolute);

    if ($linkDomain === '' || $linkDomain === $baseDomain) {
        $internalLinksCount++;
    } else {
        $externalLinksCount++;
    }
}

$report = [
    'url' => $finalUrl,
    'http_status' => $httpCode,
    'load_time_ms' => $loadTime,
    'title' => $title,
    'meta_description' => $metaDescription,
    'canonical' => $canonical,
    'h1_count' => count($h1Tags),
    'h1_tags' => $h1Tags,
    'total_images' => $totalImages,
    'images_without_alt_count' => count($imagesWithoutAlt),
    'images_without_alt' => $imagesWithoutAlt,
    'internal_links_count' => $internalLinksCount,
    'external_links_count' => $externalLinksCount,
    'scanned_at' => date('c')
];

echo json_encode([
    'success' => true,
    'report' => $report
], JSON_PRETTY_PRINT);