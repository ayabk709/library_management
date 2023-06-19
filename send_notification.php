<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

include_once 'config/Database.php';
// include_once 'class/IssueBooks.php';

// Load PHPMailer autoload file
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Retrieve notificationDate from POST data
$notificationDate = new DateTime($_POST['notificationDate']);

// Construct the email content
$emailSubject = 'Return Reminder';
$emailBody = 'This is a reminder that your return date is approaching. Please return the item by ' . $notificationDate->format('Y-m-d') . '.';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);
$notificationDate = new DateTime($_POST['notificationDate']);

try {
    // Configure SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aya.bekkach@gmail.com';
    $mail->Password   = 'tmsqqdbskgeiewtt';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Set sender
    $mail->setFrom('aya.bekkach@gmail.com', 'Aya');

    // Fetch recipient emails from the database
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT u.email FROM issuebook i
              JOIN user u ON i.userid = u.id
              WHERE DATEDIFF(i.expected_return_date, CURDATE()) = 2";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $recipientEmail = $row['email'];
            // Set recipient email address
            $mail->addAddress($recipientEmail);

            // Set email content
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body    = $emailBody;

            // Send the email
            $mail->send();

            // Clear the recipients for the next iteration
            $mail->clearAddresses();
        }
    }

    // Return success response
    echo 'Notifications sent successfully.';
} catch (Exception $e) {
    // Return error response
    echo 'Error sending notifications: ' . $mail->ErrorInfo;
}}

?>