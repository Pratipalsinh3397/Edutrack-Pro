<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsstuid']) == 0) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $studentid = $_SESSION['sturecmsstuid'];
    $teacherid = $_POST['teacher'];
    $feedback = $_POST['feedback'];

    $sql = "INSERT INTO tblfeedback (studentid, teacherid, feedbacktext) VALUES (:studentid, :teacherid, :feedback)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
    $query->bindParam(':feedback', $feedback, PDO::PARAM_STR);

    if ($query->execute()) {
      $msg = "Feedback submitted successfully.";
    } else {
      $error = "Something went wrong. Please try again.";
    }
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edutrack Pro || Give Feedback</title>
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">

    <style>
      select,
      select option {
        color: black;
        background-color: white;
      }

      option {
        color: black;
      }

      select:focus {
        border-color: #0a58ca;
        box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.5);
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
              <h3 class="page-title">Give Feedback</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Feedback</li>
                </ol>
              </nav>
            </div>

            <?php if (isset($msg)) { ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php } ?>
            <?php if (isset($error)) { ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php } ?>

            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <form method="POST">
                      <div class="form-group mb-3">
                        <label for="teacher">Select Teacher</label>
                        <select name="teacher" class="form-control" required>
                          <option value="" style="color: black;">Choose a teacher</option>
                          <?php
                          $sql = "SELECT ID, TeacherName FROM tblteacher";
                          $query = $dbh->prepare($sql);
                          $query->execute();
                          $teachers = $query->fetchAll(PDO::FETCH_OBJ);
                          foreach ($teachers as $teacher) {
                            echo '<option value="' . $teacher->ID . '">' . htmlentities($teacher->TeacherName) . '</option>';
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group mb-3">
                        <label for="feedback">Feedback</label>
                        <select name="feedback" class="form-control" required>
                          <option value="">Select Feedback</option>
                          <option value="Excellent Teaching">Excellent Teaching</option>
                          <option value="Very Helpful">Very Helpful</option>
                          <option value="Needs Improvement">Needs Improvement</option>
                          <option value="Too Fast in Class">Too Fast in Class</option>
                          <option value="Very Clear Explanation">Very Clear Explanation</option>
                        </select>
                      </div>

                      <!-- Responsive Button Group -->
                      <!-- Responsive Button Group with Spacing -->
                      <div class="form-group mt-3">
                        <div class="d-sm-flex">
                          <button type="submit" name="submit" class="btn btn-success mb-2 mb-sm-0 mr-sm-2 w-100 w-sm-auto">Submit Feedback</button>
                          <a href="dashboard.php" class="btn btn-secondary w-100 w-sm-auto">Cancel</a>
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
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
  </body>

  </html>
<?php } ?>