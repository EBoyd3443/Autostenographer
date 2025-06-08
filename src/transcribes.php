<?php
// Azure Speech-to-Text PHP Backend

require_once 'azure.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filePath = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];
    $fileName = $_FILES['file']['name'];

    $auth = "api-key: ".$AZURE_KEY;
    $headers = [
        $auth,
        "Content-Type: multipart/form-data",
        "Expect: 100-continue",
        "Accept: application/json"
    ];

    $postFields = [
        'file' => new CURLFile($filePath, $fileType, $fileName),
        'model' => 'gpt-4o-transcribe',
        'language' => 'en',
        'dimension' => 'simple'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $AZURE_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $result = curl_exec($ch);
    //echo curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        $response = json_decode($result, true);
        echo json_encode(['transcript' => $response['text'] ?? $response]);
    }

    curl_close($ch);
} else {
    echo json_encode(['error' => 'No audio file received']);
}
?>