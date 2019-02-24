<?php
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "mail.globaltech.td";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "admin@globaltech.td";
$mail->Password = "globaltech2019+-*";
$mail->SetFrom("admin@globaltech.td");
$mail->Subject = "Test";
$mail->Body = "hello";
$mail->AddAddress("rachid@dctchad.com");

 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }