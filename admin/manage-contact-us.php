<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblcontactus WHERE id=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'manage-contact-us.php'</script>";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edutrack Pro || Manage Contact Us</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
        border: 1px solid #ddd;
        /* border-radius: 10px; */
        /* padding: 15px; */
        margin-bottom: 15px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
      }
      .card-block p {
        margin-bottom: 5px;
        font-size: 14px;
      }
      .border-left-primary {
                border-left: 4px solid #007bff !important;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }
    }

    @media (min-width: 768px) {
      .desktop-table {
        display: block;
      }
      .mobile-card {
        display: none;
      }
      .border-left-primary {
                border-left: 4px solid #007bff !important;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }
    }

    .btn-space {
      margin-right: 10px;
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
            <h3 class="page-title">Manage Contact Us</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Contact Us</li>
              </ol>
            </nav>
          </div>

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4">All Contact Messages</h4>

                  <!-- Desktop Table View -->
                  <div class="table-responsive border rounded p-1 desktop-table">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Full Name</th>
                          <th>Phone No.</th>
                          <th>Email</th>
                          <th>Subject</th>
                          <th>Message</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                          $no_of_records_per_page = 15;
                          $offset = ($pageno - 1) * $no_of_records_per_page;

                          $ret = "SELECT ID FROM tblcontactus";
                          $query1 = $dbh->prepare($ret);
                          $query1->execute();
                          $total_rows = $query1->rowCount();
                          $total_pages = ceil($total_rows / $no_of_records_per_page);

                          $sql = "SELECT * FROM tblcontactus LIMIT $offset, $no_of_records_per_page";
                          $query = $dbh->prepare($sql);
                          $query->execute();
                          $results = $query->fetchAll(PDO::FETCH_OBJ);
                          $cnt = 1;

                          if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                        ?>
                          <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo htmlentities($row->fullname); ?></td>
                            <td><?php echo htmlentities($row->phoneno); ?></td>
                            <td><?php echo htmlentities($row->email); ?></td>
                            <td><?php echo htmlentities($row->subject); ?></td>
                            <td><?php echo htmlentities($row->message); ?></td>
                            <td>
                              <a href="manage-contact-us.php?delid=<?php echo $row->id; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                          </tr>
                        <?php
                            }
                          } else {
                        ?>
                          <tr><td colspan="7" class="text-danger">No Record Found</td></tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Mobile Card View -->
                  <div class="mobile-card">
                    <?php
                      foreach ($results as $row) {
                    ?>
                    <div class="card mb-3 p-3 shadow-sm border border-left-primary">
                      <h5 class="card-title text-primary"><strong>Name:</strong> <?php echo htmlentities($row->fullname); ?></h5>
                      <p><strong>Phone:</strong> <?php echo htmlentities($row->phoneno); ?></p>
                      <p><strong>Email:</strong> <?php echo htmlentities($row->email); ?></p>
                      <p><strong>Subject:</strong> <?php echo htmlentities($row->subject); ?></p>
                      <p><strong>Message:</strong> <?php echo htmlentities($row->message); ?></p>
                      <div class="btn-group btn-group-sm mt-2">
                        <a href="manage-contact-us.php?delid=<?php echo $row->id; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                    <?php } ?>
                  </div>

                  <!-- Pagination -->
                  <nav class="mt-4">
                    <ul class="pagination flex-wrap">
                      <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?pageno=1">First</a>
                      </li>
                      <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php echo ($pageno <= 1) ? '#' : "?pageno=" . ($pageno - 1); ?>">Prev</a>
                      </li>
                      <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php echo ($pageno >= $total_pages) ? '#' : "?pageno=" . ($pageno + 1); ?>">Next</a>
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
  <script src="vendors/select2/select2.min.js"></script>
  <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
</body>
</html>
<?php } ?>
