<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $mttitle = $_POST['materialTitle'];
    $classid = $_POST['classid'];
    $mtdescription = $_POST['materialdescription'];

    $mtfile = $_FILES["mtfile"]["name"];
    $extension = substr($mtfile, strlen($mtfile) - 4, strlen($mtfile));
    $allowed_extensions = array(".pdf", "docx", ".doc", ".PDF");

    $newmtfile = md5($mtfile) . $extension;
    move_uploaded_file($_FILES["mtfile"]["tmp_name"], "uploadedmt/" . $newmtfile);

    if (!in_array($extension, $allowed_extensions)) {
      echo "<script>alert('Invalid format. Only pdf / doc format allowed');</script>";
    } else {
      // Get teacher ID from session
      $teacherId = $_SESSION['sturecmsaid'];

      // SQL query to insert material along with teacherId
      $sql = "insert into tblmaterial(materialTitle, classId, materialDescription, materialFile, teacherId) 
              values(:mttitle, :classid, :mtdescription, :newmtfile, :teacherId)";
      $query = $dbh->prepare($sql);
      $query->bindParam(':mttitle', $mttitle, PDO::PARAM_STR);
      $query->bindParam(':classid', $classid, PDO::PARAM_STR);
      $query->bindParam(':mtdescription', $mtdescription, PDO::PARAM_STR);
      $query->bindParam(':newmtfile', $newmtfile, PDO::PARAM_STR);
      $query->bindParam(':teacherId', $teacherId, PDO::PARAM_STR);  // Bind teacherId

      $query->execute();
      $LastInsertId = $dbh->lastInsertId();
      if ($LastInsertId > 0) {
        echo '<script>alert("Material has been added.")</script>';
        echo "<script>window.location.href ='manage-material.php'</script>";
      } else {
        echo '<script>alert("Something Went Wrong. Please try again")</script>';
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || Add Material</title>
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
</head>

<body>
  <div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include_once('includes/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">Add Material</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Material</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">Add Material</h4>

                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-12 mb-3">
                        <label for="materialTitle">Material Title</label>
                        <input type="text" name="materialTitle" class="form-control" required>
                      </div>

                      <div class="col-12 mb-3">
                        <label for="classid">Material For</label>
                        <select name="classid" class="form-control" required>
                          <option value="">Select Class</option>
                          <?php
                          $sql2 = "SELECT * from tblclass";
                          $query2 = $dbh->prepare($sql2);
                          $query2->execute();
                          $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                          foreach ($result2 as $row1) {
                          ?>
                            <option value="<?php echo htmlentities($row1->ID); ?>">
                              <?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="col-12 mb-3">
                        <label for="materialdescription">Material Description</label>
                        <textarea name="materialdescription" class="form-control" rows="8" required></textarea>
                      </div>

                      <div class="col-12 mb-3">
                        <label for="mtfile">Material File (doc or pdf only)</label>
                        <input type="file" name="mtfile" class="form-control" accept=".doc, .docx, .pdf" required>
                      </div>

                      <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100" name="submit">Add</button>
                      </div>
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
