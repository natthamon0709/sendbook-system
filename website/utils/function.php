<?php

function getActivePrisoners() {
  global $conn;
  $sql = "SELECT * FROM prisoner WHERE is_deleted='false'";
  $query = $conn->query($sql);
  return $query;
}
function getPrisonerById($id, $check_deleted = true) {
  global $conn;
  if ($check_deleted) {
    $sql = "SELECT * FROM prisoner WHERE id=$id AND is_deleted='false' LIMIT 1";
  } else {
    $sql = "SELECT * FROM prisoner WHERE id=$id LIMIT 1";
  }
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  } else {
    return $query->fetch_assoc();
  }
}
function getPrisonerByCode($code, $check_deleted = true) {
  global $conn;
  if ($check_deleted) {
    $sql = "SELECT * FROM prisoner WHERE code='$code' AND is_deleted='false' LIMIT 1";
  } else {
    $sql = "SELECT * FROM prisoner WHERE code='$code' LIMIT 1";
  }
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  } else {
    return $query->fetch_assoc();
  }
}
function getPrisonerByIdCard($id_card, $check_deleted = true) {
  global $conn;
  if ($id_card == "") {
    return null;
  }
  if ($check_deleted) {
    $sql = "SELECT * FROM prisoner WHERE id_card='$id_card' AND is_deleted='false' LIMIT 1";
  } else {
    $sql = "SELECT * FROM prisoner WHERE id_card='$id_card' LIMIT 1";
  }
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  } else {
    return $query->fetch_assoc();
  }
}
function getPrisonerByName($firstname, $lastname, $check_deleted = true) {
  global $conn;
  if ($check_deleted) {
    $sql = "SELECT * FROM prisoner WHERE firstname='$firstname' AND lastname='$lastname' AND is_deleted='false' LIMIT 1";
  } else {
    $sql = "SELECT * FROM prisoner WHERE firstname='$firstname' AND lastname='$lastname' LIMIT 1";
  }
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  } else {
    return $query->fetch_assoc();
  }
}


function addPrisoner($firstname, $lastname, $code, $id_card) {
  global $conn;
  if ($firstname == "" || $lastname == "" || $code == "") {
    return "โปรดกรอกข้อมูลให้ครบถ้วน!";
  }
  $check = getPrisonerByCode($code);
  if ($check != null) {
    return "พบรหัสผู้ต้องขังคนนี้แล้ว!";
  }
  $conn->query("INSERT INTO prisoner (firstname, lastname, code, id_card) VALUES ('$firstname', '$lastname', '$code', '$id_card')");
  return "success";
}

function updatePrisoner($id, $firstname, $lastname, $code, $id_card) {
  global $conn;
  if ($firstname == "" || $lastname == "" || $code == "") {
    return "โปรดกรอกข้อมูลให้ครบถ้วน!";
  }
  $prisoner = getPrisonerById($id);
  if ($prisoner == null) {
    return "ไม่พบผู้ต้องขังคนนี้!!";
  }
  if ($prisoner["code"] != $code) {
    $check = getPrisonerByCode($code);
    if ($check != null) {
      return "พบรหัสผู้ต้องขังคนนี้แล้ว!";
    }
  }
  $conn->query("UPDATE prisoner SET firstname='$firstname', lastname='$lastname', code='$code', id_card='$id_card' WHERE id=$id");
  return "success";
}

function deletePrisoner($id) {
  global $conn;
  $prisoner = getPrisonerById($id);
  if ($prisoner == null) {
    return "ไม่พบผู้ต้องขังคนนี้!!";
  }
  $conn->query("UPDATE prisoner SET is_deleted='true' WHERE id=$id");
  return "success";
}

function getPrisonerStatusText($prisoner) {
  $status = $prisoner["status"];
  if ($status == 1) {
    return "ยังคงอยู่";
  } else if ($status == 2) {
    return "ถูกยืม";
  } else if ($status == 3) {
    return "ถูกนำไปที่อื่น";
  } else if ($status == 4) {
    return "หาย";
  } else {
    return "ไม่พบ Prisoner Status นี้!";
  }
}



