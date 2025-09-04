<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name    = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email   = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone   = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($message)) {
        die("Please fill all required fields.");
    }

    $mail = new PHPMailer(true);

    try {
        // Disable debug output in production
        $mail->SMTPDebug  = 0; 
        $mail->Debugoutput = ''; 

        // SMTP configuration (Zoho)
        $mail->isSMTP();
        $mail->Host       = 'smtp.zoho.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@excellent-vision.com'; 
        $mail->Password   = 'Umdk1eHM5Ds6'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Email headers
        $mail->setFrom('info@excellent-vision.com', 'Website Contact Form');
        $mail->addAddress('info@excellent-vision.com', 'Site Admin');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "
            <h3>Contact Form Details</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        ";
        $mail->AltBody = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nMessage:\n{$message}";

        $mail->send();

        // Redirect back to form page
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: " . $redirectUrl . "?status=success");
        exit;

    } catch (Exception $e) {
        // Show error without breaking headers
        echo "Mailer Error: " . htmlspecialchars($mail->ErrorInfo);
    }
} else {
    echo "Invalid request.";
}
