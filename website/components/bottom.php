<script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/sidebarmenu.js"></script>
<script>
  <?php
  if (isset($swalSuccess)) {
  ?>
    Swal.fire({
      title: "สำเร็จ!",
      text: "<?php echo $swalSuccess ?>",
      icon: "success"
    }).then((result) => {
      if (window.customRedirect) {
        location.href = window.customRedirect;
      } else {
        location.href = location.href;
      }
    });
  <?php
  } else if (isset($swalError)) {
  ?>
    Swal.fire({
      title: "ขออภัย!",
      text: "<?php echo $swalError ?>",
      icon: "error"
    }).then((result) => {
      location.href = location.href;
    });
  <?php
  }
  ?>
</script>
</body>

</html>