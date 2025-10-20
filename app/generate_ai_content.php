<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    require_once __DIR__ . '/../config/api_keys.php';

    $topic = $_POST['topic'];
    $content_type_raw = $_POST['content_type'];

    // Create a more descriptive prompt
    $prompt_map = [
        'social_caption' => "Generate a captivating social media caption about: ",
        'email_subject' => "Write a compelling email subject line for a newsletter about: ",
        'ad_copy' => "Create persuasive ad copy for a product related to: "
    ];
    $prompt = ($prompt_map[$content_type_raw] ?? "Write something about: ") . $topic;

    // Gemini API endpoint
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . AI_API_KEY;

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $_SESSION['ai_content'] = $result['candidates'][0]['content']['parts'][0]['text'];
    } else {
        $_SESSION['ai_content'] = "Sorry, the AI content generation failed. Please check your API key and try again.";
    }
}

redirect('../public/ai_content.php');
