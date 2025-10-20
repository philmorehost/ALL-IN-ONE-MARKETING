<?php
require_once __DIR__ . '/admin_functions.php';
require_once __DIR__ . '/../csrf.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    // Read the existing api_keys.php file to get the redirect URI
    require_once __DIR__ . '/../../config/api_keys.php';

    $new_config = "<?php
// API keys and settings
// In a real application, use environment variables for security.

// Google OAuth
define(\"GOOGLE_CLIENT_ID\", \"{$_POST['GOOGLE_CLIENT_ID']}\");
define(\"GOOGLE_CLIENT_SECRET\", \"{$_POST['GOOGLE_CLIENT_SECRET']}\");
define(\"GOOGLE_REDIRECT_URI\", \"" . GOOGLE_REDIRECT_URI . "\"); // Preserve the existing redirect URI

// Paystack
define(\"PAYSTACK_SECRET_KEY\", \"{$_POST['PAYSTACK_SECRET_KEY']}\");
define(\"PAYSTACK_PUBLIC_KEY\", \"{$_POST['PAYSTACK_PUBLIC_KEY']}\");

// Generative AI
define(\"AI_API_KEY\", \"{$_POST['AI_API_KEY']}\");
";

    file_put_contents(__DIR__ . '/../../config/api_keys.php', $new_config);

    $_SESSION['message'] = 'Settings updated successfully.';
    redirect('../../../public/admin/settings.php');
}
