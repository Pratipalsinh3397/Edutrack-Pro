<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['sturecmsstuid']) || strlen($_SESSION['sturecmsstuid']) == 0) {
    header('location:logout.php');
} else {  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || View Material Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />

    <style>
        .mobile-card-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mobile-card {
            border-radius: 10px;
            background-color: #ffffff;
            padding: 1rem 1.2rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e0e0;
        }

        .mobile-card .card-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .mobile-card .card-value {
            font-size: 0.95rem;
            color: #555;
        }

        @media (min-width: 576px) {
            .mobile-card-container {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .desktop-table {
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
                        <h3 class="page-title"> View Material </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Material</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $stuclass = isset($_SESSION['stuclass']) ? $_SESSION['stuclass'] : 0;
                                    $mtid = intval($_GET['mtid']);

                                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, 
                                                tblmaterial.materialTitle, tblmaterial.materialFile, 
                                                tblmaterial.postingDate, tblmaterial.id as mtid, 
                                                tblmaterial.materialDescription 
                                            FROM tblmaterial 
                                            JOIN tblclass ON tblclass.ID = tblmaterial.classId  
                                            WHERE tblmaterial.classId = :stuclass AND tblmaterial.id = :mtid";
                                    
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':stuclass', $stuclass, PDO::PARAM_INT);
                                    $query->bindParam(':mtid', $mtid, PDO::PARAM_INT);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                    ?>

                                    <!-- Desktop Table -->
                                    <div class="table-responsive desktop-table">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="font-weight-bold">Material Title</th>
                                                <td><?php echo htmlentities($row->materialTitle); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="font-weight-bold">Class</th>
                                                <td><?php echo htmlentities($row->ClassName); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="font-weight-bold">Section</th>
                                                <td><?php echo htmlentities($row->Section); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="font-weight-bold">Material File (PDF/DOC)</th>
                                                <td>
                                                    <?php if (!empty($row->materialFile)) { ?>
                                                        <a href="../teacher/uploadedmt/<?php echo htmlentities($row->materialFile); ?>" target="_blank">Click here</a>
                                                    <?php } else {
                                                        echo "No file uploaded";
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="font-weight-bold">Posting Date</th>
                                                <td><?php echo htmlentities($row->postingDate); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="font-weight-bold">Material Description</th>
                                                <td><?php echo htmlentities($row->materialDescription); ?></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- Mobile Card View -->
                                    <div class="mobile-card-container">
                                        <div class="mobile-card">
                                            <div class="card-label">Material Title</div>
                                            <div class="card-value"><?php echo htmlentities($row->materialTitle); ?></div>
                                        </div>
                                        <div class="mobile-card">
                                            <div class="card-label">Class</div>
                                            <div class="card-value"><?php echo htmlentities($row->ClassName); ?></div>
                                        </div>
                                        <div class="mobile-card">
                                            <div class="card-label">Section</div>
                                            <div class="card-value"><?php echo htmlentities($row->Section); ?></div>
                                        </div>
                                        <div class="mobile-card">
                                            <div class="card-label">Material File</div>
                                            <div class="card-value">
                                                <?php if (!empty($row->materialFile)) { ?>
                                                    <a href="../teacher/uploadedmt/<?php echo htmlentities($row->materialFile); ?>" target="_blank">Click here</a>
                                                <?php } else {
                                                    echo "No file uploaded";
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="mobile-card">
                                            <div class="card-label">Posting Date</div>
                                            <div class="card-value"><?php echo htmlentities($row->postingDate); ?></div>
                                        </div>
                                        <div class="mobile-card">
                                            <div class="card-label">Description</div>
                                            <div class="card-value"><?php echo htmlentities($row->materialDescription); ?></div>
                                        </div>
                                    </div>

                                    <?php }
                                    } else { ?>
                                        <div class="text-danger text-center">No material found.</div>
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

    <!-- JS Dependencies -->
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
