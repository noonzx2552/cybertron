<?php
session_start();

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
$sql = "SELECT pretest_score, 
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
$conn->close();

// เช็คว่า pretest_score เป็น NULL หรือไม่
$pretest_completed = !is_null($row['pretest_score']);

// ฟังก์ชันกำหนดสีของบทเรียน
function getStatusColor($status) {
    if ($status === 'completed') return '#28a745'; // เขียว
    if ($status === 'in_progress') return '#ffc107'; // เหลือง
    return 'white'; // ขาว (not_started)
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Progress</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <header class="navbar">
        <div class="nav-left"><?php echo htmlspecialchars($username); ?></div>
        <nav class="nav-right">
            <a href="#">Setting</a>
            <a onclick="location.href='/animated-login-form/logout.php'">Logout</a>
        </nav>
    </header>

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
                    <a href="unit/chapter2.php" style="color: <?php echo getStatusColor($row['chapter_2_status']); ?>;">
                        UNIT 2<br>
                        <span>Phishing & Social Eng.</span>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="unit/chapter4.php" style="color: <?php echo getStatusColor($row['chapter_4_status']); ?>;">
                        UNIT 4<br>
                        <span>Malware & Threats</span>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="unit/chapter6.php" style="color: <?php echo getStatusColor($row['chapter_6_status']); ?>;">
                        UNIT 6<br>
                        <span>Cybersecurity Basics</span>
                    </a>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress-fill"></div>
                <div class="unit-marker unit-active"></div>
            </div>

            <div class="unit-list">
                <div class="unit top-units">
                    <a href="chapter/chapter1.php" style="color: <?php echo getStatusColor($row['chapter_1_status']); ?>;">
                        UNIT 1<br>
                        <span>Digital footprint.</span>
                    </a>
                </div>
                <div class="unit top-units">
                    <a href="chapter/chapter3.php" style="color: <?php echo getStatusColor($row['chapter_3_status']); ?>;">
                        UNIT 3<br>
                        <span>Passwords & Auth.</span>
                    </a>
                </div>
                <div class="unit">
                    <a href="chapter/chapter5.php" style="color: <?php echo getStatusColor($row['chapter_5_status']); ?>;">
                        UNIT 5<br>
                        <span>Safe Browsing</span>
                    </a>
                </div>
                <div class="unit">
                    <a href="chapter/posttest.php">
                        Post-test<br>
                    </a>
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
</body>
</html>
