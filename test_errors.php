<?php
// Test script for error handling
require_once __DIR__ . '/public/index.php';

function testEndpoint($url, $method = 'GET', $data = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response
    ];
}

// Test cases
$baseUrl = 'http://localhost/projeto_filmes/public/';
$tests = [
    // 404 - Non-existent route
    ['url' => $baseUrl . '?action=nonexistent', 'expected' => 404],
    
    // 400 - Missing ID for edit
    ['url' => $baseUrl . '?action=films/edit', 'expected' => 400],
    
    // 400 - Missing genre
    ['url' => $baseUrl . '?action=films/genre', 'expected' => 400],
    
    // 404 - Non-existent film
    ['url' => $baseUrl . '?action=films&id=99999', 'expected' => 404],
];

// Run tests
foreach ($tests as $test) {
    $result = testEndpoint($test['url']);
    echo "Testing {$test['url']}\n";
    echo "Expected: {$test['expected']}, Got: {$result['code']}\n";
    echo "----------------------------------------\n";
}
