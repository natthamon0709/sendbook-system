<?php
if (!isset($_GET["id"])) {
  redirectPath();
}
if (isset($_POST["submit"])) {
  $id = $_POST["id"];
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $result = updateOfficer($id, $firstname, $lastname);
  if ($result == "success") {
    $swalSuccess = "แก้ไขข้อมูลเรียบร้อยแล้ว!";
  } else {
    $swalError = $result;
  }
}
$data = getOfficerById($_GET["id"]);
if ($data == null) {
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
          <input type="text" class="form-control" name="firstname" value="<?php echo $data["firstname"] ?>">
        </div>
        <div class="col-6">
          <label class="form-label">นามสกุล</label>
          <input type="text" class="form-control" name="lastname" value="<?php echo $data["lastname"] ?>">
        </div>
      </div>
      <input type="hidden" class="form-control" name="id" value="<?php echo $_GET["id"]; ?>">
      <button type="submit" name="submit" class="btn btn-primary">บันทึก</button>
    </form>
  </div>
</div>