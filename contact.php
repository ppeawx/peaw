<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ฟังก์ชันทำความสะอาดข้อมูล
    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // รับข้อมูลจากฟอร์มและทำความสะอาด
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $message = sanitizeInput($_POST['message']);

    // ตรวจสอบข้อมูล
    $errors = [];
    if (empty($name)) $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($message)) $errors[] = "Message is required.";

    if (empty($errors)) {
        // ตั้งค่าอีเมล
        $to = "aukkaporn.ju@gmail.com";
        $subject = "New Message from $name";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8\r\n";

        // ส่งอีเมล
        if (mail($to, $subject, $body, $headers)) {
            $status = "success";
            $msg = "Message sent successfully!";
        } else {
            $status = "error";
            $msg = "There was a problem sending your message.";
        }
    } else {
        $status = "error";
        $msg = implode("<br>", $errors);
    }

    // Redirect กลับไปที่ index.html พร้อม query parameters
    header("Location: index.html#contact?status=$status&message=" . urlencode($msg));
    exit();
}
?>