<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">ปริ้นรายงานการยืมเล่มทะเบียนล่าสุด</h5>
    <table id="table_all_histories" class="hidden table align-middle">
      <thead>
        <tr>
          <th>
            #
          </th>
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
        $histories = getHistoriesBorrow();
        while ($row = $histories->fetch_assoc()) {
          $prisonerData = getPrisonerById($row["prisoner_id"]);
          $officerData = getOfficerById($row["officer_id"]);
        ?>

          <tr>
            <td width="5%">
              <div class="form-check checkbox-lg">
                <input class="form-check-input primary hover" type="checkbox" value="" name="check_data[<?php echo $row["id"]; ?>]">
              </div>
            </td>
            <td width="5%">
              <?php echo $row["id"] ?>
            </td>
            <td width="18%">
              <?php echo $officerData["firstname"] . " " . $officerData["lastname"] ?>
            </td>
            <td width="5%">
              <?php echo getHistoryStatusBadge($row) ?>
            </td>
            <td width="18%">
              <?php echo $prisonerData["firstname"] . " " . $prisonerData["lastname"] ?>
            </td>
            <td width="8%">
              <?php echo $row["note"] ?>
            </td>
            <td width="10%">
              <?php echo $row["location"] ?>
            </td>
            <td width="12%">
              <?php echo converNormalDateToThai($row["date"]) ?>
            </td>
            <td width="12%">
              <a href="prisoner.php?code=<?php echo $prisonerData["code"] ?>" class="btn btn-outline-info ">ดูข้อมูล</a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <br>

    <div class="">
      <button class="btn btn-secondary mr-4" onclick="selectAll()">เลือกทั้งหมด</button>
      <span>&nbsp;</span>
      <button id="print_button" class="btn btn-warning ml-4" onclick="handleOnPrintClick()">ปริ้น</button>
      <span>&nbsp;</span>
    </div>
  </div>
</div>

<script>
  let table = new DataTable('#table_all_histories', {
    order: [
      [1, 'desc']
    ],
    "columnDefs": [{
      "orderable": false,
      "targets": 0
    }],
    initComplete: function() {
      document.getElementById("table_all_histories").classList.remove("hidden");
    }
  });
</script>

<script>
  async function showAllData() {
    await table
      .page.len(-1)
      .draw()
    const element = document.getElementById("print_button");
    element.scrollIntoView();
  }

  function selectAll() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (const checkbox of checkboxes) {
      checkbox.checked = true;
    }
  }

  async function handleOnPrintClick() {
    let currentPage = table.page();
    let currentShowAmount = table.page.len();
    showAllData();
    const checkboxes = document.querySelectorAll('input[name^="check_data"]');
    const checkedPrisoners = [];
    checkboxes.forEach(checkbox => {
      if (checkbox.checked) {
        const id = checkbox.name.match(/\d+/)[0];
        checkedPrisoners.push("c[" + id + "]=");
      }
    });
    if (checkedPrisoners.length == 0) {
      Swal.fire({
        title: "ขออภัย!",
        text: "โปรดเลือกข้อมูลที่ต้องการจะปริ้น!",
        icon: "warning"
      });
      return;
    }
    let args = checkedPrisoners.join("&");
    window.open("./print-histories-borrow.php?" + args);
    await table.page.len(currentShowAmount).draw();
    table.page(currentPage).draw(false);
    const element = document.getElementById("print_button");
    element.scrollIntoView();

  }
</script>