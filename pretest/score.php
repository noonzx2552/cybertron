<?php
session_start();
include "linkdb.php"; // เชื่อมต่อฐานข้อมูล

// 🔍 Log ข้อมูล JSON ที่รับมา
error_log("รับข้อมูล JSON: " . file_get_contents("php://input"));

// 🔍 Log ข้อมูล SESSION
error_log("SESSION: " . json_encode($_SESSION));

// รับ JSON ที่ส่งมา
$data = json_decode(file_get_contents("php://input"), true);
$score = isset($data["score"]) ? intval($data["score"]) : 0;

// 🔍 Log ค่า score
error_log("คะแนนที่ได้รับ: " . $score);

// ตรวจสอบว่า user ล็อกอินหรือไม่
if (!isset($_SESSION["user_id"])) {
    error_log("❌ ไม่พบ user_id ใน session");
    echo json_encode(["message" => "กรุณาเข้าสู่ระบบก่อน!"]);
    exit();
}

$user_id = $_SESSION["user_id"];
error_log("✅ User ID: " . $user_id);

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "user_db");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    error_log("❌ การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    die(json_encode(["message" => "การเชื่อมต่อฐานข้อมูลล้มเหลว!"]));
}

// บันทึกคะแนนลงฐานข้อมูล
$sql = "UPDATE users SET pretest_score = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $score, $user_id);

if ($stmt->execute()) {
    error_log("✅ บันทึกคะแนนสำเร็จ: " . $score);
    echo json_encode(["status" => "success", "message" => "บันทึกคะแนนสำเร็จ!", "score" => $score]);
} else {
    error_log("❌ เกิดข้อผิดพลาดในการบันทึกคะแนน: " . $stmt->error);
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการบันทึกคะแนน"]);
}

$stmt->close();
$conn->close();
?>
