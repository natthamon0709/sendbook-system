<?php
if (!isset($_GET["id"])) {
  redirectPath();
}
if (isset($_POST["submit"])) {
  $id = $_POST["id"];
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $code = $_POST["code"];
  $id_card = $_POST["id_card"];
  $result = updatePrisoner($id, $firstname, $lastname, $code, $id_card);
  if ($result == "success") {
    $swalSuccess = "แก้ไขข้อมูลเรียบร้อยแล้ว!";
  } else {
    $swalError = $result;
  }
}
$prisoner = getPrisonerById($_GET["id"]);
if ($prisoner == null) {
  redirectPath();
}
?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">แก้ไขเล่มทะเบียนนักโทษ</h5>
    <form method="post">
      <div class="row mb-3">
        <div class="col-6">
          <label class="form-label">ชื่อจริง</label>
          <input type="text" class="form-control" name="firstname" value="<?php echo $prisoner["firstname"] ?>">
        </div>
        <div class="col-6">
          <label class="form-label">นามสกุล</label>
          <input type="text" class="form-control" name="lastname" value="<?php echo $prisoner["lastname"] ?>">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">รหัสผู้ต้องขัง</label>
          <input type="text" class="form-control" name="code" value="<?php echo $prisoner["code"] ?>">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">เลขบัตรประชาชน</label>
          <input type="text" class="form-control" name="id_card" value="<?php echo $prisoner["id_card"] ?>">
        </div>
      </div>
      <input type="hidden" class="form-control" name="id" value="<?php echo $_GET["id"]; ?>">
      <button type="submit" name="submit" class="btn btn-primary">บันทึก</button>
    </form>
  </div>
</div>