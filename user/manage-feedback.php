<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsstuid']) == 0) {
    header('location:logout.php');
    exit();
}

$studentid = $_SESSION['sturecmsstuid'];

// Handle Deletion
if (isset($_GET['delete'])) {
    $feedbackid = intval($_GET['delete']);
    $sql = "DELETE FROM tblfeedback WHERE ID = :feedbackid AND studentid = :studentid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':feedbackid', $feedbackid, PDO::PARAM_INT);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    if ($query->execute()) {
        $msg = "Feedback deleted successfully.";
    } else {
        $error = "Failed to delete feedback. Please try again.";
    }
}

// Fetch all feedback by the student, with teacher name
$sql = "SELECT f.ID, f.feedbacktext, t.TeacherName 
        FROM tblfeedback f 
        LEFT JOIN tblteacher t ON f.teacherid = t.ID 
        WHERE f.studentid = :studentid";
$query = $dbh->prepare($sql);
$query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
$query->execute();
$feedbacks = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Manage Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />

    <style>
        @media (max-width: 576px) {
            .card-title {
                font-size: 1.1rem;
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
                        <h3 class="page-title">Manage Feedback</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Feedback</li>
                            </ol>
                        </nav>
                    </div>

                    <?php if (isset($msg)) { ?>
                        <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
                    <?php } ?>
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Your Feedback</h4>

                                    <!-- Table for Desktop -->
                                    <div class="table-responsive d-none d-sm-block">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Teacher</th>
                                                    <th>Feedback</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($query->rowCount() > 0) {
                                                    foreach ($feedbacks as $feedback) { ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($feedback->TeacherName ?? "Unknown"); ?></td>
                                                            <td><?php echo htmlentities($feedback->feedbacktext); ?></td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a href="edit-feedback.php?id=<?php echo $feedback->ID; ?>" class="btn btn-primary btn-sm m-1">Edit</a>
                                                                    <a href="manage-feedback.php?delete=<?php echo $feedback->ID; ?>" onclick="return confirm('Are you sure you want to delete this feedback?');" class="btn btn-danger btn-sm m-1">Delete</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center">No feedback found.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Card View for Mobile -->
                                    <div class="d-block d-sm-none">
                                        <?php if ($query->rowCount() > 0) {
                                            foreach ($feedbacks as $feedback) { ?>
                                                <div class="card mb-3 border border-secondary">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Teacher: <?php echo htmlentities($feedback->TeacherName ?? "Unknown"); ?></h5>
                                                        <p class="card-text"><?php echo htmlentities($feedback->feedbacktext); ?></p>
                                                        <div class="d-grid gap-2">
                                                            <a href="edit-feedback.php?id=<?php echo $feedback->ID; ?>" class="btn btn-primary btn-sm">Edit</a>
                                                            <a href="manage-feedback.php?delete=<?php echo $feedback->ID; ?>" onclick="return confirm('Are you sure you want to delete this feedback?');" class="btn btn-danger btn-sm">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        } else { ?>
                                            <div class="alert alert-info">No feedback found.</div>
                                        <?php } ?>
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
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
</body>
</html>
