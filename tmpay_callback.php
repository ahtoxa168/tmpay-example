<?php
// รับค่าจาก TMPAY (GET Method)
$transaction_id = $_GET['transaction_id'] ?? '';
$password       = $_GET['password'] ?? '';           // รหัสบัตรที่ส่งไป
$real_amount    = $_GET['real_amount'] ?? '0.00';    // จำนวนเงินจริงที่ได้
$status         = $_GET['status'] ?? '0';            // 1 = สำเร็จ

// Log เพื่อตรวจสอบ (แนะนำ)
file_put_contents('tmpay_log.txt', date('Y-m-d H:i:s') . " - " . print_r($_GET, true) . "\n", FILE_APPEND);

if ($status == '1') {
    // เติมเงินสำเร็จ → อัพเดทฐานข้อมูลผู้ใช้ที่นี่
    // ตัวอย่าง
    // $user_id = getUserIdFromPin($password); // ค้นจาก DB
    // updateUserBalance($user_id, $real_amount);

    echo "SUCCEED|TOPUP_SUCCESS_" . $real_amount;  // ต้องตอบกลับแบบนี้
} else {
    // ล้มเหลว (3=ใช้ไปแล้ว, 4=รหัสผิด, 5=อื่นๆ)
    echo "ERROR|TOPUP_FAILED";
}

exit;
?>
