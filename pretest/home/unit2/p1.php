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

// ตรวจสอบสถานะ chapter_2_status ถ้ายังไม่เป็น "completed" ให้เปลี่ยนเป็น "in_progress"
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
    <title>Phishing & Social Engineering</title>
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
        <h1>2|Phishing & Social Engineering</h1>
        <div class="content-text">
            <p><strong>ความหมายของ Phishing & Social Engineering</strong><br>
            Phishing และ Social Engineering เป็นเทคนิคที่ผู้ไม่หวังดีใช้เพื่อหลอกลวงให้เหยื่อเปิดเผยข้อมูลสำคัญ เช่น รหัสผ่าน หมายเลขบัตรเครดิต หรือข้อมูลส่วนตัวอื่นๆ โดยใช้กลวิธีทางจิตวิทยาและการสร้างความน่าเชื่อถือเพื่อให้เหยื่อหลงเชื่อและให้ข้อมูลโดยไม่รู้ตัว</p>
            <ul>
                <li><strong>Phishing:</strong> การส่งข้อความปลอมหรือสร้างเว็บไซต์ปลอมที่ถูกแอบอ้างแหล่งข้อมูลจริง เพื่อหลอกให้เหยื่อกรอกข้อมูลส่วนตัว</li>
                <li><strong>Social Engineering:</strong> เทคนิคการหลอกลวงที่ใช้จิตวิทยา หลอกให้เหยื่อเปิดเผยข้อมูล หรือดำเนินการบางอย่างโดยไม่ได้ตั้งใจ เช่น คลิกลิงก์อันตรายหรือดาวน์โหลดไฟล์ติดมัลแวร์</li>
            </ul>
            <p><strong>ตัวอย่างเหตุการณ์จริง:</strong></p>
            <ul>
                <li>มีผู้ใช้ยืนยันตัวตนผ่านเว็บไซต์ปลอมที่แอบอ้างว่าเป็นธนาคาร โดยอ้างว่าผู้ใช้มีปัญหาและขอให้กรอกข้อมูลเพื่อเข้าสู่ระบบ ซึ่งเว็บดังกล่าวเป็นเว็บปลอมเพื่อขโมยรหัสผ่าน</li>
            </ul>
        </div>
    </section>

    <section id="content2" class="content-section">
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

    <section id="content3" class="content-section">
        <h1>เทคนิคของ Social Engineering</h1>
        <div class="content-text">
            <ul>
                <li><strong>Pretexting:</strong> การสร้างเรื่องราวปลอม เช่น ปลอมเป็นเจ้าหน้าที่ไอทีของบริษัท เพื่อขอให้เหยื่อให้ข้อมูลการเข้าถึงระบบ</li>
                <li><strong>Baiting:</strong> ใช้สิ่งล่อ เช่น แจกฟรีซอฟต์แวร์ แต่แฝงมัลแวร์ไว้ หรือวางแฟลชไดรฟ์ที่มีมัลแวร์ในที่สาธารณะให้เหยื่อเสียบใช้งาน</li>
                <li><strong>Quid Pro Quo:</strong> เสนอสิ่งตอบแทนให้เหยื่อ เช่น ปลอมเป็นฝ่ายสนับสนุนเทคนิค เสนอให้ความช่วยเหลือด้านไอทีเพื่อแลกกับข้อมูลเข้าสู่ระบบ</li>
                <li><strong>Tailgating & Piggybacking:</strong> การแอบตามคนอื่นเข้าไปในพื้นที่ที่ต้องใช้สิทธิ์เข้าถึง เช่น เดินตามพนักงานเข้าอาคารโดยไม่ต้องใช้บัตรผ่าน</li>
            </ul>
        </div>
    </section>

    <section id="content4" class="content-section">
        <h1>วิธีป้องกัน Phishing & Social Engineering</h1>
        <div class="content-text">
            <h2>วิธีป้องกัน</h2>
            <ul>
                <li><strong>ตรวจสอบแหล่งที่มาของอีเมลและลิงก์:</strong> ก่อนคลิกใดๆ ให้ดูที่อยู่อีเมลและ URL ว่าถูกต้องหรือไม่</li>
                <li><strong>หลีกเลี่ยงการให้ข้อมูลส่วนตัวผ่านโทรศัพท์หรืออีเมล:</strong> องค์กรที่น่าเชื่อถือจะไม่ขอข้อมูลส่วนตัวผ่านช่องทางเหล่านี้</li>
                <li><strong>ใช้ Two-Factor Authentication (2FA):</strong> เพื่อเพิ่มความปลอดภัยให้บัญชีออนไลน์ของคุณ</li>
                <li><strong>ฝึกอบรมและสร้างความตระหนัก:</strong> ให้พนักงานและบุคคลทั่วไปทราบถึงความเสี่ยงของ Phishing และ Social Engineering</li>
                <li><strong>ใช้ซอฟต์แวร์รักษาความปลอดภัย:</strong> เช่น Antivirus และ Email Filtering เพื่อลดความเสี่ยง</li>
            </ul>
            <h2>วิธีรับมือหากตกเป็นเหยื่อ</h2>
            <ul>
                <li><strong>เปลี่ยนรหัสผ่านทันที:</strong> หากให้ข้อมูลเข้าสู่ระบบปลอม</li>
                <li><strong>แจ้งเตือนหน่วยงานที่เกี่ยวข้อง:</strong> เช่น ธนาคาร หรือฝ่าย IT ขององค์กร</li>
                <li><strong>ตรวจสอบรายการทางการเงิน:</strong> หากกรอกข้อมูลบัตรเครดิตหรือบัญชีธนาคาร</li>
                <li><strong>สแกนหา Malware ในอุปกรณ์:</strong> เพื่อตรวจสอบว่ามีมัลแวร์ติดตั้งอยู่หรือไม่</li>
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
            if (currentContent < 4) {
                showContent(currentContent + 1); // ไปยังส่วนถัดไป
            } else if (currentContent === 4) {
                window.location.href = 'lab_unit2.php'; // ไปหน้า lab_unit2.php
            }
        }
    </script>
</body>
</html>