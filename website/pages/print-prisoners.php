<?php
require_once 'vendor/autoload.php';
include_once "includes/init.php";

use Mpdf\Mpdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

// ✅ ตรวจสอบว่ามีข้อมูลส่งมาทาง POST หรือ GET
if (isset($_POST['prisoners'])) {
    // กรณีมาจาก AJAX หรือฟอร์มที่ส่ง JSON
    $ids = json_decode($_POST['prisoners'], true);
    if (!is_array($ids) || empty($ids)) {
        die("No prisoners selected.");
    }

    // สร้าง array สำหรับจำลอง ?c[]= เพื่อใช้โค้ดต่อได้
    $_GET['c'] = [];
    foreach ($ids as $id) {
        $_GET['c'][$id] = '';
    }
} elseif (isset($_GET['c']) && is_array($_GET['c'])) {
    // กรณีเปิดผ่าน URL เช่น print-prisoners.php?c[1]=&c[2]=
    // ใช้ $_GET['c'] ได้เลย
} else {
    die("No data received.");
}

// ✅ เตรียมเครื่องมือสำหรับสร้างบาร์โค้ดและ PDF
$generator = new BarcodeGeneratorPNG();
$mpdf = new Mpdf([
    'tempDir' => __DIR__ . '/tmp', // ป้องกัน error เขียนไฟล์ไม่ได้
]);

$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'utf-8';
$mpdf->autoScriptToLang = true;
$mpdf->autoLangToFont = true;

// ✅ เริ่มสร้าง HTML
$html = '
<style>
table {
  border-collapse: collapse;
  width: 100%;
}
tr {
  text-align: center;
  border: 1px solid black;
}
td {
  text-align: center; 
  padding: 20px 10px;
  border: 1px solid black;
}
.title {
  text-align: center;
  font-weight: bold;
}
.barcode {
  text-align: center;
}
.code {
  text-align: center;
  margin-top: 5px;
}
img {
  padding: 10px 0;
}
</style>

<table>
';

$ids = $_GET['c'];
$count = 0;
$rowOpen = false;

// วนลูปนักโทษแต่ละคน
foreach ($ids as $id => $vv) {
    $prisoner = getPrisonerById($id);
    if (!$prisoner) continue;

    // สร้างแถวใหม่ทุก ๆ 3 คอลัมน์
    if ($count % 3 === 0) {
        if ($rowOpen) $html .= '</tr>';
        $html .= '<tr>';
        $rowOpen = true;
    }

    // สร้างบาร์โค้ด
    $barcode = base64_encode(
        $generator->getBarcode($prisoner["code"], $generator::TYPE_CODE_128, 1, 30)
    );

    // เพิ่มข้อมูลนักโทษ
    $html .= '
    <td>
      <div class="title">' . htmlspecialchars($prisoner["firstname"] . " " . $prisoner["lastname"]) . '</div>
      <div class="barcode"><img src="data:image/png;base64,' . $barcode . '"></div>
      <div class="code">' . htmlspecialchars($prisoner["code"]) . '</div>
    </td>';

    $count++;
}

// ปิดแถวสุดท้าย
if ($rowOpen) $html .= '</tr>';
$html .= '</table>';

// สร้าง PDF
$mpdf->WriteHTML($html);
$mpdf->Output();
