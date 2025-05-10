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
    $eid = $_GET['editid'];

    $sql = "update tblclass set ClassName=:cname,Section=:section,fees=:cfees where ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cname', $cname, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->bindParam(':cfees', $cfees, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Class has been updated")</script>';
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edutrack Pro || Manage Class</title>
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    @media (max-width: 768px) {
      .form-group label {
        font-size: 14px;
      }
      .form-group input,
      .form-group select {
        font-size: 14px;
        padding: 10px;
      }
      .btn {
        width: 100%;
        margin-top: 10px;
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
            <h3 class="page-title"> Manage Class </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Manage Class</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">Manage Class</h4>
                  <form class="forms-sample" method="post">
                    <?php
                    $eid = $_GET['editid'];
                    $sql = "SELECT * from  tblclass where ID=$eid";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                      foreach ($results as $row) {
                    ?>
                        <div class="form-group">
                          <label>Class Name</label>
                          <input type="text" name="cname" value="<?php echo htmlentities($row->ClassName); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                          <label>Section</label>
                          <select name="section" class="form-control" required>
                            <option value="<?php echo htmlentities($row->Section); ?>"><?php echo htmlentities($row->Section); ?></option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Total Fees</label>
                          <input type="number" name="cfees" value="<?php echo htmlentities($row->fees); ?>" class="form-control" required>
                        </div>
                    <?php }
                    } ?>
                    <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Update</button>
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
