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
  <title>Edutrack Pro || View Notice</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Plugins and Custom CSS -->
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css" />

  <!-- Custom Mobile Style -->
  <style>
    @media (max-width: 768px) {
      .notice-card {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        background-color: #f9f9f9;
        padding: 15px;
      }

      .notice-title {
        font-size: 18px;
        font-weight: 700;
        color: #004085;
        margin-bottom: 8px;
      }

      .notice-detail {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
      }

      .notice-msg {
        font-size: 15px;
        color: #212529;
        background-color: #e2f0fb;
        padding: 10px;
        border-radius: 6px;
      }

      .notice-label {
        font-weight: bold;
        color: #6c757d;
      }

      .table {
        display: none;
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
            <h3 class="page-title">View Notice</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Notice</li>
              </ol>
            </nav>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 stretch-card">
              <div class="card">
                <div class="card-body">

                  <?php
                  $stuclass = $_SESSION['stuclass'];
                  $limit = 4;
                  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                  $offset = ($page - 1) * $limit;

                  $total_query = "SELECT COUNT(*) as total FROM tblnotice WHERE ClassId = :stuclass";
                  $total_stmt = $dbh->prepare($total_query);
                  $total_stmt->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                  $total_stmt->execute();
                  $total_result = $total_stmt->fetch(PDO::FETCH_OBJ);
                  $total_pages = ceil($total_result->total / $limit);

                  $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblnotice.NoticeTitle, 
                    tblnotice.CreationDate,tblnotice.noticeFile, tblnotice.ClassId, tblnotice.NoticeMsg, tblnotice.ID as nid 
                    FROM tblnotice 
                    JOIN tblclass ON tblclass.ID = tblnotice.ClassId 
                    WHERE tblnotice.ClassId = :stuclass 
                    ORDER BY nid DESC 
                    LIMIT :limit OFFSET :offset";

                  $query = $dbh->prepare($sql);
                  $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                  $query->bindParam(':limit', $limit, PDO::PARAM_INT);
                  $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);

                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                  ?>
                      <!-- Card style layout for mobile -->
                      <div class="notice-card d-md-none">
                        <div class="notice-title"><?php echo htmlentities($row->NoticeTitle); ?></div>
                        <div class="notice-detail"><span class="notice-label">Date:</span> <?php echo htmlentities($row->CreationDate); ?></div>
                        <div class="notice-detail"><span class="notice-label">Class:</span> <?php echo htmlentities($row->ClassName . ' - ' . $row->Section); ?></div>
                        <div class="notice-msg mb-2"><?php echo htmlentities($row->NoticeMsg); ?></div>
                        <div>
                          <?php if (!empty($row->noticeFile)) { ?>
                            <a class="btn btn-sm btn-outline-primary" href="../admin/uploadednt/<?php echo htmlentities($row->noticeFile); ?>" target="_blank">View Attachment</a>
                          <?php } else {
                            echo "<small class='text-muted'>No file uploaded</small>";
                          } ?>
                        </div>
                      </div>

                      <!-- Desktop table fallback -->
                      <table class="table table-bordered table-hover d-none d-md-table">
                        <tr class="table-primary text-center">
                          <td colspan="2" style="font-size:20px;">Notice</td>
                        </tr>
                        <tr>
                          <th>Notice Date</th>
                          <td><?php echo $row->CreationDate; ?></td>
                        </tr>
                        <tr>
                          <th>Notice Title</th>
                          <td><?php echo $row->NoticeTitle; ?></td>
                        </tr>
                        <tr>
                          <th>Message</th>
                          <td><?php echo $row->NoticeMsg; ?></td>
                        </tr>
                        <tr>
                          <th>File</th>
                          <td>
                            <?php if (!empty($row->noticeFile)) { ?>
                              <a href="../admin/uploadednt/<?php echo htmlentities($row->noticeFile); ?>" target="_blank">Click here</a>
                            <?php } else {
                              echo "No file uploaded";
                            } ?>
                          </td>
                        </tr>
                      </table>
                  <?php
                    }
                  } else {
                    echo '<div class="alert alert-danger text-center">No Notice Found</div>';
                  }
                  ?>

                  <!-- Pagination -->
                  <nav class="d-flex justify-content-center my-3">
                    <ul class="pagination">
                      <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li>
                      <?php endif; ?>
                      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                          <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                      <?php endfor; ?>
                      <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                      <?php endif; ?>
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
