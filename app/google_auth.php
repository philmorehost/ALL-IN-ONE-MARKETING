<?php
require_once __DIR__ . '/../config/api_keys.php';

$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth';

$params = [
    'response_type' => 'code',
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
    'access_type' => 'offline',
    'prompt' => 'consent'
];

$auth_url .= '?' . http_build_query($params);

header('Location: ' . $auth_url);
exit;
