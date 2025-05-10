<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblstudent WHERE ID = :rid";
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
    <!-- CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.2rem;
            }

            .breadcrumb {
                font-size: 0.9rem;
            }

            table th,
            table td {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            .btn-xs {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
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
                        <h3 class="page-title text-center text-sm-start">Between Dates Reports of Students</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Student Reports</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <?php
                                    $fdate = $_POST['fromdate'];
                                    $tdate = $_POST['todate'];
                                    ?>
                                    <h5 class="text-center mb-4 text-primary">Students Report from <strong><?php echo $fdate ?></strong> to <strong><?php echo $tdate ?></strong></h5>

                                    <div class="table-responsive border rounded">
                                        <table class="table table-striped table-bordered">
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
                                                $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                                                $no_of_records_per_page = 5;
                                                $offset = ($pageno - 1) * $no_of_records_per_page;

                                                $ret = "SELECT ID FROM tblstudent";
                                                $query1 = $dbh->prepare($ret);
                                                $query1->execute();
                                                $total_rows = $query1->rowCount();
                                                $total_pages = ceil($total_rows / $no_of_records_per_page);

                                                $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                                                        FROM tblstudent 
                                                        JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                                        WHERE date(tblstudent.DateofAdmission) BETWEEN :fdate AND :tdate 
                                                        LIMIT $offset, $no_of_records_per_page";
                                                $query = $dbh->prepare($sql);
                                                $query->bindParam(':fdate', $fdate, PDO::PARAM_STR);
                                                $query->bindParam(':tdate', $tdate, PDO::PARAM_STR);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $cnt++; ?></td>
                                                            <td><?php echo htmlentities($row->StuID); ?></td>
                                                            <td><?php echo htmlentities($row->ClassName . ' ' . $row->Section); ?></td>
                                                            <td><?php echo htmlentities($row->StudentName); ?></td>
                                                            <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                            <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                                            <td>
                                                                <a href="edit-student-detail.php?editid=<?php echo $row->sid; ?>" class="btn btn-info btn-sm mb-1">Edit</a>
                                                                <a href="manage-students.php?delid=<?php echo $row->sid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } else {
                                                    echo "<tr><td colspan='7' class='text-center text-muted'>No Records Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                            <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                                                <a class="page-link" href="<?php if ($pageno > 1) echo "?pageno=" . ($pageno - 1); else echo '#'; ?>">Prev</a>
                                            </li>
                                            <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
                                                <a class="page-link" href="<?php if ($pageno < $total_pages) echo "?pageno=" . ($pageno + 1); else echo '#'; ?>">Next</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
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
</body>

</html>
<?php } ?>