function getPrisonerStatusBadge($prisoner) {
  $status = $prisoner["status"];
  if ($status == 1) {
    return "<div class='badge bg-success rounded-3 fw-semibold'>ยังคงอยู่</div>";
  } else if ($status == 2) {
    return "<div class='badge bg-warning rounded-3 fw-semibold'>ถูกยืม</div>";
  } else if ($status == 3) {
    return "<div class='badge bg-secondary rounded-3 fw-semibold'>ถูกนำไปที่อื่น</div>";
  } else if ($status == 4) {
    return "<div class='badge bg-danger rounded-3 fw-semibold'>หาย</div>";
  } else {
    return "<div class='badge bg-dark rounded-3 fw-semibold'>ไม่พบ Prisoner Status นี้!</div>";
  }
}


function redirectPath($errorMsg = "", $path = "./") {
  if ($errorMsg != "") {
?>
    <script>
      Swal.fire({
        title: "ขออภัย!",
        text: "<?php echo $errorMsg ?>",
        icon: "error"
      }).then((result) => {
        window.location.href = "<?php echo $path; ?>";
      });
    </script>
  <?php
  } else {
  ?>
    <script>
      window.location.href = "<?php echo $path; ?>";
    </script>
<?php
  }
  include_once "layout/admin-bottom.php";
  die();
  exit();
}


function getActiveOfficers() {
  global $conn;
  $sql = "SELECT * FROM officer WHERE is_deleted='false'";
  $query = $conn->query($sql);
  return $query;
}

function getOfficerById($id, $check_deleted = true) {
  global $conn;
  if ($check_deleted) {
    $sql = "SELECT * FROM officer WHERE id='$id' AND is_deleted='false' LIMIT 1";
  } else {
    $sql = "SELECT * FROM officer WHERE id='$id' LIMIT 1";
  }
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  } else {
    return $query->fetch_assoc();
  }
}

function getOfficerByName($firstname, $lastname, $check_deleted = true) {
  global $conn;
  if ($check_deleted) {
    $sql = "SELECT * FROM officer WHERE firstname='$firstname' AND lastname='$lastname' AND is_deleted='false' LIMIT 1";
  } else {
    $sql = "SELECT * FROM officer WHERE firstname='$firstname' AND lastname='$lastname' LIMIT 1";
  }
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  } else {
    return $query->fetch_assoc();
  }
}

function addOfficer($firstname, $lastname) {
  global $conn;
  if ($firstname == "" || $lastname == "") {
    return "โปรดกรอกข้อมูลให้ครบถ้วน!";
  }
  $check = getOfficerByName($firstname, $lastname);
  if ($check != null) {
    return "พบเจ้าหน้าที่ชื่อนี้แล้ว!!";
  }
  $conn->query("INSERT INTO officer (firstname, lastname) VALUES ('$firstname', '$lastname')");
  return "success";
}

function updateOfficer($id, $firstname, $lastname) {
  global $conn;
  if ($firstname == "" || $lastname == "") {
    return "โปรดกรอกข้อมูลให้ครบถ้วน!";
  }
  $data = getOfficerById($id);
  if ($data == null) {
    return "ไม่พบเจ้าหน้าที่คนนี้!!";
  }
  if ($data["firstname"] != $firstname || $data["lastname"] != $lastname) {
    $check = getOfficerByName($firstname, $lastname);
    if ($check != null) {
      return "พบเจ้าหน้าที่ชื่อนี้แล้ว!!";
    }
  }
  $conn->query("UPDATE officer SET firstname='$firstname', lastname='$lastname' WHERE id=$id");
  return "success";
}

function deleteOfficer($id) {
  global $conn;
  $data = getOfficerById($id);
  if ($data == null) {
    return "ไม่พบเจ้าหน้าที่คนนี้!!";
  }
  $conn->query("UPDATE officer SET is_deleted='true' WHERE id=$id");
  return "success";
}



function showOfficerSelcetOptions($selected_id = "") {
  $html = '
  <select name="officer_id" class="form-control">
    <option value="" selected disabled>
      เลือกเจ้าหน้าที่
    </option>';
  $query = getActiveOfficers();
  while ($row = $query->fetch_assoc()) {
    $sel = "";
    if ($selected_id == $row["id"]) {
      $sel = "selected";
    }
    $data = '
    <option value="' . $row["id"] . '" ' . $sel . '>
    ' . ($row["firstname"] . " " . $row["lastname"]) . '
    </option>';
    $html = $html . $data;
  }
  $html = $html . ' </select>';
  return $html;
}

