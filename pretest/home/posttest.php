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

// ถ้ามีการส่งฟอร์ม ให้ตรวจจับค่า posttest_score และอัปเดตในฐานข้อมูล
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["posttest_score"])) {
        $posttest_score = intval($_POST["posttest_score"]); // แปลงเป็น int เพื่อความปลอดภัย

        // ตรวจสอบว่าค่าที่ส่งมาอยู่ในช่วง 0-10 หรือไม่
        if ($posttest_score >= 0 && $posttest_score <= 10) {
            $update_sql = "UPDATE users SET posttest_score = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);

            if ($update_stmt) {
                $update_stmt->bind_param("is", $posttest_score, $username);

                if ($update_stmt->execute()) {
                    echo "✅ Updated posttest_score successfully!";
                } else {
                    echo "❌ Error updating posttest_score: " . $update_stmt->error;
                }

                $update_stmt->close();
            } else {
                echo "❌ SQL Error: " . $conn->error;
            }
        } else {
            echo "❌ Invalid posttest_score value (must be between 0-10)";
        }
    }
}

// ดึงค่าล่าสุดของ posttest_score
$sql = "SELECT posttest_score FROM users WHERE username = ?";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Posttest Score</title>
</head>
<body>
    <h1>Update Posttest Score</h1>
    <form method="post" action="">
        <label for="posttest_score">Posttest Score:</label>
        <input type="number" name="posttest_score" value="<?php echo htmlspecialchars($row["posttest_score"] ?? ''); ?>" min="0" max="10" required>
        <br>
        <input type="submit" value="Update">
        <form action="home.php" method="get">
            <button type="submit">Exit</button>
        </form>


    </form>
</body>
</html>
