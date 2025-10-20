<?php
namespace App\Jobs;

class SendWelcomeEmail {
    public function handle($payload) {
        $user_email = $payload['email'];
        $user_name = $payload['name'];

        $email_body = "Hello {$user_name},\n\nWelcome to the All-in-One Digital Marketing Platform! We're excited to have you on board.\n\nThank you,\nThe All-in-One Digital Team";

        // Log the email to a file instead of sending it
        file_put_contents(__DIR__ . '/../../email.log', "To: {$user_email}\nSubject: Welcome to the Platform!\n\n{$email_body}\n\n---\n\n", FILE_APPEND);
    }
}
