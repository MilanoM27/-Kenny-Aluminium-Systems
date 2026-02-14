<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Honeypot check (spam trap)
if (!empty($_POST['honeypot'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Spam detected']);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// Basic validation
if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields correctly']);
    exit;
}

// Email settings
$to      = 'kennyaluminiumsystems@gmail.com';
$subject = 'New Contact Form Submission from Website';
$body    = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
$headers = "From: no-reply@kennyaluminiumsystems.co.za\r\n" .
           "Reply-To: $email\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent. We will reply soon.']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to send message. Please try again later or contact us directly.']);
}
?>