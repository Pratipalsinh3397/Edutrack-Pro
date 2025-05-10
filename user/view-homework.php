<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsstuid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['upload'])) {
    $hwdescription = $_POST['hwdescription'];
    $hwfile = $_FILES["hwfile"]["name"];
    $stdid = $_SESSION['sturecmsuid'];
    $hwid = intval($_GET['hwid']);

    $extension = substr($hwfile, strlen($hwfile) - 4, strlen($hwfile));
    $allowed_extensions = array(".pdf", "docx", ".doc", ".PDF");

    if (!in_array($extension, $allowed_extensions)) {
      echo "<script>alert('Invalid format. Only pdf / doc format allowed');</script>";
    } else {
      $newhwfile = md5($hwfile) . $extension;
      move_uploaded_file($_FILES["hwfile"]["tmp_name"], "uploadedhw/" . $newhwfile);

      $sql = "INSERT INTO tbluploadedhomeworks(homeworkId, studentId, homeworkDescription, homeworkFile)
              VALUES(:hwid, :stdid, :hwdescription, :newhwfile)";
      $query = $dbh->prepare($sql);
      $query->bindParam(':hwdescription', $hwdescription, PDO::PARAM_STR);
      $query->bindParam(':newhwfile', $newhwfile, PDO::PARAM_STR);
      $query->bindParam(':stdid', $stdid, PDO::PARAM_STR);
      $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
      $query->execute();
      $LastInsertId = $dbh->lastInsertId();

      if ($LastInsertId > 0) {
        echo "<script>alert('Homework uploaded successfully');</script>";
        echo "<script>window.location.href ='homework.php'</script>";
      } else {
        echo "<script>alert('Homework not uploaded');</script>";
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edutrack Pro || View Homework</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS -->
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">

  <style>
    @media (max-width: 768px) {
      .table-responsive {
        display: none;
      }
      .mobile-card {
        display: block;
      }
    }

    @media (min-width: 769px) {
      .mobile-card {
        display: none;
      }
    }

    .info-card {
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .info-label {
      font-weight: bold;
      color: #333;
    }

    .info-value {
      margin-bottom: 10px;
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
          <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="page-title"> View Homework </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Homework</li>
              </ol>
            </nav>
          </div>

          <?php
          $stuclass = $_SESSION['stuclass'];
          $hwid = intval($_GET['hwid']);
          $stdid = $_SESSION['sturecmsuid'];
          $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblhomework.homeworkTitle, tblhomework.homeworkFile, tblhomework.postingDate, tblhomework.lastDateofSubmission, tblhomework.id as hwid, homeworkDescription 
                  FROM tblhomework 
                  JOIN tblclass ON tblclass.ID = tblhomework.classId  
                  WHERE tblhomework.classId = :stuclass AND tblhomework.id = :hwid";
          $query = $dbh->prepare($sql);
          $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
          $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);

          if ($query->rowCount() > 0) {
            foreach ($results as $row) {
              $lds = $row->lastDateofSubmission;
          ?>

              <!-- Desktop Table -->
              <div class="row">
                <div class="col-lg-12 grid-margin stretch-card table-responsive">
                  <table class="table table-bordered">
                    <tr><th>Homework Title</th><td><?php echo htmlentities($row->homeworkTitle); ?></td></tr>
                    <tr><th>Class</th><td><?php echo htmlentities($row->ClassName); ?></td></tr>
                    <tr><th>Section</th><td><?php echo htmlentities($row->Section); ?></td></tr>
                    <tr><th>Homework File</th><td><a href="../teacher/uploadedhw/<?php echo htmlentities($row->homeworkFile); ?>" target="_blank">Click here</a></td></tr>
                    <tr><th>Last Submission Date</th><td><?php echo htmlentities($lds); ?></td></tr>
                    <tr><th>Posting Date</th><td><?php echo htmlentities($row->postingDate); ?></td></tr>
                    <tr><th>Homework Description</th><td><?php echo htmlentities($row->homeworkDescription); ?></td></tr>
                  </table>
                </div>
              </div>

              <!-- Mobile Card View -->
              <div class="mobile-card">
                <div class="info-card">
                  <div class="info-label">Homework Title:</div>
                  <div class="info-value"><?php echo htmlentities($row->homeworkTitle); ?></div>

                  <div class="info-label">Class:</div>
                  <div class="info-value"><?php echo htmlentities($row->ClassName); ?></div>

                  <div class="info-label">Section:</div>
                  <div class="info-value"><?php echo htmlentities($row->Section); ?></div>

                  <div class="info-label">Homework File:</div>
                  <div class="info-value"><a href="../teacher/uploadedhw/<?php echo htmlentities($row->homeworkFile); ?>" target="_blank">Click here</a></div>

                  <div class="info-label">Last Submission Date:</div>
                  <div class="info-value"><?php echo htmlentities($lds); ?></div>

                  <div class="info-label">Posting Date:</div>
                  <div class="info-value"><?php echo htmlentities($row->postingDate); ?></div>

                  <div class="info-label">Homework Description:</div>
                  <div class="info-value"><?php echo htmlentities($row->homeworkDescription); ?></div>
                </div>
              </div>

              <?php
              $ret = $dbh->prepare("SELECT id, homeworkDescription, homeworkFile, postinDate, teacherRemark, teacherRemarkDate FROM tbluploadedhomeworks WHERE homeworkId = :hwid AND studentId = :stdid");
              $ret->bindParam(':hwid', $hwid, PDO::PARAM_STR);
              $ret->bindParam(':stdid', $stdid, PDO::PARAM_STR);
              $ret->execute();
              $rows = $ret->fetchAll(PDO::FETCH_OBJ);

              if ($ret->rowCount() == 0):
                $cdate = date('Y-m-d');
                if ($cdate <= $lds): ?>
                  <form method="post" enctype="multipart/form-data">
                    <div class="card mt-3">
                      <div class="card-body">
                        <h5 class="card-title text-primary">Upload Homework</h5>
                        <div class="form-group">
                          <label>Homework Description</label>
                          <textarea class="form-control" name="hwdescription" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                          <label>Homework File</label>
                          <input type="file" class="form-control" name="hwfile" accept=".doc, .docx, .pdf" required>
                        </div>
                        <button type="submit" name="upload" class="btn btn-primary btn-block">Upload</button>
                      </div>
                    </div>
                  </form>
                <?php else: ?>
                  <div class="alert alert-danger mt-3">Last Submission Date is over. You cannot upload homework.</div>
                <?php endif;
              else:
                foreach ($rows as $row) { ?>
                  <div class="card mt-4">
                    <div class="card-body">
                      <h5 class="card-title text-primary">Uploaded Homework</h5>
                      <p><strong>Description:</strong> <?php echo htmlentities($row->homeworkDescription); ?></p>
                      <p><strong>File:</strong> <a href="uploadedhw/<?php echo htmlentities($row->homeworkFile); ?>" target="_blank">Click here</a></p>
                      <?php if ($row->teacherRemark != '') { ?>
                        <p><strong>Teacher Remark:</strong> <?php echo htmlentities($row->teacherRemark); ?></p>
                        <p><strong>Remark Date:</strong> <?php echo htmlentities($row->teacherRemarkDate); ?></p>
                      <?php } ?>
                    </div>
                  </div>
              <?php }
              endif;
            }
          } else {
            echo '<div class="alert alert-warning">No Homework Found</div>';
          }
          ?>
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
