<?php
session_start(); // เพิ่มบรรทัดนี้เพื่อให้ใช้ $_SESSION ได้

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user_db"; // เปลี่ยนเป็นชื่อฐานข้อมูลจริงของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$registration_error = "";

// ตรวจสอบเมื่อผู้ใช้ส่งข้อมูลผ่าน POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   // รับค่าจากฟอร์มและตัดช่องว่างด้านหน้า-หลังของ username
   $username = trim($_POST['username']);
   $password = $_POST['password'];

   // ตรวจสอบว่ารหัสผ่านตรงตามเงื่อนไขหรือไม่
   if (strlen($password) < 6) {
       $registration_error = "Password must be at least 6 characters long.";
   } else {
       // ตรวจสอบว่า username มีอยู่ในฐานข้อมูลหรือไม่
       $sql = "SELECT * FROM users WHERE username = ?";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("s", $username);
       $stmt->execute();
       $result = $stmt->get_result();

       if ($result->num_rows > 0) {
           // ถ้ามี username ซ้ำ ให้เก็บข้อความผิดพลาดใน $_SESSION
           $_SESSION['registration_error'] = "Username already exists. Please choose a different one.";
       } else {
           // **บันทึกรหัสผ่านโดยไม่เข้ารหัส**
         $sql_insert = "INSERT INTO users (username, password, start_date, 
         chapter_1_status, chapter_2_status, chapter_3_status, 
         chapter_4_status, chapter_5_status, chapter_6_status) 
         VALUES (?, ?, NOW(), 'not_started', 'not_started', 'not_started', 
         'not_started', 'not_started', 'not_started')";

         $stmt_insert = $conn->prepare($sql_insert);
         $stmt_insert->bind_param("ss", $username, $password);
         $stmt_insert->execute();


           if ($stmt_insert->execute()) {
               header("Location: login.php");
               exit();
           } else {
               $registration_error = "Error: " . $stmt_insert->error;
           }
           $stmt_insert->close();
       }
       $stmt->close();
   }
}


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!--=============== REMIXICONS ===============-->
   <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

   <!--=============== CSS ===============-->
   <link rel="stylesheet" href="assets\css\styles.css">

   <title>Animated login form - Bedimcode</title>
</head>
<body>
   <div class="login">
      <img src="assets\img\login-bg.png" alt="login image" class="login__img">
      <form action="register.php" method="POST" class="login__form" onsubmit="return validatePassword()">
         <h1 class="login__title">Register</h1>
   
         <div class="login__content">
            <div class="login__box">
               <i class="ri-user-3-line login__icon"></i>
               <div class="login__box-input">
                  <input type="text" required class="login__input" id="login-email" name="username" placeholder="">
                  <label for="login-email" class="login__label">Username</label>
               </div>
            </div>
   
            <div class="login__box">
               <i class="ri-lock-2-line login__icon"></i>
               <div class="login__box-input">
                  <input type="password" required class="login__input" id="login-pass" name="password" placeholder="">
                  <label for="login-pass" class="login__label">Password</label>
                  <i class="ri-eye-off-line login__eye" id="login-eye"></i>
               </div>
            </div>
            <!-- แสดงข้อผิดพลาดสำหรับรหัสผ่าน -->
            
         </div>
      <span id="password-error" class="login__error" style="display: none; color: white; text-align: center;"></span>
      <!-- แสดงข้อความแจ้งเตือนถ้ามีข้อผิดพลาด -->
      <?php if (isset($_SESSION["registration_error"])): ?>
         <div class="login__error" style="color: white; text-align: center;">
            <?php
            echo $_SESSION["registration_error"];
            unset($_SESSION["registration_error"]);  // ลบค่าข้อความผิดพลาดออกจากเซสชันหลังจากที่แสดงแล้ว
            ?>
         </div>
      <?php endif; ?>
         <button type="submit" class="login__button" style="margin-top: 20px;">Register</button>

   
         <p class="login__register">
            Have an account? <a href="login.php">login</a>
         </p>
      </form>
   </div>
</body>

   
   <!--=============== MAIN JS ===============-->
   <script src="assets/js/main.js"></script>

   <script>
      // ฟังก์ชันตรวจสอบรหัสผ่าน
      function validatePassword() {
         const password = document.getElementById("login-pass").value;
         const passwordError = document.getElementById("password-error");

         const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*_])[A-Za-z\d!@#$%^&*_]{6,}$/;

         // เคลียร์ข้อความผิดพลาดก่อนการตรวจสอบ
         passwordError.textContent = "";
         passwordError.style.display = "none"; // ซ่อนข้อความผิดพลาดในตอนแรก

         if (password.length < 6) {
            passwordError.textContent = "Password must be at least 6 characters long.";
            passwordError.style.display = "block"; // แสดงข้อความข้อผิดพลาดเมื่อรหัสผ่านสั้นเกินไป
            return false;  // ห้ามส่งฟอร์ม
         } else if (!regex.test(password)) {
            passwordError.textContent = "Password must contain uppercase, lowercase, a number, and a special character.";
            passwordError.style.display = "block"; // แสดงข้อความข้อผิดพลาดเมื่อรหัสผ่านไม่ตรงเงื่อนไข
            return false;  // ห้ามส่งฟอร์ม
         }
         return true;  // ถ้ารหัสผ่านถูกต้อง ส่งฟอร์มได้
      }

   </script>
</body>
</html>
