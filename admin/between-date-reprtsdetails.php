<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "delete from tblstudent where ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'manage-students.php'</script>";
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <title>Edutrack Pro || Between Dates Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="./css/style.css">
  </head>

  <body>
    <div class="container-scroller">
      <?php include_once('includes/header.php'); ?>
      <div class="container-fluid page-body-wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Between Dates Reports of Students</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Reports</li>
                </ol>
              </nav>
            </div>

            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card w-100">
                  <div class="card-body">
                    <div class="text-center mb-4">
                      <?php
                      $fdate = $_POST['fromdate'];
                      $tdate = $_POST['todate'];
                      ?>
                      <h5 style="color:blue;">Students Report from <?php echo $fdate ?> to <?php echo $tdate ?></h5>
                    </div>

                    <div class="table-responsive border rounded p-2">
                      <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                          <tr>
                            <th>S.No</th>
                            <th>Student ID</th>
                            <th>Class</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Admission Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (isset($_GET['pageno'])) {
                            $pageno = $_GET['pageno'];
                          } else {
                            $pageno = 1;
                          }

                          $no_of_records_per_page = 5;
                          $offset = ($pageno - 1) * $no_of_records_per_page;

                          $ret = "SELECT ID FROM tblstudent";
                          $query1 = $dbh->prepare($ret);
                          $query1->execute();
                          $total_rows = $query1->rowCount();
                          $total_pages = ceil($total_rows / $no_of_records_per_page);

                          $sql = "SELECT tblstudent.StuID,tblstudent.ID as sid,tblstudent.StudentName,tblstudent.StudentEmail,tblstudent.DateofAdmission,tblclass.ClassName,tblclass.Section 
                                  FROM tblstudent 
                                  JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                  WHERE date(tblstudent.DateofAdmission) BETWEEN '$fdate' AND '$tdate' 
                                  LIMIT $offset, $no_of_records_per_page";

                          $query = $dbh->prepare($sql);
                          $query->execute();
                          $results = $query->fetchAll(PDO::FETCH_OBJ);
                          $cnt = 1;

                          if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                          ?>
                              <tr>
                                <td><?php echo htmlentities($cnt); ?></td>
                                <td><?php echo htmlentities($row->StuID); ?></td>
                                <td><?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></td>
                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                <td>
                                  <a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm" target="_blank">Edit</a>
                                  <a href="manage-students.php?delid=<?php echo htmlentities($row->sid); ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                              </tr>
                            <?php $cnt++;
                            }
                          } else { ?>
                            <tr>
                              <td colspan="7" class="text-center">No Records Found</td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                      <!-- <nav>
                      <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">
                          <a class="page-link" href="?pageno=1">First</a>
                        </li>
                        <li class="page-item <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">
                          <a class="page-link" href="<?php echo ($pageno > 1) ? "?pageno=" . ($pageno - 1) : '#'; ?>">Prev</a>
                        </li>
                        <li class="page-item <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">
                          <a class="page-link" href="<?php echo ($pageno < $total_pages) ? "?pageno=" . ($pageno + 1) : '#'; ?>">Next</a>
                        </li>
                        <li class="page-item">
                          <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                        </li>
                      </ul>
                    </nav> -->
                      <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">

                          <!-- First Page -->
                          <li class="page-item <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pageno=1" tabindex="-1" aria-disabled="<?php echo ($pageno <= 1) ? 'true' : 'false'; ?>">First</a>
                          </li>

                          <!-- Previous Page -->
                          <li class="page-item <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo ($pageno > 1) ? '?pageno=' . ($pageno - 1) : '#'; ?>" aria-label="Previous">
                              <span aria-hidden="true">&laquo; Prev</span>
                            </a>
                          </li>

                          <!-- Next Page -->
                          <li class="page-item <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo ($pageno < $total_pages) ? '?pageno=' . ($pageno + 1) : '#'; ?>" aria-label="Next">
                              <span aria-hidden="true">Next &raquo;</span>
                            </a>
                          </li>

                          <!-- Last Page -->
                          <li class="page-item <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                          </li>

                        </ul>
                      </nav>

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
    <script src="./vendors/chart.js/Chart.min.js"></script>
    <script src="./vendors/moment/moment.min.js"></script>
    <script src="./vendors/daterangepicker/daterangepicker.js"></script>
    <script src="./vendors/chartist/chartist.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="./js/dashboard.js"></script>
  </body>

  </html>
<?php } ?>