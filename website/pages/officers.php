<?php
if (isset($_POST["delete"])) {
  $id = $_POST["id"];
  $result = deleteOfficer($id);
  if ($result == "success") {
    $swalSuccess = "ลบข้อมูลเรียบร้อยแล้ว!";
  } else {
    $swalError = $result;
  }
}
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">เจ้าหน้าที่</h5>
    <a type="button" class="btn btn-info m-1" href="add-officer.php">เพิ่มเจ้าหน้าที่ <i class="fa fa-plus" aria-hidden="true"></i></a>
    <br>
    <div class="mb-5"></div>
    <table id="table_data" class="display table align-middle">
      <thead>
        <tr>
          <th>
            ID
          </th>
          <th>
            ชื่อจริง
          </th>
          <th>
            นามสกุล
          </th>
          <th>
            Action
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        $datas = getActiveOfficers();
        while ($row = $datas->fetch_assoc()) {
        ?>
          <tr>
            <td>
              <?php echo $row["id"] ?>
            </td>
            <td>
              <?php echo $row["firstname"] ?>
            </td>
            <td>
              <?php echo $row["lastname"] ?>
            </td>
            <td>
              <a href="edit-officer.php?id=<?php echo $row["id"]; ?>" class="btn btn-secondary">แก้ไข</a>
              <button class="btn btn-danger" onclick="deleteData(<?php echo $row['id']; ?>)">ลบ</button>
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
  let table = new DataTable('#table_data', {});

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
          data: {
            'id': id,
            "delete": true
          },
          cache: false,
          success: function(response) {
            Swal.fire({
              title: "สำเร็จ!",
              text: "ลบข้อมูลเรียบร้อยแล้ว!",
              icon: "success"
            }).then((result) => {
              location.href = location.href;
            });
          },
          failure: function(response) {
            Swal.fire({
              title: "ขออภัย!",
              text: response,
              icon: "error"
            }).then((result) => {
              location.href = location.href;
            });
          }
        });
      }
    });
  }
</script>