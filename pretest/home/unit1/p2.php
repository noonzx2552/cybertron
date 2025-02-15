<?php
session_start();
$percentage = 90; // เปอร์เซ็นต์ความคืบหน้า (เช่น 70%)
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
$sql = "SELECT pretest_score, posttest_score, congratulation,end_date, 
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
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิธีปกป้องอัตลักษณ์ออนไลน์</title>
    <link rel="stylesheet" href="../chapter/css/style.css"> <!-- Link to the CSS file -->
</head>
<body>
    <!-- Header Navigation -->
    <header class="navbar">
        <div class="nav-left">
            <span><?php echo htmlspecialchars($username); ?></span>
        </div>
        <nav class="nav-right">
            <a href="../home.php">Home</a>
            <a href="#">Setting</a>
            <a href="#">Logout</a>
        </nav>
    </header>

    <!-- Content Section -->
    <section class="content-section">
        <h1>วิธีปกป้องอัตลักษณ์ออนไลน์</h1>

        <div class="content-text">
            <p><strong>4. วิธีปกป้องอัตลักษณ์ออนไลน์</strong></p>
            <ul>
                <li>ตรวจสอบการตั้งค่าความเป็นส่วนตัวในโซเชียลมีเดีย</li>
                <li>หลีกเลี่ยงการโพสต์ข้อมูลส่วนตัวที่ละเอียดอ่อน</li>
                <li>ใช้รหัสผ่านที่ปลอดภัยและไม่ซ้ำกันในแต่ละบัญชี</li>
                <li>จำกัดการเข้าถึงโพสต์เก่าๆ ที่อาจมีข้อมูลสำคัญ</li>
                <li>ค้นหาชื่อตัวเองใน Google เพื่อตรวจสอบว่ามีข้อมูลใดหลุดออกไปบ้าง</li>
            </ul>
        </div>
    </section>

    <!-- Footer Buttons -->
    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='p1.php'">back</button>
        <button class="next-button" onclick="window.location.href='lab_unit.php'">next</button>
    </footer>
</body>
</html>