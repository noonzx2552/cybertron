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
        <h1>Passwords & Authentication</h1>

        <div class="content-text">
            <p><strong>1. ความสำคัญของรหัสผ่าน</strong><br>
            รหัสผ่านเป็นเครื่องมือสำคัญในการป้องกันข้อมูลส่วนตัวและบัญชีออนไลน์ หากใช้รหัสผ่านที่อ่อนแอหรือซ้ำกัน อาจทำให้บัญชีถูกแฮกได้ง่าย</p>
            <ul>
                <li>ควรใช้รหัสผ่านที่ยาวและซับซ้อน</li>
                <li>หลีกเลี่ยงการใช้รหัสผ่านซ้ำกันในหลายบัญชี</li>
                <li>ไม่ควรใช้ข้อมูลส่วนตัว เช่น วันเกิด หรือชื่อ เป็นรหัสผ่าน</li>
            </ul>

            <p><strong>2. เทคนิคการสร้างรหัสผ่านที่ปลอดภัย</strong></p>
            <ul>
                <li>ใช้อักษรผสม (ตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก ตัวเลข และสัญลักษณ์)</li>
                <li>ใช้ Passphrase แทน Password เช่น "MyDog@Home2024!"</li>
                <li>ใช้โปรแกรมจัดการรหัสผ่าน (Password Manager) เพื่อสร้างและจัดเก็บรหัสผ่านที่ปลอดภัย</li>
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