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
        $sql = "delete from tblhomework where id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'manage-homeworks.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Manage Homeworks</title>
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

    <!-- Custom style for responsiveness -->
    <style>
        @media (max-width: 768px) {
            .homework-card {
                margin-bottom: 20px;
                padding: 15px;
                border: 1px solid #eee;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }

            .homework-card h5 {
                font-size: 16px;
                margin-bottom: 10px;
            }

            .homework-card p {
                font-size: 14px;
                margin-bottom: 5px;
            }

            .action-btns {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 10px;
            }

            .btn-xs {
                padding: 6px 12px;
                font-size: 13px;
                flex: 1 1 auto;
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
                        <h3 class="page-title">Manage Homework</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Homework</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
                                        <h4 class="card-title mb-2 mb-sm-0">Manage Homework</h4>
                                        <a href="#" class="text-dark">View all Homework</a>
                                    </div>

                                    <!-- Responsive: Table for desktop, cards for mobile -->
                                    <div class="d-none d-md-block">
                                        <div class="table-responsive border rounded p-2">
                                            <table class="table table-striped table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Homework Title</th>
                                                        <th>Class</th>
                                                        <th>Section</th>
                                                        <th>Last Submission Date</th>
                                                        <th>Posting Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Get the logged-in teacher's ID
                                                    $teacherId = $_SESSION['sturecmsaid'];

                                                    $page_no = isset($_GET['pageno']) ? intval($_GET['pageno']) : 1;
                                                    $records_per_page = 10;
                                                    $offset = ($page_no - 1) * $records_per_page;

                                                    // Get the total number of rows
                                                    $stmt = $dbh->prepare("SELECT COUNT(*) FROM tblhomework WHERE teacherId = :teacherId");
                                                    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $total_rows = $stmt->fetchColumn();
                                                    $total_pages = ceil($total_rows / $records_per_page);

                                                    // Fetch the homework records assigned by the logged-in teacher
                                                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblhomework.homeworkTitle, tblhomework.postingDate, tblhomework.lastDateofSubmission, tblhomework.id as hwid 
                                                            FROM tblhomework 
                                                            JOIN tblclass ON tblclass.ID=tblhomework.classId 
                                                            WHERE tblhomework.teacherId = :teacherId
                                                            LIMIT $offset, $records_per_page";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->homeworkTitle); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                <td><?php echo htmlentities($row->Section); ?></td>
                                                                <td><?php echo htmlentities($row->lastDateofSubmission); ?></td>
                                                                <td><?php echo htmlentities($row->postingDate); ?></td>
                                                                <td>
                                                                    <a href="edit-homework.php?hwid=<?php echo $row->hwid; ?>" class="btn btn-info btn-sm">Edit</a>
                                                                    <a href="manage-homeworks.php?delid=<?php echo $row->hwid; ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                                                    <a href="uploaded-hw.php?hwid=<?php echo $row->hwid; ?>" class="btn btn-primary btn-sm">Uploaded HW</a>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                            $cnt++;
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="7" class="text-center text-danger">No Record Found</td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="mt-4 d-flex justify-content-start">
                                        <ul class="pagination">
                                            <li class="page-item <?php echo ($page_no <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="?pageno=1">First</a>
                                            </li>
                                            <li class="page-item <?php echo ($page_no <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="<?php echo ($page_no > 1) ? '?pageno=' . ($page_no - 1) : '#'; ?>">Prev</a>
                                            </li>
                                            <li class="page-item <?php echo ($page_no >= $total_pages) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="<?php echo ($page_no < $total_pages) ? '?pageno=' . ($page_no + 1) : '#'; ?>">Next</a>
                                            </li>
                                            <li class="page-item <?php echo ($page_no >= $total_pages) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                                            </li>
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

    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- Plugin js for this page -->
    <script src="./vendors/chart.js/Chart.min.js"></script>
    <script src="./vendors/moment/moment.min.js"></script>
    <script src="./vendors/daterangepicker/daterangepicker.js"></script>
    <script src="./vendors/chartist/chartist.min.js"></script>
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <!-- Custom js for this page -->
    <script src="./js/dashboard.js"></script>
</body>
</html>
<?php } ?>
