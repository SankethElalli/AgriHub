<?php
header("Content-Type: application/json");

require '../vendor/autoload.php';
include('fsession.php');

use GeminiAPI\Client;

if (!isset($_SESSION['farmer_login_user'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$rawInput = file_get_contents("php://input");
$requestData = json_decode($rawInput, true);

if (!isset($requestData['messages']) || empty($requestData['messages'])) {
    echo json_encode(['error' => 'No messages provided.']);
    exit;
}

$apiKey = 'AIzaSyCkH3b86BjO8O2hwFPo9sCA28w0zE-iOKw';
$client = new Client($apiKey);

$postData = [
    'model' => 'gemini-1.5-flash',
    'messages' => $requestData['messages']
];

try {
    $response = $client->generateMessages($postData);
    if (isset($response['choices'][0]['message']['content'])) {
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Invalid API response structure.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch response from Gemini API.']);
}
?>
