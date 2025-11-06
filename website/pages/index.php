<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">ค้นหาเล่มทะเบียน</h5>
    <div class="mb-2">
      ค้นหาเล่มทะเบียนผู้ต้องขังเพื่อดำเนินการ (สแกนบาร์โค้ดแล้วกดค้นหา)
    </div>
    <form method="get" action="prisoner.php">
      <div class="row">
        <div class="col-8">
          <div class="input-search-wrapper">
            <input class="form-control" id="code_input" type="text" name="code" placeholder="รหัสผู้ต้องขัง" autocomplete="off" oninput="handleOnChange(this)">
            <div id="code_input_select" size="5" class="input-search-select">
            </div>
          </div>
        </div>
        <div class="col-4">
          <button type="submit" class="btn btn-outline-primary form-control">ค้นหา</button>
        </div>
      </div>
    </form>
    <div class="mb-3"></div>
  </div>
</div>
<div class="hidden">
  <table id="table_data_prisoners">
    <thead>
      <tr>
        <th>
          รหัสผู้ต้องขัง
        </th>
        <th>
          ชื่อจริง
        </th>
        <th>
          นามสกุล
        </th>
      </tr>
    </thead>
    <tbody>
      <?php
      $prisoners = getActivePrisoners();
      while ($row = $prisoners->fetch_assoc()) {
      ?>

        <tr>
          <td>
            <?php echo $row["code"]; ?>
          </td>
          <td>
            <?php echo $row["firstname"] ?>
          </td>
          <td>
            <?php echo $row["lastname"] ?>
          </td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>
<script>
  let table_data_prisoners = new DataTable('#table_data_prisoners', {
    order: [
      [0, 'asc']
    ]
  });
</script>
<script>
  const inputElement = document.querySelector('#code_input');

  inputElement.addEventListener('focus', function() {
    showSearchBox();

  });
  inputElement.addEventListener('blur', function() {
    unshowSearchBox();
  });
  const inputSelect = document.querySelector('#code_input_select');
</script>
<script>
  function isAllDigits(text) {
    const regex = /^[0-9]+$/;
    return regex.test(text);
  }

  function handleOnChange(e) {
    let text = e.value;
    searchToLocation(text);
    updateSearchBox(text);
  }

  function searchToLocation(text) {
    if (text.length == 10 && isAllDigits(text)) {
      window.location.href = "prisoner.php?code=" + text;
    }
    table_data_prisoners.search(text).draw();
  }

  function showSearchBox() {
    let datas = table_data_prisoners.rows({
      search: 'applied'
    }).data();
    let max = 5;
    let count = 0;
    let selectHtml = "";
    for (let i = 0; i < datas.length; i++) {
      count = count + 1;
      let data = datas[i];
      let code = data[0];
      let firstname = data[1];
      let lastname = data[2];
      inputSelect.classList.add("active");
      selectHtml = selectHtml + '<option onclick="selectSearchInput(' + "'" + code + "'" + ')">' + code + ' ' + firstname + ' ' + lastname + '</option>';
      if (count >= max) {
        break;
      }
    }
    inputSelect.innerHTML = selectHtml;
  }

  function unshowSearchBox() {
    inputSelect.classList.remove("active");
  }

  function updateSearchBox(value) {
    showSearchBox()
  }

  function selectSearchInput(v) {
    let value = v.replace(" ", "");
    inputElement.value = value;
    searchToLocation(value);
  }
</script>
<?php
include_once "pages/histories.php"
?>