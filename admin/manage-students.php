<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblstudent WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'manage-students.php'</script>";
  }

  // Handle filter input
  $filter_class_id = isset($_GET['class']) ? intval($_GET['class']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edutrack Pro || Manage Students</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
  <link rel="stylesheet" href="./css/style.css">
  <style>
    .table-responsive { display: block; }
    @media (max-width: 768px) {
      .table-responsive { display: none !important; }
      .card-view { display: block; }
      .mobile-card {
        border: 1px solid #ddd;
        margin-bottom: 15px;
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      }
      .mobile-card p { margin: 0 0 5px; }
      .mobile-card .btn {
        margin-right: 5px;
        margin-top: 10px;
      }
    }
    @media (min-width: 769px) {
      .card-view { display: none; }
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
          <h3 class="page-title"> Manage Students </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Manage Students</li>
            </ol>
          </nav>
        </div>
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-items-center mb-4">
                  <h4 class="card-title mb-sm-0">Manage Students</h4>
                </div>

                <!-- FILTER DROPDOWN -->
                <form method="GET" class="mb-3">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Select Class</label>
                    <div class="col-sm-6">
                      <select name="class" class="form-control" onchange="this.form.submit()">
                        <option value="0">All Classes</option>
                        <?php
                        $sqlclass = "SELECT * FROM tblclass";
                        $queryclass = $dbh->prepare($sqlclass);
                        $queryclass->execute();
                        $classes = $queryclass->fetchAll(PDO::FETCH_OBJ);
                        foreach ($classes as $class) {
                          $selected = ($filter_class_id == $class->ID) ? 'selected' : '';
                          echo "<option value='{$class->ID}' $selected>{$class->ClassName} {$class->Section}</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </form>

                <?php
                // Pagination
                $page_no = isset($_GET['page_no']) && $_GET['page_no'] != "" ? $_GET['page_no'] : 1;
                $total_records_per_page = 10;
                $offset = ($page_no - 1) * $total_records_per_page;

                // Count Query
                $ret = "SELECT ID FROM tblstudent";
                if ($filter_class_id > 0) {
                  $ret .= " WHERE StudentClass=:class";
                }
                $query1 = $dbh->prepare($ret);
                if ($filter_class_id > 0) $query1->bindParam(':class', $filter_class_id, PDO::PARAM_INT);
                $query1->execute();
                $total_records = $query1->rowCount();
                $total_no_of_pages = ceil($total_records / $total_records_per_page);
                $previous_page = $page_no - 1;
                $next_page = $page_no + 1;

                // Fetch Filtered Students
                $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                        FROM tblstudent 
                        JOIN tblclass ON tblclass.ID=tblstudent.StudentClass";
                if ($filter_class_id > 0) {
                  $sql .= " WHERE tblstudent.StudentClass=:class";
                }
                $sql .= " LIMIT $offset, $total_records_per_page";
                $query = $dbh->prepare($sql);
                if ($filter_class_id > 0) $query->bindParam(':class', $filter_class_id, PDO::PARAM_INT);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                ?>

                <!-- TABLE VIEW -->
                <div class="table-responsive border rounded p-1">
                  <table class="table table-striped table-hover small">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Student ID</th>
                        <th>Student Class</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Admission Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($results as $row) { ?>
                        <tr>
                          <td><?php echo htmlentities($cnt); ?></td>
                          <td><?php echo htmlentities($row->StuID); ?></td>
                          <td><?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></td>
                          <td><?php echo htmlentities($row->StudentName); ?></td>
                          <td><?php echo htmlentities($row->StudentEmail); ?></td>
                          <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                          <td>
                            <a href="edit-student-detail.php?editid=<?php echo $row->sid; ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="manage-students.php?delid=<?php echo $row->sid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                          </td>
                        </tr>
                      <?php $cnt++; } ?>
                    </tbody>
                  </table>
                </div>

                <!-- CARD VIEW -->
                <div class="card-view">
                  <?php $cnt = 1; foreach ($results as $row) { ?>
                    <div class="mobile-card border-left-primary">
                      <p><strong>S.No:</strong> <?php echo htmlentities($cnt); ?></p>
                      <p><strong>Student ID:</strong> <?php echo htmlentities($row->StuID); ?></p>
                      <p><strong>Class:</strong> <?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></p>
                      <p><strong>Name:</strong> <?php echo htmlentities($row->StudentName); ?></p>
                      <p><strong>Email:</strong> <?php echo htmlentities($row->StudentEmail); ?></p>
                      <p><strong>Admission Date:</strong> <?php echo htmlentities($row->DateofAdmission); ?></p>
                      <a href="edit-student-detail.php?editid=<?php echo $row->sid; ?>" class="btn btn-info btn-sm">Edit</a>
                      <a href="manage-students.php?delid=<?php echo $row->sid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                  <?php $cnt++; } ?>
                </div>

                <!-- PAGINATION -->
                <ul class="pagination mt-4">
                  <li class="page-item <?php if ($page_no <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page_no=<?php echo $previous_page; ?>&class=<?php echo $filter_class_id; ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $total_no_of_pages; $i++) { ?>
                    <li class="page-item <?php if ($page_no == $i) echo 'active'; ?>">
                      <a class="page-link" href="?page_no=<?php echo $i; ?>&class=<?php echo $filter_class_id; ?>"><?php echo $i; ?></a>
                    </li>
                  <?php } ?>
                  <li class="page-item <?php if ($page_no >= $total_no_of_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page_no=<?php echo $next_page; ?>&class=<?php echo $filter_class_id; ?>">Next</a>
                  </li>
                </ul>

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
