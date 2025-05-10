<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Get the logged-in teacher's ID from the session
    $teacherId = $_SESSION['sturecmsaid']; // Assuming teacher's ID is stored in the session as 'sturecmsaid'

    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblmaterial WHERE id=:rid AND teacherId=:teacherId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->bindParam(':teacherId', $teacherId, PDO::PARAM_STR); // Bind teacher ID to the query
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'manage-material.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Manage Material</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        @media (max-width: 768px) {
            .material-card {
                margin-bottom: 20px;
                padding: 15px;
                border: 1px solid #eee;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }

            .material-card h5 {
                font-size: 16px;
                margin-bottom: 10px;
            }

            .material-card p {
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
                        <h3 class="page-title">Manage Material</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Material</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
                                        <h4 class="card-title mb-2 mb-sm-0">Manage Material</h4>
                                    </div>

                                    <!-- Desktop Table -->
                                    <div class="d-none d-md-block">
                                        <div class="table-responsive border rounded p-2">
                                            <table class="table table-striped table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Material Title</th>
                                                        <th>Class</th>
                                                        <th>Section</th>
                                                        <th>Posting Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $page_no = $_GET['pageno'] ?? 1;
                                                    $records_per_page = 10;
                                                    $offset = ($page_no - 1) * $records_per_page;

                                                    $total_stmt = $dbh->prepare("SELECT ID FROM tblmaterial WHERE teacherId=:teacherId");
                                                    $total_stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_STR); // Bind teacher ID
                                                    $total_stmt->execute();
                                                    $total_rows = $total_stmt->rowCount();
                                                    $total_pages = ceil($total_rows / $records_per_page);

                                                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblmaterial.materialTitle, tblmaterial.postingDate, tblmaterial.id AS mtid
                                                            FROM tblmaterial
                                                            JOIN tblclass ON tblclass.ID = tblmaterial.classId
                                                            WHERE tblmaterial.teacherId=:teacherId
                                                            LIMIT $offset, $records_per_page";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacherId', $teacherId, PDO::PARAM_STR); // Bind teacher ID
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($row->materialTitle); ?></td>
                                                        <td><?php echo htmlentities($row->ClassName); ?></td>
                                                        <td><?php echo htmlentities($row->Section); ?></td>
                                                        <td><?php echo htmlentities($row->postingDate); ?></td>
                                                        <td>
                                                            <a href="edit-material.php?mtid=<?php echo $row->mtid; ?>" class="btn btn-info btn-sm">Edit</a>
                                                            <a href="manage-material.php?delid=<?php echo $row->mtid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php $cnt++; } } else { ?>
                                                    <tr><td colspan="6" class="text-center text-danger">No Record Found</td></tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Mobile Cards -->
                                    <div class="d-md-none">
                                        <?php
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                        <div class="material-card">
                                            <h5><?php echo htmlentities($row->materialTitle); ?></h5>
                                            <p><strong>Class:</strong> <?php echo htmlentities($row->ClassName); ?></p>
                                            <p><strong>Section:</strong> <?php echo htmlentities($row->Section); ?></p>
                                            <p><strong>Posted On:</strong> <?php echo htmlentities($row->postingDate); ?></p>
                                            <div class="action-btns">
                                                <a href="edit-material.php?mtid=<?php echo $row->mtid; ?>" class="btn btn-info btn-xs">Edit</a>
                                                <a href="manage-material.php?delid=<?php echo $row->mtid; ?>" onclick="return confirm('Do you really want to delete?');" class="btn btn-danger btn-xs">Delete</a>
                                            </div>
                                        </div>
                                        <?php $cnt++; } } ?>
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
