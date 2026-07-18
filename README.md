# TMPAY API Documentation (PHP)

บริการรับชำระเงินด้วยบัตรเงินสด TrueMoney และ Razer Gold Pin

**เอกสารอ้างอิง**: [TMPAY Official]([https://www.tmpay.net/](https://www.tmpay.net/front/TMPAY-Overview_Coding_PHP.pdf))

**อัพเดทล่าสุด**: กรกฎาคม 2026 (อ้างอิงจากเอกสาร 2021)

---

## ภาพรวมระบบ

ระบบ TMPAY ทำงาน 2 ส่วนหลัก:

1. **ส่งรหัสบัตรไปตรวจสอบ** (เรียก API ของ TMPAY)
2. **รับผลลัพธ์กลับ** ผ่าน `resp_url` (Callback)

---

## Parameters

### Channel ที่รองรับ
- `truemoney` — บัตรเงินสด TrueMoney
- `razer_gold_pin` — Razer Gold Pin

### API Endpoint
https://www.tmpay.net/TPG/backend.php (GET Method)


### Parameters สำหรับส่งรหัสบัตร
| Parameter     | Description                          | Example                  |
|---------------|--------------------------------------|--------------------------|
| `merchant_id` | รหัสร้านค้า (10 ตัวอักษร)           | `YOUR_MERCHANT_ID`       |
| `password`    | รหัสบัตร 14 หลัก                     | `01234567890123`         |
| `resp_url`    | URL รับผลลัพธ์ (HTTPS แนะนำ)         | `https://your.site/callback.php` |
| `channel`     | ประเภทบัตร                           | `truemoney` หรือ `razer_gold_pin` |

### Response จากการส่งรหัส
- `SUCCEED|TRANSACTION_ID` — รับรายการสำเร็จ
- `ERROR|INVALID_MERCHANT_ID`
- `ERROR|INVALID_PASSWORD`
- `ERROR|INVALID_RESP_URL`

---

## Callback (resp_url)

TMPAY จะส่งผลลัพธ์กลับมาทาง GET

| Parameter       | Description                     | Example          |
|-----------------|---------------------------------|------------------|
| `transaction_id`| Transaction ID                  | `XYZ1234567`     |
| `password`      | รหัสบัตรที่ส่งไป               | `01234567890123` |
| `real_amount`   | จำนวนเงินที่ได้รับ              | `100.00`         |
| `status`        | สถานะ                           | `1` (สำเร็จ)     |

**Status Code**:
- `1` = เติมเงินสำเร็จ
- `3` = บัตรถูกใช้ไปแล้ว
- `4` = รหัสบัตรไม่ถูกต้อง
- `5` = อื่นๆ

**คุณต้องตอบกลับ**: `SUCCEED|MESSAGE` หรือ `ERROR|REASON`

---

## ตัวอย่างโค้ด PHP

ดูไฟล์:
- `topup.php` — ส่งรหัสบัตร
- `callback.php` — รับผลลัพธ์

---

## การติดตั้ง / การใช้งาน

1. สมัครสมาชิกและขอ `merchant_id` ที่ [tmpay.net](https://www.tmpay.net/)
2. สร้างหน้า Form ให้ผู้ใช้กรอกรหัสบัตร
3. เรียกฟังก์ชัน `tmpay_topup($pin, $channel)`
4. สร้าง Callback URL และบันทึกผลในฐานข้อมูล

---

## Security Tips

- ใช้ HTTPS เสมอ
- ตรวจสอบ PIN ซ้ำในฐานข้อมูล
- Log ทุก Transaction
- Validate Input ทุกครั้ง

---

**การทดสอบระบบ TMPAY**: กำหนด merchant_id ให้เป็น TEST
1. รหัสบัตรเงินสด 55555555555551 มูลค่า 50 บาท
2. รหัสบัตรเงินสด 55555555555552 มูลค่า 90 บาท
3. รหัสบัตรเงินสด 55555555555553 มูลค่า 150 บาท
4. รหัสบัตรเงินสด 55555555555554 มูลค่า 300 บาท
5. รหัสบัตรเงินสด 55555555555554 มูลค่า 500 บาท
6. รหัสบัตรเงินสด 55555555555554 มูลค่า 1000 บาท

---
**สร้างโดย Grok** - สำหรับ developer ไทย 🇹🇭
