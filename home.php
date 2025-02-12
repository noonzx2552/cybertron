<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง หากยังไม่ล็อกอินให้รีไดเร็กต์กลับไปหน้า login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// นำค่าจาก session ไปเก็บในตัวแปร $username
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Progress</title>
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>
    <header class="navbar">
        <div class="nav-left"><?php echo htmlspecialchars($username); ?></div>
        <nav class="nav-right">
            <a href="#">Home</a>
            <a href="#">Setting</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section class="content-section">
        <h1>Learning Progress</h1>
        <p class="welcome-text">Welcome <?php echo htmlspecialchars($username); ?></p>
    
        <div class="progress-container">
        <div class="unit-list">
            <div class="unit top-units">
                <a href="chapter/pretest.php">
                    Pre-test<br>
                </a>
            </div>
            <div class="unit top-units">
                <a href="chapter/chapter2.php">
                    UNIT 2<br>
                    <span>Phishing & Social Eng.</span>
                </a>
            </div>
            <div class="unit top-units">
                <a href="chapter/chapter4.php">
                    UNIT 4<br>
                    <span>Malware & Threats</span>
                </a>
            </div>
            <div class="unit top-units">
                <a href="chapter/chapter6.php">
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
            <a href="chapter/chapter1.php">
                Unit 1<br>
                <span>Digital footprint.</span>
            </a>
        </div>
        <div class="unit top-units">
            <a href="chapter/chapter3.php">
            UNIT 3<br>
                <span>Passwords & Auth.</span>
            </a>
        </div>
        <div class="unit">
            <a href="chapter/chapter5.php">
                UNIT 5<br>
                <span>Safe Browsing</span>
            </a>
        </div>
        <div class="unit">
            <a href="chapter/posttest.php">
                Post-test<br>
                <span></span>
            </a>
        </div>
    </div>
</div>

    </section>

    <footer class="footer-buttons">
        <button class="exit-button" onclick="location.href='logout.php'">Exit</button>
        <button class="next-button">Next</button>
    </footer>
</body>
</html>