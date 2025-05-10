<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblclass WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'manage-class.php'</script>";
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <title>Edutrack Pro || Manage Class</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css" />

    <style>
      @media (max-width: 767.98px) {
        .desktop-table {
          display: none;
        }

        .mobile-card {
          display: block;
        }

        .card-block {
          /* border: 1px solid #ddd; */
          /* border-radius: 10px; */
          padding: 15px;
          margin-bottom: 15px;
          box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        .card-block h5 {
          margin-bottom: 10px;
          font-size: 16px;
          font-weight: 600;
        }

        .card-block p {
          margin-bottom: 2px;
          font-size: 14px;
        }

        .pagination .page-link {
          padding: 0.4rem 0.6rem;
          font-size: 0.85rem;
        }

        .border-left-primary {
                border-left: 4px solid #007bff !important;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }
        
      }

      @media (min-width: 768px) {
        .mobile-card {
          display: none;
        }

        .desktop-table {
          display: block;
        }

        .border-left-primary {
                border-left: 4px solid #007bff !important;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
              <h3 class="page-title">Manage Class</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Class</li>
                </ol>
              </nav>
            </div>

            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-sm-flex align-items-center mb-4">
                      <h4 class="card-title mb-sm-0">Manage Class</h4>
                      <a href="#" class="text-dark ml-auto mb-3 mb-sm-0">View all Classes</a>
                    </div>

                    <!-- Desktop Table -->
                    <div class="table-responsive border rounded p-1 desktop-table">
                      <table class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Class Name</th>
                            <th>Section</th>
                            <th>Total Fees</th>
                            <th>Creation Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                          $no_of_records_per_page = 15;
                          $offset = ($pageno - 1) * $no_of_records_per_page;

                          $ret = "SELECT ID FROM tblclass";
                          $query1 = $dbh->prepare($ret);
                          $query1->execute();
                          $total_rows = $query1->rowCount();
                          $total_pages = ceil($total_rows / $no_of_records_per_page);

                          $sql = "SELECT * FROM tblclass LIMIT $offset, $no_of_records_per_page";
                          $query = $dbh->prepare($sql);
                          $query->execute();
                          $results = $query->fetchAll(PDO::FETCH_OBJ);

                          $cnt = 1;
                          foreach ($results as $row) {
                          ?>
                            <tr>
                              <td><?php echo htmlentities($cnt); ?></td>
                              <td><?php echo htmlentities($row->ClassName); ?></td>
                              <td><?php echo htmlentities($row->Section); ?></td>
                              <td><?php echo htmlentities($row->fees); ?></td>
                              <td><?php echo htmlentities($row->CreationDate); ?></td>
                              <td>
                                <div>
                                  <a href="edit-class-detail.php?editid=<?php echo htmlentities($row->ID); ?>" class="btn btn-info btn-sm">Edit</a>
                                  <a href="manage-class.php?delid=<?php echo htmlentities($row->ID); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                </div>

                              </td>
                            </tr>
                          <?php $cnt++;
                          } ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="mobile-card">
                      <?php
                      $cnt = 1;
                      foreach ($results as $row) {
                      ?>
                        <div class="card mb-3 p-3 shadow-sm border border-left-primary">
                          <h5 class=" card-title text-primary"><strong>Class Name:</strong> <?php echo htmlentities($row->ClassName); ?></h5>
                          <p><strong>Section:</strong> <?php echo htmlentities($row->Section); ?></p>
                          <p><strong>Fees:</strong> <?php echo htmlentities($row->fees); ?></p>
                          <p><strong>Created:</strong> <?php echo htmlentities($row->CreationDate); ?></p>
                          <div>
                            <a href="edit-class-detail.php?editid=<?php echo htmlentities($row->ID); ?>" class="btn btn-info btn-sm ">Edit</a>
                            <a href="manage-class.php?delid=<?php echo htmlentities($row->ID); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                          </div>

                        </div>
                      <?php $cnt++;
                      } ?>
                    </div>

                    <!-- Pagination -->
                    <nav class="mt-4">
                      <ul class="pagination flex-wrap">
                        <li class="page-item <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">
                          <a class="page-link" href="?pageno=1">First</a>
                        </li>
                        <li class="page-item <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>">
                          <a class="page-link" href="<?php echo ($pageno <= 1) ? '#' : '?pageno=' . ($pageno - 1); ?>">Prev</a>
                        </li>
                        <li class="page-item <?php echo ($pageno >= $total_pages) ? 'disabled' : ''; ?>">
                          <a class="page-link" href="<?php echo ($pageno >= $total_pages) ? '#' : '?pageno=' . ($pageno + 1); ?>">Next</a>
                        </li>
                        <li class="page-item">
                          <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                        </li>
                      </ul>
                    </nav>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php include_once('includes/footer.php'); ?>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
  </body>

  </html>
<?php } ?>