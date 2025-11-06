<?php
// phpinfo();
if (isset($_POST["delete"])) {
  $id = $_POST["id"];
  $result = deletePrisoner($id);
  if ($result == "success") {
    $swalSuccess = "ลบข้อมูลเรียบร้อยแล้ว!";
  } else {
    $swalError = $result;
  }
}
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">เล่มทะเบียนนักโทษ</h5>
    <a type="button" class="btn btn-info m-1" href="add-prisoner.php">เพิ่มเล่มทะเบียน <i class="fa fa-plus" aria-hidden="true"></i></a>

    <div class="right">
      <form id="uploadForm">
        <input class="hidden" type="file" id="excelFile" name="excelFile" accept=".xlsx, .xlsm, .xltx, .xltm">
        <button type="button" id="uploadButton" class="btn btn-success m-1">อัพโหลด Excel <i class="fa fa-upload" aria-hidden="true"></i></button>
      </form>
    </div>
    <br>
    <div class="mb-5"></div>

    <table id="table_data" class="hidden table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>ID</th>
          <th>รหัสผู้ต้องขัง</th>
          <th>ชื่อจริง</th>
          <th>นามสกุล</th>
          <th>รหัสบัตรประชาชน</th>
          <th>สถานะ</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $prisoners = getActivePrisoners();
        while ($row = $prisoners->fetch_assoc()) {
        ?>
          <tr>
            <td>
              <div class="form-check checkbox-lg">
                <input class="form-check-input primary hover" type="checkbox" value="" name="check_prisoner[<?php echo $row["id"]; ?>]" id="check_prisoner_<?php echo $row["id"]; ?>">
              </div>
            </td>
            <td><?php echo $row["id"] ?></td>
            <td>
              <a href="prisoner.php?code=<?php echo $row["code"]; ?>" class="text-dark"><?php echo $row["code"]; ?></a>
            </td>
            <td><?php echo $row["firstname"] ?></td>
            <td><?php echo $row["lastname"] ?></td>
            <td><?php echo $row["id_card"] ?></td>
            <td><?php echo getPrisonerStatusBadge($row); ?></td>
            <td width="20%">
              <a href="edit-prisoner.php?id=<?php echo $row["id"]; ?>" class="btn btn-secondary">แก้ไข</a>
              <button class="btn btn-danger" onclick="deleteData(<?php echo $row['id']; ?>)">ลบ</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <br>
    <div>
      <button class="btn btn-secondary mr-4" onclick="selectAll()">เลือกทั้งหมด</button>
      <span>&nbsp;</span>
      <button id="print_button" class="btn btn-warning ml-4" onclick="handleOnPrintClick()">ปริ้น</button>
    </div>
  </div>
</div>

<script>
  // --- DataTable setup ---
  let table = new DataTable('#table_data', {
    order: [[1, 'desc']],
    lengthMenu: [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
    columnDefs: [{ orderable: false, targets: 0 }],
    initComplete: function() {
      document.getElementById("table_data").classList.remove("hidden");
    }
  });

  async function showAllData() {
    await table.page.len(-1).draw();
    const element = document.getElementById("print_button");
    element.scrollIntoView();
  }

  function selectAll() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (const checkbox of checkboxes) {
      checkbox.checked = true;
    }
  }

  // ✅ ฟังก์ชันเปิดแท็บใหม่และส่งข้อมูลแบบ POST
  function openPostWindow(url, data) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = url;
    form.target = "_blank"; // เปิดในแท็บใหม่

    for (const key in data) {
      if (data.hasOwnProperty(key)) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
      }
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }

  // ✅ ฟังก์ชันปริ้นใหม่ (ไม่ใช้ query string)
  async function handleOnPrintClick() {
    let currentPage = table.page();
    let currentShowAmount = table.page.len();
    await showAllData();

    const checkboxes = document.querySelectorAll('input[name^="check_prisoner"]');
    const selected = [];

    checkboxes.forEach(checkbox => {
      if (checkbox.checked) {
        const id = checkbox.name.match(/\d+/)[0];
        selected.push(id);
      }
    });

    if (selected.length === 0) {
      Swal.fire({
        title: "ขออภัย!",
        text: "โปรดเลือกเล่มทะเบียน!",
        icon: "warning"
      });
      return;
    }

    // ส่งแบบ POST ไปยัง print-prisoners.php
    const postData = { prisoners: JSON.stringify(selected) };
    openPostWindow("./print-prisoners.php", postData);

    await table.page.len(currentShowAmount).draw();
    table.page(currentPage).draw(false);
    const element = document.getElementById("print_button");
    element.scrollIntoView();
  }

  // --- ลบข้อมูล ---
  function deleteData(id) {
    Swal.fire({
      title: "คุณต้องการลบจริงๆ หรือไม่ ?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ลบ",
      cancelButtonText: "ยกเลิก"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: window.location,
          data: { id: id, delete: true },
          cache: false,
          success: function(response) {
            Swal.fire({
              title: "สำเร็จ!",
              text: "ลบข้อมูลเรียบร้อยแล้ว!",
              icon: "success"
            }).then(() => location.href = location.href);
          },
          failure: function(response) {
            Swal.fire({
              title: "ขออภัย!",
              text: response,
              icon: "error"
            }).then(() => location.href = location.href);
          }
        });
      }
    });
  }
</script>

<script>
  // --- upload excel ---
  const uploadForm = document.getElementById('uploadForm');
  const excelFile = document.getElementById('excelFile');
  const uploadButton = document.getElementById('uploadButton');

  function handleFileSelection() {
    excelFile.click();
  }

  excelFile.addEventListener('change', (event) => {
    const files = event.target.files;
    if (files.length === 1) {
      const file = files[0];
      handleUploadFile(file);
    }
  });

  uploadButton.addEventListener('click', handleFileSelection);

  function handleUploadFile(file) {
    if (file && file.size > 0) {
      const ext = file.name.split('.').pop();
      if (['xlsx', 'xlsm', 'xltx', 'xltm'].includes(ext)) {
        uploadFile(file);
      }
    }
  }

  async function uploadFile(file) {
    Swal.fire({
      icon: "info",
      title: "โปรดรอสักครู่",
      text: "กำลังอัพโหลดไฟล์...",
      showConfirmButton: false,
      timerProgressBar: true,
      timer: 16000
    });
    const formData = new FormData();
    formData.append('excelFile', file);

    try {
      const response = await fetch('upload-prisoners.php', { method: 'POST', body: formData });
      const result = await response.json();
      Swal.fire({
        title: "สำเร็จ!",
        text: result["message"],
        icon: "success"
      }).then(() => location.href = location.href);
    } catch (error) {
      Swal.fire({
        title: "เกิดข้อผิดพลาด!",
        text: error,
        icon: "error"
      }).then(() => location.href = location.href);
    }
  }
</script>
