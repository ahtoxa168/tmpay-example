<?php
// topup.php - ระบบเติมเงิน TMPAY

$merchant_id = 'YOUR_MERCHANT_ID_HERE'; // เปลี่ยน Merchant ID ของคุณ
$resp_url = 'https://yourwebsite.com/tmpay_callback.php'; // URL สำหรับส่งข้อมูลกลับ
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เติมเงิน TMPAY - TrueMoney & Razer Gold</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="text"] { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 25px; background: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #218838; }
        .tab { display: none; }
        .active { display: block; }
        .tabs button { padding: 10px 20px; margin-right: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>เติมเงินด้วยบัตรเงินสด</h2>
        
        <!-- Tabs -->
        <div class="tabs">
            <button onclick="showTab(0)" class="active">TrueMoney</button>
            <button onclick="showTab(1)">Razer Gold Pin</button>
        </div>

        <!-- TrueMoney Form -->
        <div id="tab0" class="tab active">
            <h3>บัตร TrueMoney</h3>
            <form method="POST" action="">
                <input type="hidden" name="channel" value="truemoney">
                <label>รหัสบัตร TrueMoney (14 หลัก)</label>
                <input type="text" name="pin" maxlength="14" placeholder="12345678901234" required>
                <button type="submit" name="submit">เติมเงินด้วย TrueMoney</button>
            </form>
        </div>

        <!-- Razer Gold Form -->
        <div id="tab1" class="tab">
            <h3>Razer Gold Pin</h3>
            <form method="POST" action="">
                <input type="hidden" name="channel" value="razer_gold_pin">
                <label>Razer Gold Pin (14 หลัก)</label>
                <input type="text" name="pin" maxlength="14" placeholder="12345678901234" required>
                <button type="submit" name="submit">เติมเงินด้วย Razer Gold</button>
            </form>
        </div>
    </div>

    <script>
        function showTab(n) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.getElementById('tab' + n).classList.add('active');
        }
    </script>
</body>
</html>

<?php
// ================== Logic การส่งข้อมูล ==================
if (isset($_POST['submit']) && isset($_POST['pin'])) {
    $pin = trim($_POST['pin']);
    $channel = $_POST['channel'];

    if (strlen($pin) !== 14 || !is_numeric($pin)) {
        echo "<script>alert('รหัสบัตรต้องเป็นตัวเลข 14 หลัก');</script>";
    } else {
        // เรียกฟังก์ชันส่งไป TMPAY
        $url = 'https://www.tmpay.net/TPG/backend.php?' . http_build_query([
            'merchant_id' => $merchant_id,
            'password'    => $pin,
            'resp_url'    => $resp_url,
            'channel'     => $channel
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        curl_close($ch);

        if (strpos($response, 'SUCCEED') !== false) {
            echo "<script>alert('รับรายการสำเร็จแล้ว! ระบบกำลังตรวจสอบบัตร...');</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด: " . htmlspecialchars($response) . "');</script>";
        }
    }
}
?>
