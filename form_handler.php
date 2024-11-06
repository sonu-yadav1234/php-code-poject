<?php
// Include database connection and PHPMailer
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $message = $_POST['message'];

    // Validate email and mobile
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }
    if (strlen($mobile) != 10 || !is_numeric($mobile)) {
        die('Mobile number should be 10 digits');
    }

    // Handle file upload
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check file type
    if ($file_ext != 'jpeg') {
        die('Only JPEG files are allowed');
    }
    // Check file size (500KB max)
    if ($file['size'] > 500000) {
        die('File size exceeds 500KB');
    }

    // Save the file
    $upload_dir = 'uploads/';
    $file_path = $upload_dir . uniqid() . '.' . $file_ext;
    move_uploaded_file($file_tmp, $file_path);

    // Insert form data into the database
    $stmt = $pdo->prepare("INSERT INTO submissions (name, email, mobile, message, file_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $mobile, $message, $file_path]);

    // Send confirmation email
    sendEmail($email, $file_path);

    echo "Form submitted successfully!";
}

// Function to send email with attachment
function sendEmail($email, $file_path) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_username';  // Mailtrap or other SMTP credentials
        $mail->Password = 'your_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sonu560139@gmail.com', 'Form Submission');
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = 'We have received your form submission';
        $mail->Body    = 'Thank you for your submission. We have received your form, and we are reviewing it.';

        // Attach the uploaded file
        $mail->addAttachment($file_path);

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
