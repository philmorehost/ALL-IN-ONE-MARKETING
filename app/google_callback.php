<?php
require_once __DIR__ . '/../config/api_keys.php';
require_once __DIR__ . '/functions.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange authorization code for access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $token_params = [
        'code' => $code,
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        // Get user info
        $userinfo_url = 'https://www.googleapis.com/oauth2/v2/userinfo';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userinfo_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token_data['access_token']]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $user_info_response = curl_exec($ch);
        curl_close($ch);

        $user_info = json_decode($user_info_response, true);

        if (isset($user_info['email'])) {
            $pdo = pdo();

            // Check if user exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$user_info['email']]);
            $user = $stmt->fetch();

            if ($user) {
                // User exists, log them in
                $_SESSION['user_id'] = $user['id'];
            } else {
                // New user, create an account
                $insertStmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                // We store a random password as it's required, but they'll log in via Google
                $random_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
                $insertStmt->execute([$user_info['name'], $user_info['email'], $random_password]);
                $_SESSION['user_id'] = $pdo->lastInsertId();
            }

            redirect('../public/dashboard.php');

        } else {
            $_SESSION['error'] = 'Failed to retrieve user information from Google.';
            redirect('../public/login.php');
        }

    } else {
        $_SESSION['error'] = 'Google authentication failed.';
        redirect('../public/login.php');
    }
} else {
    redirect('../public/login.php');
}
