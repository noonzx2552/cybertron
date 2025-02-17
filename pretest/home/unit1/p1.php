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
    <link rel="stylesheet" href="../bar/style.css"> <!-- Link to the CSS file -->
    <style>
        .content-section {
            display: none;
        }
        .content-section.active {
            display: block;
        }
    </style>
</head>
<body>
    <span class="username-display"><?php echo htmlspecialchars($username); ?></span>
    
    <!-- Navigation Menu -->
    <div class="nav">
        <input type="checkbox" id="menu-toggle" />
        <svg>
            <use xlink:href="#MENU1" />
            <use xlink:href="#MENU1" />
        </svg>
    </div>

    <!-- ปุ่ม SETTING และ LOGOUT (ซ่อนก่อน) -->
    <div class="menu-buttons">
        <button class="home-btn">HOME</button>
        <button id="setting-btn" class="setting-btn">SETTING</button>
        <button class="logout-btn">LOGOUT</button>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 56" id="MENU1">
            <path d="M48.33,45.6H18a14.17,14.17,0,0,1,0-28.34H78.86a17.37,17.37,0,0,1,0,34.74H42.33l-21-21.26L47.75,4"/>
        </symbol>
    </svg>

    <!-- Settings Modal -->
    <div id="settings-modal" class="settings-modal">
        <div class="settings-content">
            <h2>Settings</h2>
            <label for="bgm-volume">BGM Volume:</label>
            <input type="range" id="bgm-volume" min="0" max="1" step="0.001" value="1">
            <label for="sfx-volume">SFX Volume:</label>
            <input type="range" id="sfx-volume" min="0" max="1" step="0.001" value="1">
            <label for="mute">Mute:</label>
            <input type="checkbox" id="mute">
            <button id="close-settings">Close</button>
        </div>
    </div>

    <!-- Header Navigation -->
    <script defer src="../bar/script.js"></script>
    <script defer src="../setting/script.js"></script>

    <!-- Content Section -->
    <section id="content1" class="content-section active">
        <h1>1|Online Identity & Digital Footprint</h1>

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

    <section id="content2" class="content-section">
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
        <button class="back-button" onclick="handleBackButton()">back</button>
        <button class="next-button" onclick="handleNextButton()">next</button>
    </footer>

    <audio id="bgm" loop autoplay muted>
        <source src="../../../assets/sound/bgm.mp3" type="audio/mpeg">
    </audio>
    <script src="../dashboard/script.js"></script>
    <script>
        let currentContent = 'content1'; // เริ่มต้นที่ส่วนแรก

        function showContent(contentId) {
            // ซ่อนเนื้อหาทั้งหมด
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // แสดงเนื้อหาที่ต้องการ
            document.getElementById(contentId).classList.add('active');
            currentContent = contentId; // อัปเดตส่วนที่แสดงอยู่
        }

        function handleBackButton() {
            if (currentContent === 'content1') {
                window.location.href = '../home.php'; // ไปหน้า home.php
            } else if (currentContent === 'content2') {
                showContent('content1'); // กลับไปส่วนแรก
            }
        }

        function handleNextButton() {
            if (currentContent === 'content1') {
                showContent('content2'); // ไปส่วนที่สอง
            } else if (currentContent === 'content2') {
                window.location.href = 'lab_unit.php'; // ไปหน้า lab_unit.php
            }
        }
    </script>
</body>
</html>