<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $adminid = $_SESSION['sturecmsaid'];
    $AName = $_POST['adminname'];
    $mobno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $sql = "UPDATE tbladmin SET AdminName=:adminname, MobileNumber=:mobilenumber, Email=:email WHERE ID=:aid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':adminname', $AName, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobilenumber', $mobno, PDO::PARAM_STR);
    $query->bindParam(':aid', $adminid, PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Your profile has been updated")</script>';
    echo "<script>window.location.href ='profile.php'</script>";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edutrack Pro || Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
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
            <h3 class="page-title">Admin Profile</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admin Profile</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md col-lg grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center mb-4">Admin Profile</h4>
                  <form method="post">
                    <div class="row">
                      <?php
                      $sql = "SELECT * FROM tbladmin WHERE ID=:aid";
                      $query = $dbh->prepare($sql);
                      $query->bindParam(':aid', $_SESSION['sturecmsaid'], PDO::PARAM_STR);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                      ?>
                      <div class="col-md-12 mb-3">
                        <label>Admin Name</label>
                        <input type="text" name="adminname" value="<?php echo $row->AdminName; ?>" class="form-control" required>
                      </div>
                      <div class="col-md-12 mb-3">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo $row->UserName; ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-12 mb-3">
                        <label>Contact Number</label>
                        <input type="text" name="mobilenumber" value="<?php echo $row->MobileNumber; ?>" class="form-control" maxlength="10" pattern="[0-9]+" required>
                      </div>
                      <div class="col-md-12 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $row->Email; ?>" class="form-control" required>
                      </div>
                      <div class="col-md-12 mb-3">
                        <label>Registration Date</label>
                        <input type="text" value="<?php echo $row->AdminRegdate; ?>" class="form-control" readonly>
                      </div>
                      <?php }
                      } ?>
                    </div>
                    <div class="text-center mt-3">
                      <button type="submit" class="btn btn-primary px-4 w-100" name="submit">Update</button>
                    </div>
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
