<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">ประวัติล่าสุด</h5>
    <table id="table_all_histories" class="hidden table align-middle">
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
            ชื่อผู้ต้องขัง
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
          <th>
            ดูข้อมูล
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        $histories = getHistories();
        while ($row = $histories->fetch_assoc()) {
          $prisonerData = getPrisonerById($row["prisoner_id"]);
          $officerData = getOfficerById($row["officer_id"]);
        ?>

          <tr>
            <td width="5%">
              <?php echo $row["id"] ?>
            </td>
            <td width="20%">
              <?php echo $officerData["firstname"] . " " . $officerData["lastname"] ?>
            </td>
            <td width="5%">
              <?php echo getHistoryStatusBadge($row) ?>
            </td>
            <td width="20%">
              <?php echo $prisonerData["firstname"] . " " . $prisonerData["lastname"] ?>
            </td>
            <td width="12%">
              <?php echo $row["note"] ?>
            </td>
            <td width="13%">
              <?php echo $row["location"] ?>
            </td>
            <td width="13%">
              <?php echo converNormalDateToThai($row["date"]) ?>
            </td>
            <td width="12%">
              <a href="prisoner.php?code=<?php echo $prisonerData["code"] ?>" class="btn btn-outline-info">ดูข้อมูล</a>
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
  let table = new DataTable('#table_all_histories', {
    order: [
      [0, 'desc']
    ],
    initComplete: function() {
      document.getElementById("table_all_histories").classList.remove("hidden");
    }
  });
</script>