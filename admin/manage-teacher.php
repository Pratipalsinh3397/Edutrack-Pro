<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  // Code for deletion
  if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "delete from tblteacher where ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <title>Edutrack Pro || Manage Teachers</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
      .card-title {
        font-size: 1.1rem;
        font-weight: 600;
      }

      .card p {
        font-size: 0.9rem;
      }

      @media (max-width: 768px) {
        .pagination {
          flex-wrap: wrap;
        }

        .pagination .page-item {
          margin-bottom: 5px;
        }
      }

      .border-left-primary {
        border-left: 4px solid #007bff !important;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
              <h3 class="page-title"> Manage Teachers </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Teachers</li>
                </ol>
              </nav>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-sm-flex align-items-center mb-4">
                      <h4 class="card-title mb-sm-0">Manage Teachers</h4>
                      <a href="#" class="text-dark ml-auto mb-3 mb-sm-0">View all Teachers</a>
                    </div>

                    <?php
                    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                      $page_no = $_GET['page_no'];
                    } else {
                      $page_no = 1;
                    }

                    $total_records_per_page = 10;
                    $offset = ($page_no - 1) * $total_records_per_page;
                    $previous_page = $page_no - 1;
                    $next_page = $page_no + 1;

                    $ret = "SELECT ID FROM tblteacher";
                    $query1 = $dbh->prepare($ret);
                    $query1->execute();
                    $total_records = $query1->rowCount();
                    $total_no_of_pages = ceil($total_records / $total_records_per_page);
                    $second_last = $total_no_of_pages - 1;

                    $sql = "SELECT tblteacher.TeacherID, tblteacher.ID as sid, tblteacher.TeacherName, tblteacher.Email, tblteacher.TeacherRegdate, tblclass.ClassName, tblclass.Section 
                            FROM tblteacher 
                            JOIN tblclass ON tblclass.ID=tblteacher.TeacherClass 
                            LIMIT $offset, $total_records_per_page";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    ?>

                    <!-- Desktop Table View -->
                    <div class="table-responsive border rounded p-1 d-none d-md-block">
                      <table class="table table-striped table-hover small">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Teacher ID</th>
                            <th>Teacher Class</th>
                            <th>Teacher Name</th>
                            <th>Teacher Email</th>
                            <th>Joining Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $cnt = 1;
                          if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                          ?>
                              <tr>
                                <td><?php echo htmlentities($cnt); ?></td>
                                <td><?php echo htmlentities($row->TeacherID); ?></td>
                                <td><?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></td>
                                <td><?php echo htmlentities($row->TeacherName); ?></td>
                                <td><?php echo htmlentities($row->Email); ?></td>
                                <td><?php echo htmlentities($row->TeacherRegdate); ?></td>
                                <td>
                                  <a href="edit-teacher-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm" target="_blank">Edit</a>
                                  <a href="manage-teacher.php?delid=<?php echo ($row->sid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                              </tr>
                          <?php $cnt++;
                            }
                          } ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-block d-md-none">
                      <?php
                      $cnt = 1;
                      if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                      ?>
                          <div class="card mb-3 shadow-sm border border-left-primary">
                            <div class="card-body">
                              <h5 class="card-title text-primary"><?php echo htmlentities($row->TeacherName); ?></h5>
                              <p class="mb-1"><strong>ID:</strong> <?php echo htmlentities($row->TeacherID); ?></p>
                              <p class="mb-1"><strong>Class:</strong> <?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></p>
                              <p class="mb-1"><strong>Email:</strong> <?php echo htmlentities($row->Email); ?></p>
                              <p class="mb-2"><strong>Joined:</strong> <?php echo htmlentities($row->TeacherRegdate); ?></p>

                              <a href="edit-teacher-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm" target="_blank">Edit</a>
                              <a href="manage-teacher.php?delid=<?php echo ($row->sid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>

                            </div>
                          </div>
                      <?php $cnt++;
                        }
                      } ?>
                    </div>

                    <!-- Pagination -->
                    <div align="left" class="mt-3">
                      <ul class="pagination">
                        <li class="page-item <?php if ($page_no <= 1) echo 'disabled'; ?>">
                          <a class="page-link" <?php if ($page_no > 1) echo "href='?page_no=$previous_page'"; ?>>Previous</a>
                        </li>
                        <?php
                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                          echo $counter == $page_no
                            ? "<li class='page-item active'><a class='page-link'>$counter</a></li>"
                            : "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                        }
                        ?>
                        <li class="page-item <?php if ($page_no >= $total_no_of_pages) echo 'disabled'; ?>">
                          <a class="page-link" <?php if ($page_no < $total_no_of_pages) echo "href='?page_no=$next_page'"; ?>>Next</a>
                        </li>
                        <?php if ($page_no < $total_no_of_pages) {
                          echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                        } ?>
                      </ul>
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