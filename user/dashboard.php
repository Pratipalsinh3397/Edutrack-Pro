<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsstuid'] == 0)) {
  header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || Dashboard</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Mobile responsiveness -->

  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  <div class="container-scroller">
    <!-- Navbar -->
    <?php include_once('includes/header.php'); ?>

    <div class="container-fluid page-body-wrapper">
      <!-- Sidebar -->
      <?php include_once('includes/sidebar.php'); ?>

      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row purchace-popup">
            <div class="col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card">
              <div class="card card-secondary">
                <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                  <p class="mb-2 mb-md-0">📢 Notices from the school — kindly check!</p>
                  <a href="view-notice.php" target="_blank" class="btn btn-warning btn-sm mt-2 mt-md-0">View Notice</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Add more dashboard content here -->

        </div>
        <!-- content-wrapper ends -->

        <?php include_once('includes/footer.php'); ?>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="./vendors/chart.js/Chart.min.js"></script>
  <script src="./vendors/moment/moment.min.js"></script>
  <script src="./vendors/daterangepicker/daterangepicker.js"></script>
  <script src="./vendors/chartist/chartist.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="./js/dashboard.js"></script>
</body>

</html>
<?php } ?>
