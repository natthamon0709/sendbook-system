<?php
require 'vendor/autoload.php';
include_once "includes/init.php";

$responseData = [];
if (isset($_FILES['excelFile'])) {
  $inputFileName = $_FILES['excelFile']['tmp_name'];

  try {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);


    $worksheet = $spreadsheet->getActiveSheet();
    $rowIterator = $worksheet->getRowIterator();
    $rowIterator->next();
    $insertedCount = 0;
    $first = false;
    foreach ($rowIterator as $row) {
      try {
        if (!$first) {
          $first = true;
          continue;
        }

        $cellIterator = $row->getCellIterator();
        $cellData = [];
        foreach ($cellIterator as $cell) {
          $cellData[] = $cell->getValue();
        }
        $code = $cellData[1];
        if (!isset($code)) {
          continue;
        }
        $id_card = $cellData[2];
        $fullname = $cellData[3];
        if (!isset($fullname)) {
          continue;
        }
        $args = explode(" ", $fullname);
        if (count($args) != 2) {
          continue;
        }
        $firstname = $args[0];
        $lastname = $args[1];
        $result = addPrisoner($firstname, $lastname, $code, $id_card);
        if ($result == "success") {
          $insertedCount = $insertedCount + 1;
        }
      } catch (Exception $ex) {
        continue;
      }
    }
    $responseData['status'] = 'success';
    $responseData['message'] = "อัพโหลดเล่มทะเบียน  " . $insertedCount . " เล่ม!";
    echo json_encode($responseData);
  } catch (Exception $e) {
    $responseData['status'] = 'error';
    $responseData['message'] = "An error occurred: " . $e->getMessage();
    echo json_encode($responseData);
  }
}
