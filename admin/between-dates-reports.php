<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edutrack Pro || Between Dates Reports</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- Layout styles -->
  <link rel="stylesheet" href="css/style.css" />
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
          <!-- Page Header -->
          <div class="page-header">
            <h3 class="page-title">Between Dates Reports - Students</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
              </ol>
            </nav>
          </div>

          <!-- Form Row -->
          <div class="row justify-content-center">
            <div class="col-12  col-md col-lg grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center mb-4">Generate Student Report by Date</h4>

                  <form class="forms-sample" method="post" action="between-date-reprtsdetails.php">
                    <div class="form-group">
                      <label for="fromdate">From Date:</label>
                      <input type="date" class="form-control" id="fromdate" name="fromdate" required>
                    </div>

                    <div class="form-group">
                      <label for="todate">To Date:</label>
                      <input type="date" class="form-control" id="todate" name="todate" required>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Submit</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/select2/select2.min.js"></script>
  <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
</body>
</html>
<?php } ?>
