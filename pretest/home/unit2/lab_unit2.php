<?php
session_start(); // เริ่มต้นเซสชัน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$logged_in_user = $_SESSION['username']; // กำหนดค่าให้ถูกต้อง

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงค่า chapter_1_status
$sql = "SELECT chapter_1_status FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $logged_in_user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// ตรวจสอบคำตอบที่ส่งมาทาง POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["answer"])) {
    $user_answer = trim($_POST["answer"]);
    $correct_answer = "flag{correct_answer}"; // กำหนดคำตอบที่ถูกต้อง

    if ($user_answer === $correct_answer) {
        // ถ้าผู้ใช้ตอบถูกต้อง ให้บันทึก chapter_1_status เป็น "completed"
        $update_sql = "UPDATE users SET chapter_1_status = 'completed' WHERE username = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("s", $logged_in_user);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "correct"]);
        exit();
    } else {
        echo json_encode(["status" => "incorrect"]);
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Unit 2: Phishing & Social Engineering</title>
    <link rel="stylesheet" href="../css/labunit.css"> <!-- Link to the CSS file -->
</head>
<body>
    <header class="navbar">
        <div class="nav-left">
            <span><?php echo htmlspecialchars($logged_in_user); ?></span>
        </div>
        <nav class="nav-right">
            <a href="../home.php">Home</a>
            <a href="#">Setting</a>
            <a href="#">Logout</a>
        </nav>
    </header>

    <section class="content-section">
        <h1>Lab unit 6: Cybersecurity & Threat Prevention</h1>

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
</body>
</html>
