<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $notmsg = $_POST['notmsg'];
    $eid = $_GET['editid'];

    $sql = "update tblpublicnotice set NoticeTitle=:nottitle, NoticeMessage=:notmsg where ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Notice has been updated")</script>';
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edutrack Pro || Update Notice</title>

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    
    <!-- Main Layout Style -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- Custom Responsive Styles -->
    <style>
      @media screen and (max-width: 768px) {
        .page-title {
          font-size: 20px;
          text-align: center;
        }

        .form-group label {
          font-size: 14px;
        }

        .form-control {
          font-size: 14px;
        }

        .btn {
          width: 100%;
        }

        .card-body {
          padding: 1rem;
        }
      }

      textarea.form-control {
        resize: vertical;
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
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Update Notice</h4>

                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <?php
                      $eid = $_GET['editid'];
                      $sql = "SELECT * from tblpublicnotice where ID=:eid";
                      $query = $dbh->prepare($sql);
                      $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                      ?>
                          <div class="form-group">
                            <label for="nottitle">Notice Title</label>
                            <input type="text" name="nottitle" value="<?php echo htmlentities($row->NoticeTitle); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label for="notmsg">Notice Message</label>
                            <textarea name="notmsg" class="form-control" rows="5" required><?php echo htmlentities($row->NoticeMessage); ?></textarea>
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

    <!-- JavaScript Plugins -->
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
