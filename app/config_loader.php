<?php
// This script ensures that a config/api_keys.php file exists.
// If it doesn't, it's created from the template.

$api_keys_file = __DIR__ . '/../config/api_keys.php';
$template_file = __DIR__ . '/../config/api_keys.php.template';

if (!file_exists($api_keys_file) && file_exists($template_file)) {
    copy($template_file, $api_keys_file);
}

// Now, safely include the config file
if (file_exists($api_keys_file)) {
    require_once $api_keys_file;
} else {
    // Handle the case where the config is missing entirely
    // In a real app, you might show an error page
    die('Critical Error: The API keys configuration file is missing and could not be created from the template.');
}
