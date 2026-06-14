<?php
// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data and sanitize
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validate required fields
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Email configuration
        $to = "info@yegnacoffee.com"; // Change to your email address
        $subject = "New Contact Form Submission from $name";
        
        // Email body
        $email_body = "You have received a new message from your website contact form.\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone\n\n";
        $email_body .= "Message:\n$message\n";
        
        // Email headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // For Ethiopian local development, you might need to use a local mail server
        // If you're using XAMPP/WAMP locally, you'll need to configure sendmail
        
        // Try to send email
        if (mail($to, $subject, $email_body, $headers)) {
            echo "success";
        } else {
            // If mail fails, you can save to a file or database
            // For now, let's save to a log file
            $log_file = "contact_log.txt";
            $log_entry = date('Y-m-d H:i:s') . " | $name | $email | $phone | $message\n";
            file_put_contents($log_file, $log_entry, FILE_APPEND);
            echo "success"; // Still return success since we saved it
        }
        
    } else {
        // Return error message
        echo implode(", ", $errors);
    }
    
} else {
    // Not a POST request
    header("Location: ../index.html");
    exit();
}
?>



