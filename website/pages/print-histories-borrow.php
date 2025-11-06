
<?php
require_once 'vendor/autoload.php';
include_once "includes/init.php";

use Mpdf\Mpdf;


$mpdf = new Mpdf();
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'utf-8';
$mpdf->autoScriptToLang = true;
$mpdf->autoLangToFont   = true;

$html = '

<style>
.container {
  margin: 0 auto;
}
.text-center {
  text-align: center;
}
th {
  padding: 6px;
  background-color: rgb(216, 233, 243);
}
table {
  border-collapse: collapse;
}
td,
th {
  padding: 4px 8px;
  border: 1px solid black;
}
.name1 {
  padding-right: 40px;
}
.name2 {
  padding-right: 40px;
}
.date {
  width: 135px;
}
</style>
<div class="text-center">
<h3>
รายงานการยืมเล่มทะเบียน
</h3>
</div>
<table class="container">
<tr>
<th>
  ชื่อ-สกุล<br />
  (เจ้าหน้าที่)
</th>
<th>
  ชื่อ-สกุล<br />
  (ผู้ต้องขัง)
</th>
<th class="text-center date">
  ยืม<br />
  วัน/เดือน/ปี
</th>
</tr>
  ';
$ids = $_GET['c'];
$idsDatas = [];
foreach ($ids as $id => $vv) {
  array_push($idsDatas,  $id);
}
$idsString = implode(',', $idsDatas);
$sql = '
SELECT officer_id, COUNT(*) AS total
FROM history WHERE id IN (' . $idsString . ') AND type=1
GROUP BY officer_id;
';
$results = $conn->query($sql);
$index = 0;
while ($row = $results->fetch_assoc()) {
  $officer_id = $row["officer_id"];
  $officer = getOfficerById($officer_id);
  if ($officer == null) {
    continue;
  }
  $findSql = "SELECT * FROM history WHERE officer_id=$officer_id AND id IN ($idsString) AND type=1";
  $historyQuery = $conn->query($findSql);
  $first = false;
  while ($history = $historyQuery->fetch_assoc()) {
    $prisoner = getPrisonerById($history["prisoner_id"]);
    if ($prisoner == null) {
      continue;
    }
    $index = $index + 1;
    if (!$first) {
      $first =  true;
      $html = $html . '
      <tr>
  <td class="name1">' . ($officer["firstname"] . " " . $officer["lastname"]) . '</td>
  <td class="name2">' . ($index . " " . $prisoner["firstname"] . " " . $prisoner["lastname"]) . '</td>
  <td class="text-center">' . (thaidate("j M y", $history["date"])) . '</td>
</tr>
      ';
    } else {
      $html = $html . '
      <tr>
  <td class="name1"></td>
  <td class="name2">' . ($index . " " . $prisoner["firstname"] . " " . $prisoner["lastname"]) . '</td>
  <td class="text-center">' . (thaidate("j M y", $history["date"])) . '</td>
</tr>
      ';
    }
  }
}
/*thaidate('l j F Y', '2021-02-25');
// พฤหัสบดี 25 กุมภาพันธ์ 2564
*/
$html = $html . '</table>';
$mpdf->WriteHTML($html);

$mpdf->Output();
