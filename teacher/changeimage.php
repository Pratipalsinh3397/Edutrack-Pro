<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {

    $eid = $_GET['editid'];
    $image = $_FILES["image"]["name"];
    $extension = substr($image, strlen($image) - 4, strlen($image));
    $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
    if (!in_array($extension, $allowed_extensions)) {
      echo "<script>alert('Logo has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
    } else {
      $image = md5($image) . time() . $extension;
      move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $image);
      $sql = "update tblstudent set Image=:image where ID=:eid";
      $query = $dbh->prepare($sql);

      $query->bindParam(':image', $image, PDO::PARAM_STR);
      $query->bindParam(':eid', $eid, PDO::PARAM_STR);
      $query->execute();
      echo '<script>alert("Student image has been updated")</script>';
      echo "<script>window.location.href ='manage-students.php'</script>";
    }
  }

?>
  <!DOCTYPE html>
  <html lang="en">

  <head>

    <title>Edutrack Pro || Update Students Image</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css" />

  </head>

  <body>
  <div class="container-scroller">
  <?php include_once('includes/header.php'); ?>
  <div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title"> Update Students Image </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"> Update Students Image</li>
            </ol>
          </nav>
        </div>
        <div class="row">
          <div class="col-lg-8 mx-auto grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title text-center mb-4">Update Student Image</h4>
                <hr />
                <form class="forms-sample" method="post" enctype="multipart/form-data">
                  <?php
                  $eid = $_GET['editid'];
                  $sql = "SELECT tblstudent.StudentName,tblstudent.Image FROM tblstudent WHERE tblstudent.ID=:eid";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                  ?>
                      <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlentities($row->StudentName); ?>" readonly>
                      </div>

                      <div class="form-group">
                        <label>Old Image</label><br>
                        <img src="images/<?php echo $row->Image; ?>" class="img-fluid rounded" alt="Student Image" style="max-width: 150px;">
                      </div>

                      <div class="form-group">
                        <label>New Image</label>
                        <input type="file" name="image" class="form-control" required>
                      </div>

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary" name="submit">Update</button>
                      </div>
                  <?php
                    }
                  }
                  ?>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>
</div>

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/select2/select2.min.js"></script>
    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="js/typeahead.js"></script>
    <script src="js/select2.js"></script>
    <!-- End custom js for this page -->
  </body>

  </html><?php }  ?>