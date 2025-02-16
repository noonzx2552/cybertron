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

    <audio id="bgm" loop autoplay muted>
        <source src="bgm.mp3" type="audio/mpeg">
    </audio>
    <audio id="sfx">
        <source src="sfx.mp3" type="audio/mpeg">
    </audio>
<script src="script.js"></script>
<script>
    const audio = document.getElementById('bgm');

    // บันทึกสถานะเพลงก่อนเปลี่ยนหน้าเว็บ
    window.addEventListener('beforeunload', () => {
        sessionStorage.setItem('audioCurrentTime', audio.currentTime);
        sessionStorage.setItem('audioPlaying', !audio.paused);
    });

    // ดึงสถานะเพลงเมื่อโหลดหน้าใหม่
    window.addEventListener('load', () => {
        const savedTime = parseFloat(sessionStorage.getItem('audioCurrentTime')) || 0;
        const isPlaying = sessionStorage.getItem('audioPlaying') === 'true';

        audio.currentTime = savedTime;
        if (isPlaying) {
            audio.play();
        }
    });
</script>

    </section>

    <footer class="footer-buttons">
        <button class="exit-button" onclick="location.href='logout.php'">Exit</button>
        <button class="next-button">Next</button>
    </footer>
</body>
</html>