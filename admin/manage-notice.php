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
    $sql = "DELETE FROM tblnotice WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'manage-notice.php'</script>";
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || Manage Notice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
  <link rel="stylesheet" href="./css/style.css">

  <style>
    @media (max-width: 768px) {
      .border-left-primary {
        border-left: 4px solid #007bff !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        background-color: #fff;
      }

      .card-body {
        padding: 1rem;
      }

      .card-title {
        font-size: 1.2rem;
        font-weight: 600;
      }

      .btn-sm {
        font-size: 0.85rem;
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
            <h3 class="page-title"> Manage Notice </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Notice</li>
              </ol>
            </nav>
          </div>

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-sm-flex align-items-center mb-4">
                    <h4 class="card-title mb-sm-0">Manage Notice</h4>
                    <a href="#" class="text-dark ms-auto mb-3 mb-sm-0">View all Notice</a>
                  </div>

                  <?php
                  $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                  $no_of_records_per_page = 15;
                  $offset = ($pageno - 1) * $no_of_records_per_page;

                  $query1 = $dbh->prepare("SELECT ID FROM tblnotice");
                  $query1->execute();
                  $total_rows = $query1->rowCount();
                  $total_pages = ceil($total_rows / $no_of_records_per_page);

                  $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, tblnotice.CreationDate, tblnotice.ClassId, tblnotice.ID as nid 
                          FROM tblnotice 
                          JOIN tblclass ON tblclass.ID = tblnotice.ClassId 
                          ORDER BY tblnotice.ID DESC
                          LIMIT $offset, $no_of_records_per_page";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  ?>

                  <!-- Desktop Table View -->
                  <div class="table-responsive d-none d-md-block border rounded p-1">
                    <table class="table table-striped table-hover small">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Notice Title</th>
                          <th>Class</th>
                          <th>Section</th>
                          <th>Notice Date</th>
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
                              <td><?= htmlentities($cnt); ?></td>
                              <td><?= htmlentities($row->NoticeTitle); ?></td>
                              <td><?= htmlentities($row->ClassName); ?></td>
                              <td><?= htmlentities($row->Section); ?></td>
                              <td><?= htmlentities($row->CreationDate); ?></td>
                              <td>
                                <a href="edit-notice-detail.php?editid=<?= htmlentities($row->nid); ?>" class="btn btn-info btn-sm" target="_blank">Edit</a>
                                <a href="manage-notice.php?delid=<?= $row->nid; ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                              </td>
                            </tr>
                        <?php $cnt++;
                          }
                        } else {
                          echo '<tr><td colspan="6" class="text-danger text-center">No Record Found</td></tr>';
                        }
                        ?>
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
                            <h5 class="card-title text-primary"><?= htmlentities($row->NoticeTitle); ?></h5>
                            <p class="mb-2"> <strong>Class:</strong> <?= htmlentities($row->ClassName); ?> (<?= htmlentities($row->Section); ?>)</p>
                            <p class="mb-2"> <strong>Date:</strong> <?= htmlentities($row->CreationDate); ?></p>
                            <div class="d-flex gap-2">
                              <a href="edit-notice-detail.php?editid=<?= htmlentities($row->nid); ?>" class="btn btn-sm btn-info" target="_blank">Edit</a>
                              <a href="manage-notice.php?delid=<?= $row->nid; ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-sm btn-danger">Delete</a>
                            </div>
                          </div>
                        </div>
                    <?php $cnt++;
                      }
                    } else {
                      echo '<div class="alert alert-warning text-center">No notices found.</div>';
                    }
                    ?>
                  </div>

                  <!-- Pagination -->
                  <div class="d-flex flex-wrap justify-content-start mt-4">
                    <ul class="pagination">
                      <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                      <li class="page-item <?= ($pageno <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= ($pageno <= 1) ? '#' : '?pageno=' . ($pageno - 1); ?>">Prev</a>
                      </li>
                      <li class="page-item <?= ($pageno >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= ($pageno >= $total_pages) ? '#' : '?pageno=' . ($pageno + 1); ?>">Next</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="?pageno=<?= $total_pages; ?>">Last</a></li>
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

  <!-- JS Scripts -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="./vendors/chart.js/Chart.min.js"></script>
  <script src="./vendors/moment/moment.min.js"></script>
  <script src="./vendors/daterangepicker/daterangepicker.js"></script>
  <script src="./vendors/chartist/chartist.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="./js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
