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
    <title>Lab Unit 1: Online Identity & Digital Footprint</title>
    <link rel="stylesheet" href="../css/labunit.css"> <!-- Link to the CSS file -->

    <link rel="stylesheet" href="../bar/style.css"> <!-- Link to the CSS file -->
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

    <section class="content-section">
        <h1>Lab unit 1: Cybersecurity & Threat Prevention</h1>

        <div class="content-text">
            <p><strong>ตามหาข้อมูลจาก social media ของ username : @supersigma777</strong></p>
            <p><strong>ลักษณะ flag ของคำตอบคือ flag{answer}</strong></p>

            <div class="input-group">
                <label for="answer">Answer or flag:</label>
                <input type="text" id="answer" name="answer" placeholder="Enter your answer or flag">
            </div>

            <div class="button-group">
                <button class="submit-button" onclick="checkAnswer()">SUBMIT</button>
            </div>
        </div>
    </section>

    <footer class="footer-buttons">
        <button class="back-button" onclick="window.location.href='p1.php'">back</button>

    </footer>
    <!-- Pop-up for Correct Answer -->
    <div id="correct-popup" class="popup">
        <div class="popup-content">
            <span class="close-popup" onclick="closePopup('correct-popup')">&times;</span>
            <p>Correct! Well done! 🎉</p>
            <img src="thumbs-up.png" alt="Thumbs Up" class="popup-image">
        </div>
    </div>

    <!-- Pop-up for Incorrect Answer -->
    <div id="incorrect-popup" class="popup">
        <div class="popup-content">
            <span class="close-popup" onclick="closePopup('incorrect-popup')">&times;</span>
            <p>Try again! 😅</p>
        </div>
    </div>
    

    <script>
        function checkAnswer() {
            let answer = document.getElementById("answer").value;

            fetch(window.location.href, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "answer=" + encodeURIComponent(answer)
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // ดูว่ามีอะไรผิดพลาด
                if (data.status === "correct") {
                    document.getElementById("correct-popup").style.display = "block";
                } else if (data.status === "incorrect") {
                    document.getElementById("incorrect-popup").style.display = "block";
                }
            });

        }

        function closePopup(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>
    <script defer src="script.js"></script>
    <script defer src="script_copy.js"></script>
</body>
</html>