function converThaiDateToNormal($dateStr) {
  $sub = explode("/", $dateStr);

  return (intval($sub[2]) - 543) . "-" . $sub[1] . "-" . $sub[0];
}

function borrowPrisoner($officer_id, $prisoner_id, $note, $location, $date) {
  global $conn;
  if ($officer_id == "") {
    return "โปรดเลือกเจ้าหน้าที่!";
  }
  if ($prisoner_id == "" || $date == "") {
    return "โปรดกรอกข้อมูลให้ครบถ้วน!";
  }
  $officer = getOfficerById($officer_id);
  if ($officer == null) {
    return "ไม่พบเจ้าหน้าที่คนนี้อยู่ในระบบ!";
  }
  $prisoner = getPrisonerById($prisoner_id);
  if ($prisoner == null) {
    return "ไม่พบผู้ต้องขังคนนี้อยู่ในระบบ!";
  }
  if ($prisoner["status"] != 1) {
    return "ไม่สามารถยืมเล่มทะเบียนผู้ต้องขังคนนี้ได้";
  }
  $date = converThaiDateToNormal($date);
  $sql = "INSERT INTO history (officer_id, prisoner_id, type, location, note, date) VALUES ($officer_id, $prisoner_id, 1, '$location', '$note', '$date')";
  $query = $conn->query($sql);
  if (!$query) {
    return $conn->error;
  }
  $query = $conn->query("UPDATE prisoner SET status=2 WHERE id=$prisoner_id LIMIT 1");
  if (!$query) {
    return $conn->error;
  }
  return "success";
}

function getHistoryStatusBadge($history) {
  $type = $history["type"];
  if ($type == 1) {
    return "<div class='badge bg-primary rounded-3 fw-semibold'>ยืม</div>";
  } else if ($type == 2) {
    return "<div class='badge bg-warning rounded-3 fw-semibold'>คืน</div>";
  } else if ($type == 3) {
    return "<div class='badge bg-secondary rounded-3 fw-semibold'>นำไปที่อื่น</div>";
  } else if ($type == 4) {
    return "<div class='badge bg-danger rounded-3 fw-semibold'>หาย</div>";
  } else if ($type == 5) {
    return "<div class='badge bg-success rounded-3 fw-semibold'>ค้นพบแล้ว</div>";
  } else {
    return "<div class='badge bg-dark rounded-3 fw-semibold'>ไม่พบ History Status นี้!</div>";
  }
}
function getLastPrisonerHistories($prisoner_id) {
  global $conn;
  $sql = "SELECT * FROM history WHERE prisoner_id=$prisoner_id ORDER BY id DESC LIMIT 1";
  $query = $conn->query($sql);
  if ($query->num_rows == 0) {
    return null;
  }
  return $query->fetch_assoc();
}

function getHistories() {
  global $conn;
  $sql = "SELECT * FROM history";
  $query = $conn->query($sql);
  return $query;
}

function getHistoriesBorrow() {
  global $conn;
  $sql = "SELECT * FROM history WHERE type=1";
  $query = $conn->query($sql);
  return $query;
}
function getHistoriesReturn() {
  global $conn;
  $sql = "SELECT * FROM history WHERE type=2";
  $query = $conn->query($sql);
  return $query;
}

function getHistoriesTakeAway() {
  global $conn;
  $sql = "SELECT * FROM history WHERE type=3";
  $query = $conn->query($sql);
  return $query;
}

function getHistoriesDisappear() {
  global $conn;
  $sql = "SELECT * FROM history WHERE type=4";
  $query = $conn->query($sql);
  return $query;
}

function getHistoriesFound() {
  global $conn;
  $sql = "SELECT * FROM history WHERE type=5";
  $query = $conn->query($sql);
  return $query;
}


function getActiveHistories() {
  global $conn;
  $sql = "SELECT * FROM history WHERE is_active='true'";
  $query = $conn->query($sql);
  return $query;
}

function getPrisonerHistories($prisoner_id) {
  global $conn;
  $sql = "SELECT * FROM history WHERE prisoner_id=$prisoner_id ORDER BY id DESC";
  $query = $conn->query($sql);
  return $query;
}

