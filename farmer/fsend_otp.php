<?php
session_start();
require('../sql.php'); // Includes Login Script

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/AgriHub/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/AgriHub/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/AgriHub/PHPMailer/src/SMTP.php';

$email = $_SESSION['farmer_login_user'];
$res = mysqli_query($conn, "select * from farmerlogin where email='$email'");
$count = mysqli_num_rows($res);
if ($count > 0) {
    $otp = rand(11111, 99999);
    mysqli_query($conn, "update farmerlogin set otp='$otp' where email ='$email'");
    $html = "
    <html>
    <head>
        <style>
            .email-container {
                font-family: 'Segoe UI', Arial, sans-serif;
                line-height: 1.7;
                color: #2c3e50;
                max-width: 600px;
                margin: 0 auto;
                padding: 30px;
                border-radius: 12px;
                background-color: #ffffff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .email-header {
                text-align: center;
                background: linear-gradient(135deg,rgb(26, 121, 66),rgb(69, 211, 128));
                color: white;
                padding: 25px 0;
                border-radius: 8px;
                margin-bottom: 30px;
            }
            .email-header h2 {
                margin: 0;
                font-size: 28px;
                font-weight: 600;
            }
            .email-body {
                padding: 0 20px;
                background: #ffffff;
            }
            .email-body p {
                margin: 15px 0;
            }
            .otp-code {
                text-align: center;
                font-size: 32px;
                font-weight: 600;
                color:rgb(32, 139, 77);
                padding: 20px;
                margin: 25px 0;
                background: #f8f9fa;
                border-radius: 8px;
                letter-spacing: 3px;
            }
            .email-footer {
                text-align: center;
                padding: 20px 0;
                margin-top: 30px;
                font-size: 13px;
                color: #95a5a6;
                border-top: 1px solid #ecf0f1;
            }
            .warning-text {
                font-size: 12px;
                color: #e74c3c;
                margin-top: 15px;
            }
        </style>
    </head>
    <body style='background-color: #f5f6fa; padding: 20px;'>
        <div class='email-container'>
            <div class='email-header'>
                <h2>AgriHub Verification</h2>
            </div>
            <div class='email-body'>
                <p>Dear Farmer,</p>
                <p>Thank you for using AgriHub. Please use the following One-Time Password (OTP) to verify your account:</p>
                <div class='otp-code'>$otp</div>
                <p>This OTP will expire in 5 minutes for security reasons.</p>
                <p class='warning-text'>Please do not share this OTP with anyone for security purposes.</p>
                <p>Best regards,<br>The AgriHub Team</p>
            </div>
            <div class='email-footer'>
                <p>AgriHub. All rights reserved.<br>
                This is an automated message, please do not reply.</p>
            </div>
        </div>
    </body>
    </html>";
    smtp_mailer($email, 'AgriHub', $html);
    echo "yes";
} else {
    echo "not exist";
}

function smtp_mailer($to, $subject, $msg) {
    require_once("../smtp/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "";
    $mail->Password = "";
    $mail->SetFrom("elallisanketh7@gmail.com");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);
    if (!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
}
?>

