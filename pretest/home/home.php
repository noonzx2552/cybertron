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


// เช็คว่า pretest_score เป็น NULL หรือไม่
$pretest_score = $row['pretest_score'] ?? null; 
$posttest_score = $row['posttest_score'] ?? null; 

$pretest_completed = !is_null($pretest_score);
$posttest_completed = !is_null($posttest_score);

// ฟังก์ชันกำหนดสีของบทเรียน
function getStatusColor($status) {
    if ($status === 'completed') return '#28a745'; // เขียว
    if ($status === 'in_progress') return '#ffc107'; // เหลือง
    return 'white'; // ขาว (not_started)
}

// ตรวจสอบว่า pretest_score และ posttest_score ไม่เป็น NULL
$score_completed = !is_null($pretest_score) && !is_null($posttest_score);

// ตรวจสอบว่า chapter_1-6_status เป็น "completed" ทั้งหมด
$status_completed = (
    $row["chapter_1_status"] === "completed" &&
    $row["chapter_2_status"] === "completed" &&
    $row["chapter_3_status"] === "completed" &&
    $row["chapter_4_status"] === "completed" &&
    $row["chapter_5_status"] === "completed" &&
    $row["chapter_6_status"] === "completed"
);
    // อัปเดตค่า congratulation เป็น 1
$update_sql = "UPDATE users SET congratulation = 1 WHERE username = ?";
$update_stmt = $conn->prepare($update_sql);

if ($update_stmt) {
    $update_stmt->bind_param("s", $username);
    $update_stmt->close();
} else {
    echo "❌ SQL Error: " . $conn->error;
}

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สมมติว่าได้ค่า $row จากฐานข้อมูลแล้ว
$chapter_status = [
    'chapter_1_status' => $row['chapter_1_status'],
    'chapter_2_status' => $row['chapter_2_status'],
    'chapter_3_status' => $row['chapter_3_status'],
    'chapter_4_status' => $row['chapter_4_status'],
    'chapter_5_status' => $row['chapter_5_status'],
    'chapter_6_status' => $row['chapter_6_status']
];

$pretest_score = $row['pretest_score'] ?? null;
$posttest_score = $row['posttest_score'] ?? null;

// คำนวณเปอร์เซ็นต์ความคืบหน้าจากสถานะของบทเรียน
$completed_chapters = 0;
foreach ($chapter_status as $chapter => $status) {
    //echo "$chapter สถานะ: $status<br>"; // แสดงสถานะของแต่ละบท
    if ($status === 'completed') {
        $completed_chapters++;
    }
}

// เปอร์เซ็นต์จากสถานะบทเรียน
$percentage = $completed_chapters * 12.5;
//echo "จำนวนบทที่เรียนจบ: $completed_chapters<br>"; // แสดงจำนวนบทที่เรียนจบ

// เพิ่มเปอร์เซ็นต์จาก pretest และ posttest
if (!is_null($pretest_score)) {
    $percentage += 12.5; // เพิ่ม 12.5% เมื่อมีทั้ง pretest และ posttest
    //echo "Pretest และ Posttest มีค่าทั้งสอง: เพิ่ม 12.5%<br>";
}
if (!is_null($posttest_score)){
    $percentage += 12.5;
}

// กำหนดให้เปอร์เซ็นต์ไม่เกิน 100%
$percentage = min($percentage, 100);

