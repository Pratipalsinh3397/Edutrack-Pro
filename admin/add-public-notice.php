<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $notmsg = $_POST['notmsg'];

    $sql = "INSERT INTO tblpublicnotice (NoticeTitle, NoticeMessage) VALUES (:nottitle, :notmsg)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->execute();
    $LastInsertId = $dbh->lastInsertId();

    if ($LastInsertId > 0) {
      echo '<script>alert("Notice has been added.")</script>';
      echo "<script>window.location.href ='add-public-notice.php'</script>";
    } else {
      echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || Add Notice</title>
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
            <h3 class="page-title">Add Notice</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Notice</li>
              </ol>
            </nav>
          </div>

          <!-- Responsive row -->
          <div class="row">
            <div class="col-12  col-md col-lg grid-margin stretch-card">
              <div class="card">
                <!-- Card Body START (Unchanged) -->
                <div class="card-body">
                  <h4 class="card-title" style="text-align: center;">Add Notice</h4>

                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Notice Title</label>
                      <input type="text" name="nottitle" value="" class="form-control" required='true'>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Notice Message</label>
                      <textarea name="notmsg" class="form-control" required='true'></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Add</button>
                  </form>
                </div>
                <!-- Card Body END -->
              </div>
            </div>
          </div>

        </div>
        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
      </div>
    </div>
  </div>

  <!-- JS Plugins -->
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
