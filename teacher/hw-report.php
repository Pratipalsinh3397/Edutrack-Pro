<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblhomework WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Homework deleted');</script>";
        echo "<script>window.location.href = 'manage-homeworks.php'</script>";
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Edutrack Pro || Homework Between Dates Report</title>
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
            @media (max-width: 768px) {
                .desktop-table {
                    display: none;
                }

                .card-mobile {
                    display: block;
                }
            }

            @media (min-width: 769px) {
                .card-mobile {
                    display: none;
                }
            }

            .card-mobile .card {
                margin-bottom: 15px;
            }

            .btn-group-sm>.btn {
                margin: 2px;
            }

            .pagination {
                flex-wrap: wrap;
            }
        </style>
    </head>

    <body>
        <div class="container-scroller">
            <!-- TOP NAVBAR (with burger menu & profile) -->
            <?php include_once('includes/header.php'); ?>

            <div class="container-fluid page-body-wrapper">
                <!-- SIDEBAR -->
                <?php include_once('includes/sidebar.php'); ?>

                <div class="main-panel">
                    <div class="content-wrapper">

                        <div class="page-header">
                            <h3 class="page-title">Homework Between Dates Report</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Between Dates Report</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        $fdate = $_POST['fromdate'] ?? '';
                                        $tdate = $_POST['todate'] ?? '';

                                        if ($fdate && $tdate):
                                        ?>
                                            <h5 class="text-center text-primary mb-4">
                                                Report from <strong><?php echo htmlentities($fdate); ?></strong> to <strong><?php echo htmlentities($tdate); ?></strong>
                                            </h5>

                                            <?php
                                            $pageno = $_GET['pageno'] ?? 1;
                                            $records_per_page = 5;
                                            $offset = ($pageno - 1) * $records_per_page;

                                            $ret = "SELECT ID FROM tblhomework WHERE DATE(postingDate) BETWEEN '$fdate' AND '$tdate'";
                                            $stmt = $dbh->prepare($ret);
                                            $stmt->execute();
                                            $total_rows = $stmt->rowCount();
                                            $total_pages = ceil($total_rows / $records_per_page);

                                            $sql = "SELECT tblclass.ClassName, tblclass.Section, tblhomework.homeworkTitle, 
                                                tblhomework.postingDate, tblhomework.lastDateofSubmission, tblhomework.ID AS hwid 
                                        FROM tblhomework 
                                        JOIN tblclass ON tblclass.ID = tblhomework.classId 
                                        WHERE DATE(postingDate) BETWEEN '$fdate' AND '$tdate' 
                                        ORDER BY tblhomework.postingDate DESC 
                                        LIMIT $offset, $records_per_page";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            ?>

                                            <!-- Desktop Table View -->
                                            <div class="table-responsive border rounded p-2 desktop-table">
                                                <table class="table table-striped table-bordered">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th>Homework Title</th>
                                                            <th>Class</th>
                                                            <th>Section</th>
                                                            <th>Last Submission</th>
                                                            <th>Posted On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ($query->rowCount() > 0): $cnt = $offset + 1;
                                                            foreach ($results as $row): ?>
                                                                <tr>
                                                                    <td><?php echo $cnt++; ?></td>
                                                                    <td><?php echo htmlentities($row->homeworkTitle); ?></td>
                                                                    <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                    <td><?php echo htmlentities($row->Section); ?></td>
                                                                    <td><?php echo htmlentities($row->lastDateofSubmission); ?></td>
                                                                    <td><?php echo htmlentities($row->postingDate); ?></td>
                                                                    <td>
                                                                        <div class="btn-group-sm d-flex flex-wrap">
                                                                            <a href="edit-homework.php?hwid=<?php echo $row->hwid; ?>" class="btn btn-info">Edit</a>
                                                                            <a href="manage-homeworks.php?delid=<?php echo $row->hwid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger">Delete</a>
                                                                            <a href="uploaded-hw.php?hwid=<?php echo $row->hwid; ?>" class="btn btn-secondary" target="_blank">Uploaded</a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach;
                                                        else: ?>
                                                            <tr>
                                                                <td colspan="7" class="text-center text-danger">No records found</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Mobile Card View -->
                                            <div class="card-mobile">
                                                <?php if ($query->rowCount() > 0): foreach ($results as $row): ?>
                                                        <div class="card border shadow-sm mb-3">
                                                            <div class="card-body">
                                                                <h5 class="card-title text-primary"><?php echo htmlentities($row->homeworkTitle); ?></h5>
                                                                <p><strong>Class:</strong> <?php echo htmlentities($row->ClassName); ?></p>
                                                                <p><strong>Section:</strong> <?php echo htmlentities($row->Section); ?></p>
                                                                <p><strong>Last Submission:</strong> <?php echo htmlentities($row->lastDateofSubmission); ?></p>
                                                                <p><strong>Posted On:</strong> <?php echo htmlentities($row->postingDate); ?></p>
                                                                <div class="btn-group-sm d-flex flex-column gap-1">
                                                                    <a href="edit-homework.php?hwid=<?php echo $row->hwid; ?>" class="btn btn-info btn-sm">Edit</a>
                                                                    <a href="manage-homeworks.php?delid=<?php echo $row->hwid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                                                                    <a href="uploaded-hw.php?hwid=<?php echo $row->hwid; ?>" class="btn btn-secondary btn-sm" target="_blank">Uploaded</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach;
                                                else: ?>
                                                    <div class="text-center text-danger">No records found</div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Pagination -->
                                            <div class="mt-4">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                                                        <a class="page-link" href="?pageno=1">First</a>
                                                    </li>
                                                    <li class="page-item <?php if ($pageno <= 1) echo 'disabled'; ?>">
                                                        <a class="page-link" href="?pageno=<?php echo max(1, $pageno - 1); ?>">Prev</a>
                                                    </li>
                                                    <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
                                                        <a class="page-link" href="?pageno=<?php echo min($total_pages, $pageno + 1); ?>">Next</a>
                                                    </li>
                                                    <li class="page-item <?php if ($pageno >= $total_pages) echo 'disabled'; ?>">
                                                        <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center text-danger">Please select a date range first.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <?php include_once('includes/footer.php'); ?>
                </div>
            </div>
        </div>

        <!-- JS -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
    </body>

    </html>
<?php } ?>