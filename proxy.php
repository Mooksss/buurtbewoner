<?php
// Optionele proxy: alleen nodig als de browser PDOK niet rechtstreeks mag aanroepen (CORS-fout).
// Gebruik: zet in index.html  const PROXY = "proxy.php?url=";
$url = $_GET['url'] ?? '';
$host = parse_url($url, PHP_URL_HOST);
if (parse_url($url, PHP_URL_SCHEME) !== 'https' || $host !== 'api.pdok.nl') {
  http_response_code(400);
  exit('Alleen api.pdok.nl is toegestaan');
}
$ch = curl_init($url);
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15, CURLOPT_FOLLOWLOCATION => false]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: 502;
curl_close($ch);
http_response_code($code);
header('Content-Type: application/json');
echo $body;
