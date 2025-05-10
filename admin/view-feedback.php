<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

// Admin delete handler
if (isset($_GET['delid'])) {
    $feedbackid = intval($_GET['delid']);
    $sql = "DELETE FROM tblfeedback WHERE ID = :feedbackid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':feedbackid', $feedbackid, PDO::PARAM_INT);
    if ($query->execute()) {
        if ($query->rowCount() > 0) {
            $_SESSION['msg'] = "Feedback deleted successfully.";
        } else {
            $_SESSION['error'] = "No feedback found with the given ID.";
        }
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }
    header('Location: view-feedback.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || View Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
</head>

<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">View Student Feedback</h3>
                    </div>

                    <?php if (isset($_SESSION['msg'])) { ?>
                        <div class="alert alert-success"><?php echo htmlentities($_SESSION['msg']);
                                                            unset($_SESSION['msg']); ?></div>
                    <?php } ?>
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger"><?php echo htmlentities($_SESSION['error']);
                                                        unset($_SESSION['error']); ?></div>
                    <?php } ?>

                    <?php
                    $sql = "SELECT f.ID, f.feedbacktext, f.postingDate, s.StudentName, t.TeacherName 
                            FROM tblfeedback f 
                            LEFT JOIN tblstudent s ON f.studentid = s.StuID 
                            LEFT JOIN tblteacher t ON f.teacherid = t.ID 
                            ORDER BY f.postingDate DESC";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    ?>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">All Feedback</h4>

                            <!-- Desktop Table View -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Student</th>
                                            <th>Teacher</th>
                                            <th>Feedback</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($query->rowCount() > 0) {
                                            $cnt = 1;
                                            foreach ($results as $row) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $cnt++; ?></td>
                                                    <td><?php echo htmlentities($row->StudentName); ?></td>
                                                    <td><?php echo htmlentities($row->TeacherName); ?></td>
                                                    <td><?php echo htmlentities($row->feedbacktext); ?></td>
                                                    <td><?php echo htmlentities(date("d-m-Y h:i A", strtotime($row->postingDate))); ?></td>
                                                    <td>
                                                        <a href="view-feedback.php?delid=<?php echo $row->ID; ?>" onclick="return confirm('Delete this feedback?')" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="text-center text-danger">No feedback found.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="d-md-none">
                                <?php
                                if ($query->rowCount() > 0) {
                                    $cnt = 1;
                                    foreach ($results as $row) {
                                ?>
                                        <div class="card mb-3 shadow-sm border">
                                            <div class="card-body">
                                                <h5 class="card-title"> <?php echo $cnt++; ?> - <?php echo htmlentities($row->StudentName); ?></h5>
                                                <p><strong>Teacher:</strong> <?php echo htmlentities($row->TeacherName); ?></p>
                                                <p><strong>Feedback:</strong> <?php echo htmlentities($row->feedbacktext); ?></p>
                                                <p><strong>Date:</strong> <?php echo htmlentities(date("d-m-Y h:i A", strtotime($row->postingDate))); ?></p>
                                                <a href="view-feedback.php?delid=<?php echo $row->ID; ?>" onclick="return confirm('Delete this feedback?')" class="btn btn-danger btn-sm">Delete</a>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<p class="text-danger text-center">No feedback found.</p>';
                                }
                                ?>
                            </div>

                        </div>
                    </div>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/select2/select2.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/template.js"></script>

</body>

</html>