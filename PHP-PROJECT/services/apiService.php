<?php
//require_once 'config/constants.php';
//require_once __DIR__ . '/config/constants.php'; // ✅ CORRECT

require_once __DIR__ . '/../config/constants.php';

function getApiToken() {

    if (!empty($_SESSION['api_token'])) return $_SESSION['api_token'];
    
    $url = API_BASE_URL . TOKEN_ENDPOINT;
    $data = [
        'grant_type' => GRANT_TYPE, 
        'username' => API_USERNAME,
        'password' => API_PASSWORD,
        'scope' => SCOPE
    ];
    $data = http_build_query($data);
    $ch = curl_init($url);
 
    curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded',
        'Content-Length: ' . strlen($data),
    ],
    // Disable SSL verification — only for debugging
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_VERBOSE => false,
    CURLOPT_STDERR => fopen('php://output', 'w')
    ]);

    $response = @curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
   
    if (!empty($result['access_token'])) {
        $_SESSION['api_token'] = $result['access_token'];
        return $result['access_token'];
    }
    if (!empty($result['access_token'])) {
        $_SESSION['access_token'] = $result['access_token'];

        // Store expiry time (example: token valid for 1 hour)
        $expires_in = !empty($result['expires_in']) ? $result['expires_in'] : 3600;
        $_SESSION['token_expires'] = time() + $expires_in - 60; // buffer 60 sec

        return $result['access_token'];
    }

    return false;
}

function apiRequest($endpoint, $method = 'GET', $data = []) {
    $token = getApiToken(); // Generate or get saved token

    if (!$token) {
        return ['success' => false, 'message' => 'Token not available'];
    }

    $url = API_BASE_URL . $endpoint;

    $headers = [
        'Authorization: Bearer ' . $token
    ];

    // Determine Content-Type based on method and data
    if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
        $headers[] = 'Content-Type: application/json';
        $payload = json_encode($data);
    } else {
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $payload = http_build_query($data);
    }

    $ch = curl_init();

    // For GET with data, force body send (if required by API)
    if ($method === 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
    } else {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Disable SSL checks (ONLY for shared hosting like GoDaddy, not production!)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = @curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['success' => false, 'message' => $error];
    }
    $temp_var = json_decode($response, true);
    
    if(isset($temp_var['Message']) && $temp_var['Message'] == "Authorization has been denied for this request."){
        header('location: ' . ROOT_URL.'logout.php');
    }
    return json_decode($response, true);
}


function downloadPdfViaGet($endpoint, $queryParams = []) {
    $token = getApiToken();
    if (!$token) {
        return ['success' => false, 'message' => 'Token not available'];
    }

    $url = API_BASE_URL . $endpoint . '?' . http_build_query($queryParams);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($queryParams),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/pdf'
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error || $http_code !== 200) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $error ?: 'HTTP status ' . $http_code]);
        exit;
    }

    // Serve PDF with download headers
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="receipt.pdf"');
    header('Content-Length: ' . strlen($response));
    return $response;
}


