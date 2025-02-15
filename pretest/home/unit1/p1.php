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
if ($row["chapter_1_status"] !== "completed") {
    $new_status = "in_progress";

    $stmt = $conn->prepare("UPDATE users SET chapter_1_status = ? WHERE username = ?");
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
    <title>Online Identity & Digital Footprint</title>
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
        <h1>Online Identity & Digital Footprint</h1>

        <div class="content-text">
            <p><strong>1. อัตลักษณ์ออนไลน์คืออะไร?</strong><br>
            อัตลักษณ์ออนไลน์ (Online Identity) คือภาพลักษณ์และข้อมูลที่เราทิ้งไว้บนโลกออนไลน์ ไม่ว่าจะเป็นโซเชียลมีเดีย เว็บไซต์ หรือแพลตฟอร์มอื่นๆ</p>

            <p><strong>2. Digital Footprint คืออะไร?</strong><br>
            Digital Footprint คือร่องรอยทางดิจิทัลที่เกิดจากการใช้งานอินเทอร์เน็ตของเรา แบ่งออกเป็น 2 ประเภท:</p>
            <ul>
                <li><strong>Active Digital Footprint:</strong> ข้อมูลที่เราตั้งใจโพสต์หรือแชร์ เช่น โพสต์ในโซเชียลมีเดีย คอมเมนต์ รีวิว</li>
                <li><strong>Passive Digital Footprint:</strong> ข้อมูลที่ถูกเก็บโดยอัตโนมัติ เช่น ประวัติการค้นหา คุกกี้จากเว็บไซต์</li>
            </ul>

            <p><strong>3. ความเสี่ยงจาก Digital Footprint</strong></p>
            <ul>
                <li>ข้อมูลส่วนตัวอาจถูกนำไปใช้ในทางที่ผิด เช่น การโจรกรรมข้อมูล (Identity Theft)</li>
                <li>ภาพลักษณ์บนโลกออนไลน์อาจส่งผลต่อโอกาสในอนาคต เช่น การสมัครงานหรือการศึกษาต่อ</li>
                <li>ข้อมูลที่แชร์โดยไม่ระวังอาจถูกใช้เพื่อหลอกลวง หรือก่อให้เกิดภัยคุกคาม</li>
            </ul>
        </div>
    </section>

    <!-- Footer Buttons -->
    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='../home.php'">back</button>
        <button class="next-button" onclick="window.location.href='p2.php'">next</button>
    </footer>
</body>
</html>
