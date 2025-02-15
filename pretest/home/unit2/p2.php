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
if ($row["chapter_2_status"] !== "completed") {
    $new_status = "in_progress";

    $stmt = $conn->prepare("UPDATE users SET chapter_2_status = ? WHERE username = ?");
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
    <title>ประเภทของ Phishing Attacks</title>
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
        <h1>ประเภทของ Phishing Attacks</h1>

        <div class="content-text">
            <ul>
                <li><strong>Email Phishing:</strong> เป็นวิธีที่พบได้บ่อยที่สุด โดยผู้ไม่หวังดีจะส่งอีเมลที่ดูเหมือนมาจากองค์กรที่เชื่อถือได้ เช่น ธนาคาร หรือบริษัทเทคโนโลยี เพื่อหลอกให้เหยื่อกรอกข้อมูลหรือดาวน์โหลดมัลแวร์</li>
                <li><strong>Spear Phishing:</strong> เป็นการโจมตีที่เจาะจงเป้าหมาย โดยผู้ไม่หวังดีจะศึกษาข้อมูลของเหยื่อก่อนส่งข้อความที่ถูกปรับแต่งเพื่อให้เหยื่อหลงเชื่อและถูกหลอกลวง</li>
                <li><strong>Smishing (SMS Phishing):</strong> การส่งข้อความ SMS หลอกลวง เช่น แจ้งว่าผู้ใช้ได้รับรางวัล และขอให้คลิกลิงก์เพื่อรับของรางวัล</li>
                <li><strong>Vishing (Voice Phishing):</strong> การใช้โทรศัพท์ปลอมตัวเป็นบุคคลสำคัญ เช่น เจ้าหน้าที่ธนาคาร เพื่อขอข้อมูลทางการเงินของเหยื่อ</li>
                <li><strong>Website Spoofing:</strong> การสร้างเว็บไซต์ปลอมที่มีหน้าตาคล้ายเว็บไซต์จริง เช่น เว็บธนาคาร เพื่อหลอกให้เหยื่อกรอกข้อมูลเข้าสู่ระบบ</li>
            </ul>
        </div>
    </section>

    <!-- Footer Buttons -->
    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='p1.php'">back</button>
        <button class="next-button" onclick="window.location.href='p3.php'">next</button>
    </footer>
</body>
</html>