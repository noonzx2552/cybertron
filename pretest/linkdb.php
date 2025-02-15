<?php
session_start(); // ต้องมี session_start() ก่อนที่จะใช้งาน session

// การเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // เปลี่ยนตามเซิร์ฟเวอร์ของคุณ
$username_db = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password_db = ""; // รหัสผ่านฐานข้อมูล
$dbname = "user_db"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบชื่อผู้ใช้ในฐานข้อมูล
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // ถ้ามีชื่อผู้ใช้ในฐานข้อมูล
        $row = $result->fetch_assoc();
        // ตรวจสอบรหัสผ่าน
        if ($password == $row['password']) {
            $_SESSION['username'] = $username; // เก็บข้อมูลใน session
            $_SESSION['user_id'] = $row['id'];  // เก็บ user_id ใน session
            
            // ไม่ echo ข้อความเพื่อป้องกันปัญหา header already sent
            header("Location: home.php"); // รีไดเร็กต์ไปที่หน้า home
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username.";
    }
}

$conn->close(); // ปิดการเชื่อมต่อ
?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
   <link rel="stylesheet" href="assets/css/styles.css">
   <title>Login Form</title>
</head>
<body>
   <div class="login">
      <img src="assets/img/login-bg.png" alt="login image" class="login__img">
      <form action="login_process.php" method="POST" class="login__form">
         <h1 class="login__title">Login</h1>

         <div class="login__content">
            <div class="login__box">
               <i class="ri-user-3-line login__icon"></i>
               <div class="login__box-input">
                  <input type="text" required class="login__input" id="login-email" name="username" placeholder=" ">
                  <label for="login-email" class="login__label">Username</label>
               </div>
            </div>

            <div class="login__box">
               <i class="ri-lock-2-line login__icon"></i>
               <div class="login__box-input">
                  <input type="password" required class="login__input" id="login-pass" name="password" placeholder=" ">
                  <label for="login-pass" class="login__label">Password</label>
                  <i class="ri-eye-off-line login__eye" id="login-eye"></i>
               </div>
            </div>
         </div>

         <!-- แสดงข้อผิดพลาดจาก PHP -->
         <?php if (isset($_SESSION["login_error"])): ?>
            <div class="login__error-wrapper" style="text-align: center; margin-top: 20px;">
               <div class="login__error" style="color: white;padding: 10px; border-radius: 5px; width: 80%; max-width: 400px; margin-left: auto; margin-right: auto;">
                  <?php echo $_SESSION["login_error"]; unset($_SESSION["login_error"]); ?>
               </div>
            </div>
         <?php endif; ?>

         <button type="submit" class="login__button" style="margin-top: 20px;">Login</button>

         <p class="login__register">
            Don't have an account? <a href="register.php">Register</a>
         </p>
      </form>
   </div>
   <script src="assets/js/main.js"></script>
</body>
</html>

