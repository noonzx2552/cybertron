<?php
session_start();

// Check if request is POST only
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method Not Allowed");
}

// Database connection settings
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if username and password are provided
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ดึงข้อมูลผู้ใช้ออกมา
        $row = $result->fetch_assoc();

        // เช็คว่า pretest_score เป็น NULL หรือไม่
        if ($row['pretest_score'] === NULL) {
            // ถ้า pretest_score เป็น NULL ให้ไปหน้า pretest
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: pretest/index.html");
            exit();
        } else {
            // ถ้า pretest_score ไม่เป็น NULL ให้ไปหน้า home
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: pretest/home/home.php");
            exit();
        }

    } else {
        $_SESSION["login_error"] = "Incorrect username or password.";
        header("Location: login.php"); // รีไดเร็กต์ไปที่ login.php
        exit();
    }

    $stmt->close();
} else {
    $_SESSION["login_error"] = "Please fill in all fields.";
}

$conn->close();
header("Location: login.php");
exit();
?>
