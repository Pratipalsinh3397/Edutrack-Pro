<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $eid = $_GET['editid'];
    $image = $_FILES["image"]["name"];
    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");

    if (!in_array($extension, $allowed_extensions)) {
      echo "<script>alert('Logo has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
    } else {
      $newImageName = md5($image . time()) . '.' . $extension;
      move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $newImageName);

      $sql = "UPDATE tblstudent SET Image = :image WHERE ID = :eid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':image', $newImageName, PDO::PARAM_STR);
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edutrack Pro || Update Students Image</title>
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .container-responsive {
      max-width: 100%;
      margin: auto;
    }
    .card {
      width: 100%;
    }
    img {
      max-width: 100%;
      height: auto;
    }
    @media (max-width: 768px) {
      .card-title, .form-group label {
        display: block;
      }
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include_once('includes/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">Update Students Image</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Students Image</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">Update Students Image</h4>
                  <hr />
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <?php
                    $eid = $_GET['editid'];
                    $sql = "SELECT tblstudent.StudentName, tblstudent.Image FROM tblstudent WHERE tblstudent.ID = :eid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                      foreach ($results as $row) { ?>
                        <div class="form-group">
                          <label>Student Name</label>
                          <input type="text" class="form-control" value="<?php echo htmlentities($row->StudentName); ?>" readonly>
                        </div>
                        <div class="form-group">
                          <label>Old Image</label><br>
                          <img src="images/<?php echo htmlentities($row->Image); ?>" width="100" height="100">
                        </div>
                        <div class="form-group">
                          <label>New Image</label>
                          <input type="file" name="image" class="form-control" required>
                        </div>
                    <?php }
                    } ?>
                    <button type="submit" name="submit" class="btn btn-primary mt-3 btn-block w-100">Update</button>
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
