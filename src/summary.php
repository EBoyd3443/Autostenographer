<?php
// Azure GPT-4o used to extract summary of notes
require_once 'azure.php';
$requestBody = json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($requestBody['notes'])) {
    $notes = $requestBody['notes'];
    $messages = [
        [
            "role" => "system",
            "content" => "You are an AI assistant that creates a summary of a group of notes. If there is an overarching theme, use that as a title before summarizing each note. Break up any notes that contain multiple topics. Summarize any exceptionally long notes. Disregard each 'Note' line in the summary, they exist to start each note and track the date/time they were made."
        ],
        [
            "role" => "user",
            "content" => $notes
        ]
    ];

    $auth = "api-key: ".$AZURE_KEY;
    $headers = [
        $auth,
        "Content-Type: application/json",
        "Expect: 100-continue",
        "Accept: application/json"
    ];

    $postFields = [
        'messages' => $messages,
        'model' => 'gpt-4o',
        'max_tokens'=> 4096,
        'temperature'=> 1,
        'top_p'=> 1
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $AZURE_URL2,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postFields),
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $result = curl_exec($ch);
    //echo curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        $response = json_decode($result, true);
        echo json_encode(['transcript' => $response['choices'][0]['message']['content'] ?? 'No content'
]);
    }

    curl_close($ch);
} else {
    echo json_encode(['error' => 'Request with no body.']);
}
?>