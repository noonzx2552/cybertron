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
if ($row["chapter_4_status"] !== "completed") {
    $new_status = "in_progress";

    $stmt = $conn->prepare("UPDATE users SET chapter_4_status = ? WHERE username = ?");
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
    <title>Malware & Threats</title>
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
            <a href="../../logout.php">Logout</a>
        </nav>
    </header>

    <!-- Content Section -->
    <section class="content-section">
        <h1>Malware & Threats</h1>

        <div class="content-text">
            <p><strong>1. มัลแวร์คืออะไร?</strong><br>
            มัลแวร์ (Malicious Software) คือซอฟต์แวร์ที่ถูกออกแบบมาเพื่อสร้างความเสียหายหรือขโมยข้อมูลจากอุปกรณ์ของเหยื่อ</p>

            <p><strong>2. ประเภทของมัลแวร์</strong></p>
            <ul>
                <li><strong>ไวรัส (Virus):</strong> แพร่กระจายโดยแนบมากับไฟล์หรือโปรแกรม</li>
                <li><strong>เวิร์ม (Worm):</strong> แพร่กระจายได้เองโดยไม่ต้องมีไฟล์พาหะ</li>
                <li><strong>โทรจัน (Trojan Horse):</strong> แอบตัวเป็นซอฟต์แวร์ที่ดูเหมือนไม่เป็นอันตราย</li>
                <li><strong>แรนซัมแวร์ (Ransomware):</strong> ล็อกไฟล์ของเหยื่อและเรียกค่าไถ่</li>
                <li><strong>สปายแวร์ (Spyware):</strong> ติดตามข้อมูลและพฤติกรรมของเหยื่อ</li>
            </ul>

            <p><strong>3. วิธีป้องกันมัลแวร์</strong></p>
            <ul>
                <li>ติดตั้งและอัปเดตซอฟต์แวร์ป้องกันมัลแวร์เป็นประจำ</li>
                <li>หลีกเลี่ยงการดาวน์โหลดไฟล์จากแหล่งที่ไม่น่าเชื่อถือ</li>
                <li>สแกนระบบเป็นประจำเพื่อลดความเสี่ยงจากแรนซัมแวร์</li>
            </ul>
        </div>
    </section>

    <!-- Footer Buttons -->
    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='../home.php'">back</button>
        <button class="next-button" onclick="window.location.href='lab.php'">next</button>
    </footer>
</body>
</html>