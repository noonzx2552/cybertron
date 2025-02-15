<?php
session_start(); // เริ่มต้นเซสชัน

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username'])) {
    die("You must be logged in to access this page.");
}

$logged_in_user = $_SESSION['username']; // ดึงชื่อผู้ใช้จากเซสชัน

// ดึงข้อมูลของผู้ใช้ที่ล็อกอินอยู่
$stmt = $conn->prepare("SELECT chapter_1_status FROM users WHERE username = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $logged_in_user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// ตรวจสอบสถานะ ถ้าสถานะเป็น "completed" ไม่ต้องส่งค่าใหม่
if ($row["chapter_1_status"] !== "completed") {
    // ตั้งค่าใหม่เป็น "in_progress" และอัปเดต
    $new_status = "in_progress";

    // ใช้ prepared statement
    $stmt = $conn->prepare("UPDATE users SET chapter_1_status = ? WHERE username = ?");
    
    // ตรวจสอบว่า prepare() สำเร็จหรือไม่
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $new_status, $logged_in_user);

    if ($stmt->execute()) {
        // ✅ ถ้าอัปเดตสำเร็จให้ redirect ไป home.php
        header("Location: ../home.php");
        exit(); // หยุด script
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Chapter 1 Status</title>
</head>
<body>
    <h1>Change Chapter 1 Status</h1>
    <form method="post" action="">
        <label for="chapter_1_status">Current Status: <?php echo htmlspecialchars($row["chapter_1_status"]); ?></label>
        <select name="chapter_1_status">
            <option value="not_started" <?php echo ($row["chapter_1_status"] == "not_started") ? "selected" : ""; ?>>Not Started</option>
            <option value="in_progress" <?php echo ($row["chapter_1_status"] == "in_progress") ? "selected" : ""; ?>>In Progress</option>
            <option value="completed" <?php echo ($row["chapter_1_status"] == "completed") ? "selected" : ""; ?>>Completed</option>
        </select>
        <input type="submit" value="Change" >
    </form>
</body>
</html>

<?php
$conn->close();
?>
