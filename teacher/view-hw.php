<?php session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['upload'])) {
    $tremark = $_POST['teacherremark'];
    $hwid = intval($_GET['hwid']);
    $stdid = $_GET['stid'];

    $sql = "UPDATE tbluploadedhomeworks SET teacherRemark=:tremark WHERE homeworkId=:hwid AND studentId=:stdid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':tremark', $tremark, PDO::PARAM_STR);
    $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
    $query->bindParam(':stdid', $stdid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Teacher Remark Updated successfully');</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || View Homework</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- Layout styles -->
  <link rel="stylesheet" href="css/style.css" />
  <style>
    @media (max-width: 767px) {
      .table-responsive {
        border: none;
      }

      .table-mobile-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        padding: 15px;
      }

      .table-mobile-card h6 {
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
      }

      .table-mobile-card p {
        margin-bottom: 10px;
        font-size: 14px;
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
            <h3 class="page-title">View Homework</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Homework</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">

                  <!-- Responsive Table/Card View -->
                  <div class="table-responsive d-none d-md-block">
                    <table class="table table-bordered">
                      <?php
                      $hwid = intval($_GET['hwid']);
                      $stdid = $_GET['stid'];

                      $sql = "SELECT StudentName,StudentEmail FROM tblstudent WHERE ID='$stdid'";
                      $query = $dbh->prepare($sql);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);

                      foreach ($results as $row) { ?>
                        <tr><th>Student Name</th><td><?php echo htmlentities($row->StudentName); ?></td></tr>
                        <tr><th>Student Email</th><td><?php echo htmlentities($row->StudentEmail); ?></td></tr>
                      <?php }

                      $sql = "SELECT tblclass.ID,tblclass.ClassName,tblclass.Section,tblhomework.homeworkTitle,tblhomework.postingDate,tblhomework.lastDateofSubmission,tblhomework.id as hwid,homeworkDescription FROM tblhomework JOIN tblclass ON tblclass.ID=tblhomework.classId WHERE tblhomework.id=:hwid";
                      $query = $dbh->prepare($sql);
                      $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);

                      if ($query->rowCount() > 0) {
                        foreach ($results as $row) { ?>
                          <tr><th>Homework Title</th><td><?php echo htmlentities($row->homeworkTitle); ?></td></tr>
                          <tr><th>Class</th><td><?php echo htmlentities($row->ClassName); ?></td></tr>
                          <tr><th>Section</th><td><?php echo htmlentities($row->Section); ?></td></tr>
                          <tr><th>Last Submission Date</th><td><?php echo htmlentities($row->lastDateofSubmission); ?></td></tr>
                          <tr><th>Posting Date</th><td><?php echo htmlentities($row->postingDate); ?></td></tr>
                          <tr><th>Homework Description</th><td><?php echo htmlentities($row->homeworkDescription); ?></td></tr>

                          <?php
                          $ret = $dbh->prepare("SELECT id,homeworkDescription,homeworkFile,postinDate,teacherRemark,teacherRemarkDate FROM tbluploadedhomeworks WHERE homeworkId=:hwid AND studentId=:stdid");
                          $ret->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                          $ret->bindParam(':stdid', $stdid, PDO::PARAM_STR);
                          $ret->execute();
                          $rows = $ret->fetchAll(PDO::FETCH_OBJ);

                          if ($ret->rowCount() == 0) {
                            echo '<tr><th colspan="2" style="color:red">Homework not uploaded by the student</th></tr>';
                          } else {
                            foreach ($rows as $row) {
                              echo '<tr><th colspan="2" style="color:blue">Uploaded Homework</th></tr>';
                              echo '<tr><th>Homework Description</th><td>' . htmlentities($row->homeworkDescription) . '</td></tr>';
                              echo '<tr><th>Homework File</th><td><a href="../user/uploadedhw/' . htmlentities($row->homeworkFile) . '" target="_blank">Click here</a></td></tr>';

                              if ($row->teacherRemark == '') {
                          ?>
                                <form method="post">
                                  <tr><th>Teacher Remark</th><td><textarea class="form-control" name="teacherremark" required></textarea></td></tr>
                                  <tr><td colspan="2"><input type="submit" name="upload" class="btn btn-primary" value="Upload"></td></tr>
                                </form>
                              <?php } else { ?>
                                <tr><th>Teacher Remark</th><td><?php echo htmlentities($row->teacherRemark); ?></td></tr>
                                <tr><th>Remark Date</th><td><?php echo htmlentities($row->teacherRemarkDate); ?></td></tr>
                      <?php }
                            }
                          }
                        }
                      } else {
                        echo '<tr><th colspan="2" style="color:red;">No Homework Found</th></tr>';
                      } ?>
                    </table>
                  </div>

                  <!-- Mobile Card View -->
                  <div class="d-md-none">
                    <?php
                    $hwid = intval($_GET['hwid']);
                    $stdid = $_GET['stid'];

                    $sql = "SELECT StudentName,StudentEmail FROM tblstudent WHERE ID='$stdid'";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    foreach ($results as $row) {
                      echo '<div class="table-mobile-card"><h6>Student Name</h6><p>' . htmlentities($row->StudentName) . '</p>';
                      echo '<h6>Student Email</h6><p>' . htmlentities($row->StudentEmail) . '</p></div>';
                    }

                    $sql = "SELECT tblclass.ClassName,tblclass.Section,tblhomework.homeworkTitle,tblhomework.postingDate,tblhomework.lastDateofSubmission,homeworkDescription FROM tblhomework JOIN tblclass ON tblclass.ID=tblhomework.classId WHERE tblhomework.id=:hwid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    foreach ($results as $row) {
                      echo '<div class="table-mobile-card">';
                      echo '<h6>Homework Title</h6><p>' . htmlentities($row->homeworkTitle) . '</p>';
                      echo '<h6>Class</h6><p>' . htmlentities($row->ClassName) . '</p>';
                      echo '<h6>Section</h6><p>' . htmlentities($row->Section) . '</p>';
                      echo '<h6>Last Date</h6><p>' . htmlentities($row->lastDateofSubmission) . '</p>';
                      echo '<h6>Posting Date</h6><p>' . htmlentities($row->postingDate) . '</p>';
                      echo '<h6>Description</h6><p>' . htmlentities($row->homeworkDescription) . '</p>';
                      echo '</div>';

                      $ret = $dbh->prepare("SELECT homeworkDescription,homeworkFile,teacherRemark,teacherRemarkDate FROM tbluploadedhomeworks WHERE homeworkId=:hwid AND studentId=:stdid");
                      $ret->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                      $ret->bindParam(':stdid', $stdid, PDO::PARAM_STR);
                      $ret->execute();
                      $rows = $ret->fetchAll(PDO::FETCH_OBJ);

                      if ($ret->rowCount() == 0) {
                        echo '<div class="table-mobile-card"><p style="color:red">Homework not uploaded by the student.</p></div>';
                      } else {
                        foreach ($rows as $row) {
                          echo '<div class="table-mobile-card">';
                          echo '<h6>Uploaded Homework</h6>';
                          echo '<p><strong>Description:</strong> ' . htmlentities($row->homeworkDescription) . '</p>';
                          echo '<p><a href="../user/uploadedhw/' . htmlentities($row->homeworkFile) . '" target="_blank">View File</a></p>';
                          if ($row->teacherRemark == '') {
                    ?>
                            <form method="post" class="mt-2">
                              <div class="form-group">
                                <label for="teacherremark">Teacher Remark</label>
                                <textarea class="form-control" name="teacherremark" required></textarea>
                              </div>
                              <button type="submit" name="upload" class="btn btn-primary btn-sm">Upload</button>
                            </form>
                          <?php } else {
                            echo '<p><strong>Teacher Remark:</strong> ' . htmlentities($row->teacherRemark) . '</p>';
                            echo '<p><strong>Date:</strong> ' . htmlentities($row->teacherRemarkDate) . '</p>';
                          }
                          echo '</div>';
                        }
                      }
                    }
                    ?>
                  </div>

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
