<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $classid = $_POST['classid'];
    $notmsg = $_POST['notmsg'];
    $eid = $_GET['editid'];
    $ntfile = $_FILES["ntfile"]["name"];

    if (!empty($ntfile)) {
      $extension = strtolower(pathinfo($ntfile, PATHINFO_EXTENSION));
      $allowed_extensions = array("pdf", "docx", "doc");

      if (!in_array($extension, $allowed_extensions)) {
        echo "<script>alert('Invalid format. Only PDF, DOC, or DOCX allowed');</script>";
        exit();
      }

      $newntfile = uniqid() . '.' . $extension;
      move_uploaded_file($_FILES["ntfile"]["tmp_name"], "uploadednt/" . $newntfile);

      $sql = "UPDATE tblnotice SET NoticeTitle=:nottitle, ClassId=:classid, NoticeMsg=:notmsg, noticeFile=:newntfile WHERE ID=:eid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':newntfile', $newntfile, PDO::PARAM_STR);
    } else {
      $sql = "UPDATE tblnotice SET NoticeTitle=:nottitle, ClassId=:classid, NoticeMsg=:notmsg WHERE ID=:eid";
      $query = $dbh->prepare($sql);
    }

    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Notice has been updated")</script>';
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || Update Notice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .form-group label {
      font-weight: 600;
    }
    @media (max-width: 768px) {
      .card-body {
        padding: 1rem;
      }
      .page-title {
        font-size: 1.25rem;
      }
      .form-control {
        font-size: 0.9rem;
      }
      .btn {
        width: 100%;
        margin-top: 1rem;
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
            <h3 class="page-title">Update Notice</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Notice</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card w-100">
                <div class="card-body">
                  <h4 class="card-title text-center">Update Notice</h4>
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <?php
                    $eid = $_GET['editid'];
                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, tblnotice.CreationDate, tblnotice.ClassId, tblnotice.noticeFile, tblnotice.NoticeMsg, tblnotice.ID as nid FROM tblnotice JOIN tblclass ON tblclass.ID = tblnotice.ClassId WHERE tblnotice.ID = :eid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                      foreach ($results as $row) {
                    ?>
                      <div class="form-group">
                        <label>Notice Title</label>
                        <input type="text" name="nottitle" value="<?php echo htmlentities($row->NoticeTitle); ?>" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Notice For</label>
                        <select name="classid" class="form-control">
                          <option value="<?php echo htmlentities($row->ClassId); ?>">
                            <?php echo htmlentities($row->ClassName . " " . $row->Section); ?>
                          </option>
                          <?php
                          $sql2 = "SELECT * FROM tblclass";
                          $query2 = $dbh->prepare($sql2);
                          $query2->execute();
                          $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                          foreach ($result2 as $row1) {
                          ?>
                            <option value="<?php echo htmlentities($row1->ID); ?>">
                              <?php echo htmlentities($row1->ClassName . " " . $row1->Section); ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Notice Message</label>
                        <textarea name="notmsg" class="form-control" rows="5" required><?php echo htmlentities($row->NoticeMsg); ?></textarea>
                      </div>
                      <div class="form-group">
                        <label>File</label>
                        <input type="file" name="ntfile" class="form-control" accept=".pdf, .doc, .docx">
                        <?php if ($row->noticeFile) { ?>
                          <p>Current File:
                            <a href="uploadednt/<?php echo htmlentities($row->noticeFile); ?>" target="_blank">View File</a>
                          </p>
                        <?php } ?>
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