<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsstuid']) == 0) {
    header('location:logout.php');
    exit();
}

$studentid = $_SESSION['sturecmsstuid'];
$feedbackid = intval($_GET['id']);

// Fetch existing feedback
$sql = "SELECT * FROM tblfeedback WHERE ID = :id AND studentid = :studentid";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $feedbackid, PDO::PARAM_INT);
$query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
$query->execute();

if ($query->rowCount() == 0) {
    echo "<script>alert('Invalid request'); window.location='manage-feedback.php';</script>";
    exit();
}

$feedback = $query->fetch(PDO::FETCH_OBJ);

// Handle update
if (isset($_POST['update'])) {
    $newFeedback = $_POST['feedback'];

    $update = "UPDATE tblfeedback SET feedbacktext = :feedback WHERE ID = :id AND studentid = :studentid";
    $stmt = $dbh->prepare($update);
    $stmt->bindParam(':feedback', $newFeedback, PDO::PARAM_STR);
    $stmt->bindParam(':id', $feedbackid, PDO::PARAM_INT);
    $stmt->bindParam(':studentid', $studentid, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $msg = "Feedback updated successfully.";
        $feedback->feedbacktext = $newFeedback; // Refresh the local object
    } else {
        $error = "Failed to update feedback.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css" />
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
                        <h3 class="page-title">Edit Feedback</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Feedback</li>
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
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group mb-3">
                                            <label for="feedback">Feedback</label>
                                            <select name="feedback" class="form-control" required>
                                                <option value="">Select Feedback</option>
                                                <option value="Excellent Teaching" <?php if ($feedback->feedbacktext == 'Excellent Teaching') echo 'selected'; ?>>Excellent Teaching</option>
                                                <option value="Very Helpful" <?php if ($feedback->feedbacktext == 'Very Helpful') echo 'selected'; ?>>Very Helpful</option>
                                                <option value="Needs Improvement" <?php if ($feedback->feedbacktext == 'Needs Improvement') echo 'selected'; ?>>Needs Improvement</option>
                                                <option value="Too Fast in Class" <?php if ($feedback->feedbacktext == 'Too Fast in Class') echo 'selected'; ?>>Too Fast in Class</option>
                                                <option value="Very Clear Explanation" <?php if ($feedback->feedbacktext == 'Very Clear Explanation') echo 'selected'; ?>>Very Clear Explanation</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="update" class="btn btn-primary">Update Feedback</button>
                                        <a href="manage-feedback.php" class="btn btn-secondary">Back</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>
</body>

</html>