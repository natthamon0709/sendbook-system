<?php
if (!isset($_GET["code"])) {
  redirectPath();
}
$prisoner = getPrisonerByCode($_GET["code"]);
if ($prisoner == null) {
  if (strpos($_GET["code"], " ")  !== false) {
    $ag = explode(" ", $_GET["code"]);
    $prisoner = getPrisonerByName($ag[0], $ag[1]);
  }
}
if ($prisoner == null) {
  $prisoner = getPrisonerByIdCard($_GET["code"]);
}

if ($prisoner == null) {
  redirectPath("ไม่พบเล่มทะเบียนนักโทษคนนี้อยู่ในระบบ!");
}
if (isset($_POST["action"])) {
  $action = $_POST["action"];
  $officer_id = $_POST["officer_id"];
  $prisoner_id = $prisoner["id"];
  $note = $_POST["note"];
  $location = $_POST["location"];
  $date = $_POST["date"];
  $result = actionPrisoner($action, $officer_id, $prisoner_id, $note, $location, $date);
  if ($result == "success") {
    if ($action == "borrow") {
      $swalSuccess = "เจ้าหน้าที่ยืมเล่มทะเบียนร้อยแล้ว!";
    } else  if ($action == "return") {
      $swalSuccess = "เจ้าหน้าที่คืนเล่มทะเบียนร้อยแล้ว!";
    } else  if ($action == "disappear") {
      $swalSuccess = "เจ้าหน้าที่ทำเล่มทะเบียนหาย!";
    } else  if ($action == "takeaway") {
      $swalSuccess = "เจ้าหน้าที่นำเล่มทะเบียนไปที่อื่นเรียบร้อยแล้ว!";
    } else  if ($action == "found") {
      $swalSuccess = "เจ้าหน้าที่พบเล่มทะเบียนเรียบร้อยแล้ว!";
    } else {
      $swalSuccess = "เจ้าหน้าที่ดำเนินการเรียบร้อยแล้ว!";
    }
  } else {
    $swalError = $result;
  }
  $prisoner = getPrisonerById($prisoner["id"]);
}
$status = $prisoner["status"];
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">ข้อมูลเล่มทะเบียนผู้ต้องขัง</h5>
    <table class="table">
      <tr>
        <th width="40%">
          รหัสผู้ต้องขัง :
        </th>
        <td width="60%">
          <?php echo $prisoner["code"]; ?>
        </td>
      </tr>
      <tr>
        <th width="40%">
          ชื่อจริง-นามสกุล :
        </th>
        <td width="60%">
          <?php echo $prisoner["firstname"] . " " . $prisoner["lastname"]; ?>
        </td>
      </tr>
      <tr>
        <th width="40%">
          รหัสบัตรประชาชน :
        </th>
        <td width="60%">
          <?php echo $prisoner["id_card"] ?>
        </td>
      </tr>
      <tr>
        <th width="40%">
          สถานะ :
        </th>
        <td width="60%">
          <?php echo getPrisonerStatusBadge($prisoner) ?>
        </td>
      </tr>
    </table>
    <div class="mb-2">

    </div>
  </div>
</div>
<?php

$last_officer_id = "";
if ($prisoner["status"] != 1) {
  $last_history = getLastPrisonerHistories($prisoner["id"]);
  if ($last_history != null) {
    $last_officer_id = $last_history["officer_id"];
  }
}

?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">เจ้าหน้าที่ต้องการดำเนินการ</h5>
    <form method="post" autocomplete="off">
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">ชื่อเจ้าหน้าที่</label>
          <?php echo showOfficerSelcetOptions($last_officer_id) ?>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">ประเภทการดำเนินการ</label>
          <select class="form-control" name="action" required>
            <option value="" disabled>
              ประเภท
            </option>
            <?php
            // สถานะ: ยังคงอยู่ (1)
            if ($status == 1) {
            ?>
              <option value="borrow" selected>
                ยืม
              </option>
            <?php
            }
            ?>
            <?php
            // สถานะ: ถูกยืม (2)
            if ($status == 2) {
            ?>
              <option value="return">
                คืน
              </option>
              <option value="takeaway">
                นำไปที่อื่น
              </option>
              <option value="disappear">
                หาย
              </option>
            <?php
            }
            ?>
            <?php
            // สถานะ: ถูกนำไปที่อื่น (3)
            if ($status == 3) {
            ?>
              <option value="return">
                คืน
              </option>
              <option value="disappear">
                หาย
              </option>
            <?php
            }
            ?>
            <?php
            // สถานะ: หาย (4)
            if ($status == 4) {
            ?>
              <option value="found">
                ค้นพบแล้ว
              </option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-4">
          <label class="form-label">หมายเหตุ</label>
          <input type="text" class="form-control" name="note" placeholder="กรอกหมายเหตุ">
        </div>
        <div class="col-4">
          <label class="form-label">สถานที่</label>
          <input type="text" class="form-control" name="location" placeholder="กรอกชื่อสถานที่">
        </div>
        <div class="col-4">
          <div class="form-group">
            <label class="form-label">วันที่</label>
            <input type="text" class="form-control" id="datepicker" name="date">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <label class="form-label">&nbsp;</label>
          <input type="submit" class="btn btn-secondary form-control" value="ดำเนินการ">
        </div>
      </div>
      <div class="row">
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">ประวัติการใช้งานเล่มทะเบียน</h5>
    <table id="table_data" class="display table align-middle">
      <thead>
        <tr>
          <th>
            ID
          </th>
          <th>
            ชื่อเจ้าหน้าที่
          </th>
          <th>
            ประเภท
          </th>
          <th>
            หมายเหตุ
          </th>
          <th>
            สถานที่
          </th>
          <th>
            วันที่
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        $histories = getPrisonerHistories($prisoner["id"]);
        while ($row = $histories->fetch_assoc()) {
          $officerData = getOfficerById($row["officer_id"]);
        ?>

          <tr>
            <td>
              <?php echo $row["id"] ?>
            </td>
            <td>
              <?php echo $officerData["firstname"] . " " . $officerData["lastname"] ?>
            </td>
            <td>
              <?php echo getHistoryStatusBadge($row) ?>
            </td>
            <td>
              <?php echo $row["note"] ?>
            </td>
            <td>
              <?php echo $row["location"] ?>
            </td>
            <td>
              <?php echo converNormalDateToThai($row["date"]) ?>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script>
  let table = new DataTable('#table_data', {
    order: [
      [0, 'desc']
    ]
  });

  $(function() {
    $("#datepicker").datepicker({
      language: 'th-th',
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayBtn: 'linked',
      todayHighlight: true,
      autoclose: true
    });
    $('#datepicker').datepicker('setDate', new Date());
    $('#datepicker').datepicker('update');
  });
</script>