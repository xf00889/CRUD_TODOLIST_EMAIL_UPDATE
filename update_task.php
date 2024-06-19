<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader for PHPMailer
require 'send_email.php'; // Include the send_mail.php script
include 'db.php';

$task_id = $_POST['task_id'];
$status = $_POST['status'];

// Using prepared statements to prevent SQL injection
$updateStmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");
$updateStmt->bind_param('si', $status, $task_id);

if ($updateStmt->execute() === TRUE) {
    $success = true;
    $emailSuccess = false;

    if ($status == 'finished') {
        $selectStmt = $conn->prepare("SELECT customer_email FROM tasks WHERE id=?");
        $selectStmt->bind_param('i', $task_id);
        $selectStmt->execute();
        $selectStmt->bind_result($customer_email);
        $selectStmt->fetch();
        $selectStmt->close(); // Close the SELECT statement here

        if ($customer_email) {
            $fromEmail = "hutchiejn@gmail.com";
            $fromName = "JN computer services"; // Replace with your name or company name
            $subject = "Task Completed";

            // HTML email content with styles and an image
            $message = '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                    }
                    .container {
                        width: 80%;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        background-color: #f9f9f9;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .header img {
                        max-width: 100px;
                        margin-bottom: 10px;
                    }
                    .content {
                        text-align: center;
                    }
                    .footer {
                        margin-top: 20px;
                        text-align: center;
                        font-size: 12px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1 style="font-weight: bold; font-size: 30px;">JN Computer Services</h1>
                        <h2>Task Completed</h2>
                    </div>
                    <div class="content">
                        <p>Your task has been marked as completed. Please visit the site for more information!</p>
                        <p>Thank you!</p>
                    </div>
                    <div class="footer">
                        <p>&copy; 2024 JN Computer Services. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
            ';

            $mailResult = sendMail($customer_email, $subject, $message, $fromEmail, $fromName);

            if ($mailResult === true) {
                $emailSuccess = true;
            } else {
                $emailSuccess = false;
            }
        }
    }

    $updateStmt->close(); // Ensure the UPDATE statement is closed properly
    $conn->close();

    if ($success) {
        $queryString = $emailSuccess ? "update=success&email=sent" : "update=success&email=failed";
        header("Location: tasks.php?$queryString");
        exit();
    }
} else {
    echo "Error updating task status: " . $updateStmt->error . "<br>";
}

$conn->close();
?>
