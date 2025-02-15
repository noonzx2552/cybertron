<?php
session_start();
$percentage = 90; // เปอร์เซ็นต์ความคืบหน้า

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงค่า pretest_score และสถานะของแต่ละบท
$sql = "SELECT pretest_score, posttest_score, congratulation, end_date, 
               chapter_1_status, chapter_2_status, chapter_3_status, 
               chapter_4_status, chapter_5_status, chapter_6_status 
        FROM users WHERE username = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// ตรวจสอบสถานะ chapter_1_status ถ้ายังไม่เป็น "completed" ให้เปลี่ยนเป็น "in_progress"
if ($row["chapter_3_status"] !== "completed") {
    $new_status = "in_progress";

    $stmt = $conn->prepare("UPDATE users SET chapter_3_status = ? WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $new_status, $username);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwords & Authentication</title>
    <link rel="stylesheet" href="../chapter/css/style.css"> <!-- Link to the CSS file -->
</head>
<body>
    <!-- Header Navigation -->
    <header class="navbar">
        <div class="nav-left">
            <span>{user}</span>
        </div>
        <nav class="nav-right">
            <a href="../home.php">Home</a>
            <a href="#">Setting</a>
            <a href="../../logout.php">Logout</a>
        </nav>
    </header>

    <!-- Content Section -->
    <section class="content-section">
        <h1>Passwords & Authentication (ต่อ)</h1>

        <div class="content-text">
            <p><strong>3. การพิสูจน์ตัวตนแบบหลายปัจจัย (Multi-Factor Authentication - MFA)</strong><br>
            MFA เป็นการเพิ่มชั้นการรักษาความปลอดภัย เช่น:</p>
            <ul>
                <li>รหัสผ่าน + OTP ที่ส่งไปยังมือถือ</li>
                <li>รหัสผ่าน + การยืนยันตัวตนด้วยโปรแกรม (ลายนิ้วมือหรือใบหน้า)</li>
            </ul>

            <p><strong>4. แนวทางป้องกันการถูกขโมยรหัสผ่าน</strong></p>
            <ul>
                <li>ไม่กรอกรหัสผ่านในเว็บไซต์ที่ไม่น่าเชื่อถือ</li>
                <li>เปิดใช้งาน MFA ทุกครั้งที่ทำได้</li>
                <li>ตรวจสอบกิจกรรมการเข้าสู่ระบบจากอุปกรณ์ที่ไม่รู้จัก</li>
            </ul>
        </div>
    </section>

    <!-- Footer Buttons -->
    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='p1.php'">back</button>
        <button class="next-button" onclick="window.location.href='lab.php'">next</button>
    </footer>
</body>
</html>