<?php
if (isset($_POST["submit"])) {
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $code = $_POST["code"];
  $id_card = $_POST["id_card"];
  $result = addPrisoner($firstname, $lastname, $code, $id_card);
  if ($result == "success") {
    $swalSuccess = "เพิ่มข้อมูลเรียบร้อยแล้ว!";
  } else {
    $swalError = $result;
  }
}
?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">เพิ่มเล่มทะเบียนนักโทษ</h5>
    <form method="post">
      <div class="row mb-3">
        <div class="col-6">
          <label class="form-label">ชื่อจริง</label>
          <input type="text" class="form-control" name="firstname">
        </div>
        <div class="col-6">
          <label class="form-label">นามสกุล</label>
          <input type="text" class="form-control" name="lastname">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">รหัสผู้ต้องขัง</label>
          <input type="text" class="form-control" name="code">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">เลขบัตรประชาชน</label>
          <input type="text" class="form-control" name="id_card">
        </div>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">บันทึก</button>
    </form>
  </div>
</div>