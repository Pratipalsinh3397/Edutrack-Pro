<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $cname = $_POST['cname'];
    $section = $_POST['section'];
    $cfees = $_POST['cfees'];

    // Check if the class and section already exists
    $sql_check = "SELECT ID FROM tblclass WHERE ClassName = :cname AND Section = :section";
    $query_check = $dbh->prepare($sql_check);
    $query_check->bindParam(':cname', $cname, PDO::PARAM_STR);
    $query_check->bindParam(':section', $section, PDO::PARAM_STR);
    $query_check->execute();

    if ($query_check->rowCount() > 0) {
      echo '<script>alert("This class and section already exists.")</script>';
    } else {
      $sql = "INSERT INTO tblclass(ClassName, Section, fees) VALUES (:cname, :section, :cfees)";
      $query = $dbh->prepare($sql);
      $query->bindParam(':cname', $cname, PDO::PARAM_STR);
      $query->bindParam(':section', $section, PDO::PARAM_STR);
      $query->bindParam(':cfees', $cfees, PDO::PARAM_STR);
      $query->execute();
      $LastInsertId = $dbh->lastInsertId();
      if ($LastInsertId > 0) {
        echo '<script>alert("Class has been added.")</script>';
        echo "<script>window.location.href ='add-class.php'</script>";
      } else {
        echo '<script>alert("Something went wrong. Please try again.")</script>';
      }
    }
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <title>Edutrack Pro || Add Class</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
      <!-- Header -->
      <?php include_once('includes/header.php'); ?>

      <div class="container-fluid page-body-wrapper">
        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Add Class </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add Class</li>
                </ol>
              </nav>
            </div>

            <!-- Responsive Form Section -->
            <div class="row">
              <div class="col-12 col-md col-lg grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Add Class</h4>
                    <form class="forms-sample" method="post">

                      <div class="form-group">
                        <label for="cname">Class Name</label>
                        <input type="text" name="cname" id="cname" class="form-control" required>
                      </div>

                      <div class="form-group">
                        <label for="section">Section</label>
                        <select name="section" id="section" class="form-control" required>
                          <option value="">Choose Section</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="D">D</option>
                          <option value="E">E</option>
                          <option value="F">F</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="cfees">Total Fees</label>
                        <input type="number" name="cfees" id="cfees" class="form-control" required>
                      </div>

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Add</button>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Responsive Form Section -->

          </div>
          <!-- content-wrapper ends -->

          <!-- Footer -->
          <?php include_once('includes/footer.php'); ?>
        </div>
      </div>
    </div>

    <!-- JS Scripts -->
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
