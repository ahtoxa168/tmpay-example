<?php
// ตัวอย่างฟังก์ชันเติมเงิน
function tmpay_topup($password, $channel) {
    $merchant_id = 'YOUR_MERCHANT_ID';     // ได้จาก TMPAY
    $resp_url = 'https://yourwebsite.com/tmpay_callback.php'; // URL รับผลลัพธ์ (ต้องเป็น HTTPS แนะนำ)

    // สร้าง URL
    $url = 'https://www.tmpay.net/TPG/backend.php?' . http_build_query([
        'merchant_id' => $merchant_id,
        'password'    => $password,      // รหัสบัตร 14 หลัก
        'resp_url'    => $resp_url,
        'channel'     => $channel        // truemoney หรือ razer_gold_pin
    ]);

    // ใช้ cURL (แนะนำ)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    curl_close($ch);

    // หรือใช้ file_get_contents ถ้า cURL ไม่ได้
    // $response = file_get_contents($url);

    if (strpos($response, 'SUCCEED') !== false) {
        // รับรายการสำเร็จ (ยังไม่ใช่เติมสำเร็จ ต้องรอ callback)
        echo "รับรายการสำเร็จ รอผลลัพธ์...";
        return true;
    } else {
        echo "เกิดข้อผิดพลาด: " . $response;
        return false;
    }
}

// ================== ตัวอย่างการใช้งาน ==================

// TrueMoney
if (isset($_POST['truemoney_pin'])) {
    $pin = trim($_POST['truemoney_pin']);
    tmpay_topup($pin, 'truemoney');
}

// Razer Gold Pin
if (isset($_POST['razer_pin'])) {
    $pin = trim($_POST['razer_pin']);
    tmpay_topup($pin, 'razer_gold_pin');
}
?>
