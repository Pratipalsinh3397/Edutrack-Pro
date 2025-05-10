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
        $sql = "DELETE FROM tblstudent WHERE ID=:rid";
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
    <title>Edutrack Pro || Search Students</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
    <!-- Layout styles -->
    <link rel="stylesheet" href="./css/style.css">

    <style>
        .mobile-card {
            display: none;
        }

        .desktop-table {
            display: block;
        }

        @media (max-width: 768px) {
            .desktop-table {
                display: none !important;
            }

            .mobile-card {
                display: block !important;
            }

            .mobile-card .card {
                border: 1px solid #ddd;
                /* border-radius: 10px; */
                margin-bottom: 15px;
                /* padding: 15px; */
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }
            .btn{
              margin: 10px 0px;
            }
        }

        .pagination {
            flex-wrap: wrap;
        }

        .pagination .page-item {
            margin: 3px;
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
                        <h3 class="page-title"> Search Student </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> Search Student</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" class="row g-2 align-items-end">
                                        <div class="col-sm-9 col-12 mx-10">
                                            <label for="searchdata" class="form-label"><strong>Search Student:</strong></label>
                                            <input id="searchdata" type="text" name="searchdata" required class="form-control" placeholder="Search by Student ID or Student name" value="<?php echo isset($_POST['searchdata']) ? htmlentities($_POST['searchdata']) : ''; ?>">
                                          </div>
                                          <div class="col-sm-3 col-12 mx-10">
                                          <button type="submit" class="btn btn-primary w-100" name="search" id="submit">Search</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['search']) || isset($_GET['searchdata'])) {
                                        $sdata = isset($_POST['searchdata']) ? $_POST['searchdata'] : $_GET['searchdata'];
                                        $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                                        $no_of_records_per_page = 5;
                                        $offset = ($pageno - 1) * $no_of_records_per_page;

                                        $ret = "SELECT COUNT(*) FROM tblstudent WHERE StuID LIKE :search OR StudentName LIKE :search";
                                        $query1 = $dbh->prepare($ret);
                                        $query1->bindValue(':search', "$sdata%", PDO::PARAM_STR);
                                        $query1->execute();
                                        $total_rows = $query1->fetchColumn();
                                        $total_pages = ceil($total_rows / $no_of_records_per_page);

                                        $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                                                FROM tblstudent 
                                                JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                                WHERE tblstudent.StuID LIKE :search OR tblstudent.StudentName LIKE :search 
                                                LIMIT $offset, $no_of_records_per_page";
                                        $query = $dbh->prepare($sql);
                                        $query->bindValue(':search', "$sdata%", PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                    ?>
                                        <hr />
                                        <h4 align="center">Result against "<?php echo htmlentities($sdata); ?>" keyword </h4>

                                        <!-- Desktop Table View -->
                                        <div class="table-responsive border rounded p-1 desktop-table mt-4">
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
                                                    <?php
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->StuID); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>
                                                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                                <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                                                <td>
                                                                    <a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm" target="_blank">Edit</a>
                                                                    <a href="manage-students.php?delid=<?php echo ($row->sid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                                                </td>
                                                            </tr>
                                                    <?php $cnt++;
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="8"> No record found against this search</td></tr>';
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Mobile Card View -->
                                        <div class="mobile-card mt-3">
                                            <?php
                                            if ($query->rowCount() > 0) {
                                                $cnt = 1;
                                                foreach ($results as $row) {
                                            ?>
                                                    <div class="card mb-3 shadow-sm border border-left-primary">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-primary"><?php echo htmlentities($row->StudentName); ?></h5>
                                                            <p><strong>Student ID:</strong> <?php echo htmlentities($row->StuID); ?></p>
                                                            <p><strong>Class:</strong> <?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></p>
                                                            <p><strong>Email:</strong> <?php echo htmlentities($row->StudentEmail); ?></p>
                                                            <p><strong>Admission Date:</strong> <?php echo htmlentities($row->DateofAdmission); ?></p>
                                                            <a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-sm" target="_blank">Edit</a>
                                                            <a href="manage-students.php?delid=<?php echo ($row->sid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                                        </div>
                                                    </div>
                                            <?php $cnt++;
                                                }
                                            } ?>
                                        </div>

                                        <!-- Pagination -->
                                        <nav aria-label="Page navigation" class="mt-4">
                                            <ul class="pagination">
                                                <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                                                    <a class="page-link" href="?pageno=1&searchdata=<?php echo urlencode($sdata); ?>">First</a>
                                                </li>
                                                <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                                                    <a class="page-link" href="<?php if ($pageno <= 1) echo '#'; else echo '?pageno=' . ($pageno - 1) . '&searchdata=' . urlencode($sdata); ?>">Prev</a>
                                                </li>
                                                <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
                                                    <a class="page-link" href="<?php if ($pageno >= $total_pages) echo '#'; else echo '?pageno=' . ($pageno + 1) . '&searchdata=' . urlencode($sdata); ?>">Next</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pageno=<?php echo $total_pages; ?>&searchdata=<?php echo urlencode($sdata); ?>">Last</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    <?php } ?>
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
</body>
</html>
<?php } ?>