function actionPrisoner($action, $officer_id, $prisoner_id, $note, $location, $date) {
  global $conn;
  if ($action == "") {
    return "โปรดเลือกประเภทการดำเนินการ!";
  }
  // Auto-assign first available officer if none selected
  if ($officer_id == "" || $officer_id == null) {
    $firstOfficer = getActiveOfficers();
    if ($firstOfficer && $firstOfficer->num_rows > 0) {
      $officerData = $firstOfficer->fetch_assoc();
      $officer_id = $officerData["id"];
    } else {
      return "ไม่พบเจ้าหน้าที่ในระบบ!";
    }
  }
  
  if ($prisoner_id == "" || $date == "") {
    return "โปรดกรอกข้อมูลให้ครบถ้วน!";
  }
  $officer = getOfficerById($officer_id);
  if ($officer == null) {
    return "ไม่พบเจ้าหน้าที่คนนี้อยู่ในระบบ!";
  }
  $prisoner = getPrisonerById($prisoner_id);
  if ($prisoner == null) {
    return "ไม่พบผู้ต้องขังคนนี้อยู่ในระบบ!";
  }
  $date = converThaiDateToNormal($date);
  if ($action == "borrow") {
    if ($prisoner["status"] != 1) {
      return "ไม่สามารถยืมเล่มทะเบียนผู้ต้องขังคนนี้ได้";
    }
    $sql = "INSERT INTO history (officer_id, prisoner_id, type, location, note, date) VALUES ($officer_id, $prisoner_id, 1, '$location', '$note', '$date')";
    $query = $conn->query($sql);
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE prisoner SET status=2 WHERE id=$prisoner_id LIMIT 1");
    if (!$query) {
      return $conn->error;
    }
    return "success";
  } else if ($action == "return") {
    if (!($prisoner["status"] == 2 || $prisoner["status"] == 3)) {
      return "ไม่สามารถดำเนินการเล่มทะเบียนนี้ได้!";
    }
    $sql = "INSERT INTO history (officer_id, prisoner_id, type, location, note, date) VALUES ($officer_id, $prisoner_id, 2, '$location', '$note', '$date')";
    $query = $conn->query($sql);
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE prisoner SET status=1 WHERE id=$prisoner_id LIMIT 1");
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE history SET is_active='false' WHERE prisoner_id=$prisoner_id");
    if (!$query) {
      return $conn->error;
    }
    return "success";
  } else if ($action == "takeaway") {
    if (!($prisoner["status"] == 2)) {
      return "ไม่สามารถดำเนินการเล่มทะเบียนนี้ได้!";
    }
    $sql = "INSERT INTO history (officer_id, prisoner_id, type, location, note, date) VALUES ($officer_id, $prisoner_id, 3, '$location', '$note', '$date')";
    $query = $conn->query($sql);
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE prisoner SET status=3 WHERE id=$prisoner_id LIMIT 1");
    if (!$query) {
      return $conn->error;
    }
    return "success";
  } else if ($action == "disappear") {
    if (!($prisoner["status"] == 2 || $prisoner["status"] == 3)) {
      return "ไม่สามารถดำเนินการเล่มทะเบียนนี้ได้!";
    }
    $sql = "INSERT INTO history (officer_id, prisoner_id, type, location, note, date) VALUES ($officer_id, $prisoner_id, 4, '$location', '$note', '$date')";
    $query = $conn->query($sql);
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE prisoner SET status=4 WHERE id=$prisoner_id LIMIT 1");
    if (!$query) {
      return $conn->error;
    }
    return "success";
  } else if ($action == "found") {
    if (!($prisoner["status"] == 4)) {
      return "ไม่สามารถดำเนินการเล่มทะเบียนนี้ได้!";
    }
    $sql = "INSERT INTO history (officer_id, prisoner_id, type, location, note, date) VALUES ($officer_id, $prisoner_id, 5, '$location', '$note', '$date')";
    $query = $conn->query($sql);
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE prisoner SET status=1 WHERE id=$prisoner_id LIMIT 1");
    if (!$query) {
      return $conn->error;
    }
    $query = $conn->query("UPDATE history SET is_active='false' WHERE prisoner_id=$prisoner_id");
    if (!$query) {
      return $conn->error;
    }
    return "success";
  } else {
    return "ไม่พบ Action ประเภทนี้!";
  }
}

function converNormalDateToThai($dateStr) {
  $sub = explode("-", $dateStr);

  return $sub[2] . "-" . $sub[1] . "-" . (intval($sub[0]) + 543);
}