//echo "ความคืบหน้าทั้งหมด: " . $percentage . "%";
$congratulation = $row['congratulation'];
// ✅ ถ้าทุกเงื่อนไขเป็นจริง ให้แสดงอนิเมชั่นและ redirect ไปที่ scoreboard.php
// ตรวจสอบเงื่อนไข
if ($score_completed && $status_completed && $congratulation == 0) {
    // อัปเดตค่าลง MySQL ให้เป็น 1
    $sql = "UPDATE users SET congratulation = 1, end_date = NOW() WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    // แสดงหน้า Congratulations
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Congratulations!</title>
        <style>
            /* Reset CSS */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                
            }
    
            body {
                font-family: Arial, sans-serif;
                overflow: hidden;
            }
    
            /* Overlay ทับทั้งหน้าจอ */
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
    
            /* Container สำหรับข้อความและรูปภาพ */
            .congrats-container {
                text-align: center;
                color: white;
            }
    
            .congratulations {
                font-size: 3em;
                color: #28a745;
                animation: celebrate 2s ease-in-out, colorChange 3s infinite, glow 1.5s infinite;
                text-shadow: 0 0 10px #28a745, 0 0 20px #28a745, 0 0 30px #28a745;
                transform-style: preserve-3d;
            }

            /* Animation สำหรับขยายและหด */
            @keyframes celebrate {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }

            /* Animation สำหรับเปลี่ยนสี */
            @keyframes colorChange {
                0% { color: #28a745; }
                25% { color: #ffcc00; }
                50% { color: #ff6666; }
                75% { color: #66ccff; }
                100% { color: #28a745; }
            }

            /* Animation สำหรับแสงสว่าง (Glow Effect) */
            @keyframes glow {
                0% { text-shadow: 0 0 10px #28a745, 0 0 20px #28a745, 0 0 30px #28a745; }
                50% { text-shadow: 0 0 20px #ffcc00, 0 0 40px #ffcc00, 0 0 60px #ffcc00; }
                100% { text-shadow: 0 0 10px #28a745, 0 0 20px #28a745, 0 0 30px #28a745; }
            }

            /* เพิ่มเอฟเฟกต์หมุน (Rotate Effect) */
            @keyframes rotate {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* เพิ่มเอฟเฟกต์กระพริบ (Blink Effect) */
            @keyframes blink {
                0%, 50%, 100% { opacity: 1; }
                25%, 75% { opacity: 0; }
            }
    
            /* Animation สำหรับข้อความ */
            @keyframes celebrate {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
    
            /* รูปภาพที่เด้งขึ้นมา */
            .celebration-image {
                width: 150px;
                height: 150px;
                margin-top: 20px;
                animation: bounce 1.5s infinite;
            }
    
            /* Animation สำหรับรูปภาพ */
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }
    
            /* Loading Bar */
            .loading-bar {
                width: 200px;
                height: 10px;
                background-color: #e0e0e0;
                border-radius: 10px;
                margin: auto; /* จัดให้อยู่กลางในแนวแกน X */
                overflow: hidden;
                position: relative;
                display: flex; /* ใช้ flexbox */
                justify-content: center; /* จัดให้อยู่กึ่งกลาง */
                align-items: center; /* จัดให้อยู่ตรงกลางในแนวตั้ง */
            }

    
            .loading-bar::before {
                content: \'\';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, #3498db, transparent);
                animation: loading 2s linear infinite;
            }
    
            @keyframes loading {
                0% { left: -100%; }
                100% { left: 100%; }
            }
        </style>
    </head>
    <body>
        <!-- Overlay ทับทั้งหน้าจอ -->
        <div class="overlay">
            <div class="congrats-container">
                <!-- ข้อความ Congratulations -->
                <div class="congratulations">Congratulations!</div>
    
                <!-- รูปภาพที่เด้งขึ้นมา -->
                <img src="image/remove.png" alt="Celebration" class="celebration-image">
    
                <!-- Loading Bar -->
                <div class="loading-bar"></div>
            </div>
        </div>
    
        <script>
            // Redirect หลังจาก 5 วินาที
            setTimeout(function() {
                window.location.href = "dashboard.php";
            }, 5000); // 5 วินาที
        </script>
    </body>
    </html>
    ';
} else {
    // หากเงื่อนไขไม่ตรง ให้ทำอย่างอื่น (ถ้ามี)
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Progress</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="setting/style.css">
</head>
<body>
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
    <button class="dash-btn">DASHBOARD</button>
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
    <audio id="bgm" loop autoplay muted>
    <source src="bgm.mp3" type="audio/mpeg">
</audio>
<audio id="sfx">
    <source src="sfx.mp3" type="audio/mpeg">
</audio>
<script src="script.js"></script>

    <header class="navbar">
        <div class="nav-left"><?php echo htmlspecialchars($username); ?></div>
    </header>
        <!-- Navigation Menu -->
    <section class="content-section">
        <h1>Learning Progress</h1>
        <p class="welcome-text">Welcome <?php echo htmlspecialchars($username); ?></p>

        <div class="progress-container">
            <div class="unit-list">
                <div class="unit top-units">
                    <a href="../index.html" style="color: <?php echo $pretest_completed ? 'green' : 'black'; ?>;">
                        Pre-test<br>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="unit2/p1.php" style="color: <?php echo getStatusColor($row['chapter_2_status']); ?>;">
                        UNIT 2<br>
                        <span>Phishing & Social Eng.</span>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="unit4/p1.php" style="color: <?php echo getStatusColor($row['chapter_4_status']); ?>;">
                        UNIT 4<br>
                        <span>Malware & Threats</span>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="unit6/p1.php" style="color: <?php echo getStatusColor($row['chapter_6_status']); ?>;">
                        UNIT 6<br>
                        <span>Cybersecurity Basics</span>
                    </a>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
                <div class="unit-marker unit-active"></div>
            </div>


            <div class="unit-list">
                <div class="unit top-units">
                    <a href="unit1/p1.php" style="color: <?php echo getStatusColor($row['chapter_1_status']); ?>;">
                        UNIT 1<br>
                        <span>Digital footprint.</span>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="unit3/p1.php" style="color: <?php echo getStatusColor($row['chapter_3_status']); ?>;">
                        UNIT 3<br>
                        <span>Passwords & Auth.</span>
                    </a>
                </div>
                <div class="unit">
                    <a href="unit5/p1.php" style="color: <?php echo getStatusColor($row['chapter_5_status']); ?>;">
                        UNIT 5<br>
                        <span>Safe Browsing</span>
                    </a>
                </div>
                <div class="unit">
                    <!-- แสดงหรือซ่อนปุ่ม Post-test -->
                    <?php if ($status_completed): ?>
                        <div class="unit">
                            <a href="posttest.php" style="color: <?php echo isset($posttest_score) && !is_null($posttest_score) ? 'green' : 'white'; ?>;">
                                Post-test<br>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Add this inside the <head> section -->
<style>
    /* Background Animation Styles */
    .background-animation {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
        background: radial-gradient(circle, #001122, #000000); /* สีพื้นหลังแบบ gradient เพื่อให้ดูเหมือนท้องฟ้ายามค่ำคืน */
    }

    .firefly {
        position: absolute;
        width: 5px;
        height: 5px;
        background-color: #ffff99; /* สีเหลืองอ่อนเหมือนหิ่งห้อย */
        border-radius: 50%;
        box-shadow: 0 0 10px 2px #ffff99; /* เงาเพื่อให้ดูเหมือนหิ่งห้อยมีแสง */
        opacity: 0;
        animation: flicker 2s infinite ease-in-out;
    }

    @keyframes flicker {
        0%, 100% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
    }
</style>

<!-- Add this right after the opening <body> tag -->
<div class="background-animation" id="firefly-container"></div>

<!-- Add this script at the end of the <body> section -->
<script>
    // Function to create fireflies
    function createFireflies() {
        const container = document.getElementById('firefly-container');
        const numFireflies = 50; // จำนวนหิ่งห้อย

        for (let i = 0; i < numFireflies; i++) {
            const firefly = document.createElement('div');
            firefly.classList.add('firefly');
            container.appendChild(firefly);

            // Randomize position and animation delay
            const x = Math.random() * 100; // ตำแหน่ง X แบบสุ่ม
            const y = Math.random() * 100; // ตำแหน่ง Y แบบสุ่ม
            const delay = Math.random() * 2; // ความล่าช้าในการเริ่ม animation

            firefly.style.left = `${x}%`;
            firefly.style.top = `${y}%`;
            firefly.style.animationDelay = `${delay}s`;

            // Randomize movement
            animateFirefly(firefly);
        }
    }

    // Function to animate fireflies
    function animateFirefly(firefly) {
        const duration = Math.random() * 5 + 5; // ความเร็วการเคลื่อนที่แบบสุ่ม
        const x = Math.random() * 100; // ตำแหน่ง X ใหม่
        const y = Math.random() * 100; // ตำแหน่ง Y ใหม่

        firefly.style.transition = `all ${duration}s linear`;
        firefly.style.left = `${x}%`;
        firefly.style.top = `${y}%`;

        // Repeat animation
        setTimeout(() => animateFirefly(firefly), duration * 1000);
    }

    // Start creating fireflies
    createFireflies();
</script>
<script src="script.js"></script>
<script src="setting/script.js"></script>
</body>
</html>