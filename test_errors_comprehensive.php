<?php
// Test script for comprehensive error handling
require_once __DIR__ . '/public/index.php';

class TestResult {
    public static function printResult($test, $result) {
        echo "\nTesting: {$test['name']}\n";
        echo "URL: {$test['url']}\n";
        echo "Method: {$test['method']}\n";
        echo "Expected: {$test['expected']}, Got: {$result['code']}\n";
        
        if (isset($test['expectedMessage'])) {
            echo "Expected message: {$test['expectedMessage']}\n";
            echo "Got message: " . substr($result['response'], 0, 200) . "\n";
            $passed = $result['code'] === $test['expected'] && 
                     strpos($result['response'], $test['expectedMessage']) !== false;
        } else {
            $passed = $result['code'] === $test['expected'];
        }
        
        echo ($passed ? "✓ PASS" : "✗ FAIL") . "\n";
        if (!$passed) {
            echo "Response: " . substr($result['response'], 0, 200) . "...\n";
        }
        echo "----------------------------------------\n";
    }
}

function testEndpoint($url, $method = 'GET', $data = [], $files = [], $cookies = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    // Set cookies if provided
    if (!empty($cookies)) {
        $cookieStr = '';
        foreach ($cookies as $key => $value) {
            $cookieStr .= $key . '=' . $value . '; ';
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookieStr);
    }
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        
        if ($files) {
            $postData = $data;
            foreach ($files as $key => $file) {
                $postData[$key] = new CURLFile($file);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response
    ];
}

// First, let's create a test user and log in
$baseUrl = 'http://localhost/projeto_filmes/public/';
$testUser = [
    'email' => 'test@example.com',
    'password' => 'Test123!',
    'name' => 'Test User',
    'cpf' => '12345678901',
    'dtaNasc' => '1990-01-01'
];

// Register test user
$registerResult = testEndpoint(
    $baseUrl . '?action=register',
    'POST',
    $testUser
);

// Log in to get session
$loginResult = testEndpoint(
    $baseUrl . '?action=login',
    'POST',
    [
        'email' => $testUser['email'],
        'password' => $testUser['password']
    ]
);

// Extract PHPSESSID from response headers
preg_match('/PHPSESSID=([^;]+)/', $loginResult['response'], $matches);
$sessionId = $matches[1] ?? '';

$sessionCookies = ['PHPSESSID' => $sessionId];

// Test cases
$tests = [
    // 404 - Non-existent route
    [
        'name' => 'Non-existent route',
        'url' => $baseUrl . '?action=nonexistent', 
        'method' => 'GET',
        'expected' => 404,
        'expectedMessage' => 'Page Not Found'
    ],
    
    // 400 - Missing ID for edit
    [
        'name' => 'Missing film ID for edit',
        'url' => $baseUrl . '?action=films/edit',
        'method' => 'GET', 
        'expected' => 400,
        'expectedMessage' => 'ID is required'
    ],
    
    // 400 - Missing genre
    [
        'name' => 'Missing genre parameter',
        'url' => $baseUrl . '?action=films/genre',
        'method' => 'GET',
        'expected' => 400,
        'expectedMessage' => 'Genre is required'
    ],
    
    // 404 - Non-existent film
    [
        'name' => 'Non-existent film ID',
        'url' => $baseUrl . '?action=films&id=99999',
        'method' => 'GET',
        'expected' => 404,
        'expectedMessage' => 'Film not found'
    ],
    
    // 400 - Invalid file upload (wrong type)
    [
        'name' => 'Invalid file type upload',
        'url' => $baseUrl . '?action=films/create',
        'method' => 'POST',
        'data' => [
            'title' => 'Test Movie',
            'director' => 'Test Director',
            'release_year' => '2023',
            'duration' => '120'
        ],
        'files' => [
            'cover_image' => __DIR__ . '/test_upload.txt'
        ],
        'cookies' => $sessionCookies,
        'expected' => 400,
        'expectedMessage' => 'Invalid file type'
    ],
    
    // 400 - Invalid release year
    [
        'name' => 'Invalid release year',
        'url' => $baseUrl . '?action=films/create',
        'method' => 'POST',
        'data' => [
            'title' => 'Test Movie',
            'director' => 'Test Director',
            'release_year' => '1800',
            'duration' => '120'
        ],
        'cookies' => $sessionCookies,
        'expected' => 400,
        'expectedMessage' => 'Release year must be between'
    ],
    
    // 400 - Invalid duration
    [
        'name' => 'Invalid duration',
        'url' => $baseUrl . '?action=films/create',
        'method' => 'POST',
        'data' => [
            'title' => 'Test Movie',
            'director' => 'Test Director',
            'release_year' => '2023',
            'duration' => '0'
        ],
        'cookies' => $sessionCookies,
        'expected' => 400,
        'expectedMessage' => 'Duration must be greater than 0'
    ]
];

// Run tests
foreach ($tests as $test) {
    $result = testEndpoint(
        $test['url'], 
        $test['method'], 
        $test['data'] ?? [], 
        $test['files'] ?? [],
        $test['cookies'] ?? []
    );
    
    TestResult::printResult($test, $result);
}
