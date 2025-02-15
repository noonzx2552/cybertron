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
if ($row["chapter_6_status"] !== "completed") {
    $new_status = "in_progress";

    $stmt = $conn->prepare("UPDATE users SET chapter_6_status = ? WHERE username = ?");
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
    <title>Cybersecurity & Threat Prevention</title>
    <link rel="stylesheet" href="../chapter/css/style.css"> <!-- Link to the CSS file -->
</head>
<body>
    <!-- Header Navigation -->
    <header class="navbar">
        <div class="nav-left">
            <span><?php echo htmlspecialchars($username); ?></span>
        </div>
        <nav class="nav-right">
            <a href="#">Home</a>
            <a href="#">Setting</a>
            <a href="#">Logout</a>
        </nav>
    </header>

    <!-- Content Section -->
    <section class="content-section">
        <h1>Cybersecurity & Threat Prevention</h1>

        <div class="content-text">
            <p><strong>1. ความสำคัญของความปลอดภัยทางไซเบอร์</strong><br>
            การรักษาความปลอดภัยไซเบอร์เป็นเรื่องสำคัญสำหรับทุกคน ไม่ว่าจะเป็นบุคคลทั่วไป องค์กร หรือหน่วยงานรัฐ เพราะการโจมตีทางไซเบอร์สามารถสร้างความเสียหายร้ายแรงได้</p>

            <p><strong>2. แนวทางป้องกันภัยคุกคามทางไซเบอร์</strong></p>
            <ul>
                <li>อัปเดตซอฟต์แวร์และระบบปฏิบัติการอยู่เสมอ เพื่อลดช่องโหว่</li>
                <li>ใช้ Firewall และ Antivirus เพื่อป้องกันภัยคุกคาม</li>
                <li>สำรองข้อมูลสำคัญเป็นประจำ ทั้งแบบออนไลน์และออฟไลน์</li>
            </ul>

            <p><strong>3. วิธีรับมือเมื่อถูกโจมตีทางไซเบอร์</strong></p>
            <ul>
                <li>เปลี่ยนรหัสผ่านทันที หากคุณถูกโจมตี</li>
                <li>ปิดการเข้าถึงระบบที่ได้รับผลกระทบเพื่อลดความเสียหาย</li>
                <li>แจ้งหน่วยงานที่เกี่ยวข้อง เช่น CERT หรือฝ่าย IT ขององค์กร</li>
            </ul>
        </div>
    </section>

    <!-- Footer Buttons -->
    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='../home.php'">back</button>
        <button class="next-button" onclick="window.location.href='pg14.html'">next</button>
    </footer>
</body>
</html>