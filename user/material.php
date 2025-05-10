<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsstuid'] == 0)) {
  header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || Material</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

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
    .material-card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .material-card h6 {
      font-weight: 600;
      margin-bottom: 4px;
    }

    .mobile-card {
      display: none;
    }

    @media (max-width: 767.98px) {
      .desktop-table {
        display: none;
      }

      .mobile-card {
        display: block;
      }
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include_once('includes/header.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php'); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">View Material</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Material</li>
              </ol>
            </nav>
          </div>

          <?php
          $stuclass = $_SESSION['stuclass'];
          // Modified SQL query to include teacher's name
          $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblmaterial.materialTitle, tblmaterial.postingDate, tblmaterial.id as mtid, tblteacher.TeacherName 
                  FROM tblmaterial 
                  JOIN tblclass ON tblclass.ID = tblmaterial.classId  
                  JOIN tblteacher ON tblteacher.ID = tblmaterial.teacherId
                  WHERE tblmaterial.classId = :stuclass";
          $query = $dbh->prepare($sql);
          $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);
          ?>

          <!-- Desktop Table View -->
          <div class="row desktop-table">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead class="thead-light">
                        <tr>
                          <th>S.No</th>
                          <th>Material Title</th>
                          <th>Posting Date</th>
                          <th>Teacher's Name</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                          foreach ($results as $row) { ?>
                            <tr>
                              <td><?php echo htmlentities($cnt); ?></td>
                              <td><?php echo htmlentities($row->materialTitle); ?></td>
                              <td><?php echo htmlentities($row->postingDate); ?></td>
                              <td><?php echo htmlentities($row->TeacherName); ?></td>
                              <td>
                                <a href="view-material.php?mtid=<?php echo htmlentities($row->mtid); ?>" class="btn btn-sm btn-primary" target="_blank">View</a>
                              </td>
                            </tr>
                          <?php $cnt++;
                          }
                        } else { ?>
                          <tr>
                            <td colspan="5" class="text-center text-danger">No Material Found</td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Mobile Card View -->
          <div class="row mobile-card">
            <div class="col-12">
              <?php
              $cnt = 1;
              if ($query->rowCount() > 0) {
                foreach ($results as $row) { ?>
                  <div class="material-card">
                    <h6>S.No:</h6>
                    <p><?php echo htmlentities($cnt); ?></p>
                    <h6>Material Title:</h6>
                    <p><?php echo htmlentities($row->materialTitle); ?></p>
                    <h6>Posting Date:</h6>
                    <p><?php echo htmlentities($row->postingDate); ?></p>
                    <h6>Teacher's Name:</h6>
                    <p><?php echo htmlentities($row->TeacherName); ?></p>
                    <a href="view-material.php?mtid=<?php echo htmlentities($row->mtid); ?>" class="btn btn-sm btn-primary w-100" target="_blank">View</a>
                  </div>
              <?php $cnt++;
                }
              } else { ?>
                <div class="text-center text-danger">No Material Found</div>
              <?php } ?>
            </div>
          </div>

        </div>
        <?php include_once('includes/footer.php'); ?>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- Plugin js for this page -->
  <script src="vendors/select2/select2.min.js"></script>
  <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <!-- Custom js for this page -->
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
</body>

</html>
<?php } ?>
