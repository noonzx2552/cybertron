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

// ตรวจสอบสถานะ chapter_3_status ถ้ายังไม่เป็น "completed" ให้เปลี่ยนเป็น "in_progress"
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

    <!-- Content Sections -->
    <section id="content1" class="content-section active">
        <h1>3|Passwords & Authentication</h1>
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

    <section id="content2" class="content-section">
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
        <button class="back-button" onclick="handleBackButton()">back</button>
        <button class="next-button" onclick="handleNextButton()">next</button>
    </footer>

    <audio id="bgm" loop autoplay muted>
        <source src="../../../assets/sound/bgm.mp3" type="audio/mpeg">
    </audio>
    <script src="../dashboard/script.js"></script>
    <script>
        let currentContent = 1; // เริ่มต้นที่ส่วนแรก

        function showContent(contentId) {
            // ซ่อนเนื้อหาทั้งหมด
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // แสดงเนื้อหาที่ต้องการ
            document.getElementById(`content${contentId}`).classList.add('active');
            currentContent = contentId; // อัปเดตส่วนที่แสดงอยู่
        }

        function handleBackButton() {
            if (currentContent === 1) {
                window.location.href = '../home.php'; // ไปหน้า ../home.php
            } else if (currentContent > 1) {
                showContent(currentContent - 1); // กลับไปส่วนก่อนหน้า
            }
        }

        function handleNextButton() {
            if (currentContent < 2) {
                showContent(currentContent + 1); // ไปยังส่วนถัดไป
            } else if (currentContent === 2) {
                window.location.href = 'lab_unit3.php'; // ไปหน้า lab_unit3.php
            }
        }
    </script>
</body>
</html>