<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Delete Logic
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblenroll WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted'); window.location.href = 'manage-admission-inq.php';</script>";
    }

    // Email Response Logic
    if (isset($_POST['sendresponse'])) {
        $msg = $_POST['respmsg'];
        $themail = $_POST['respemail'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'typrojectphd@gmail.com';
            $mail->Password = 'qsktvvyjhhntufez';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('typrojectphd@gmail.com', 'Edutrack Pro');
            $mail->addAddress($themail);

            $mail->isHTML(true);
            $mail->Subject = 'Response Regarding Your Inquiry';
            $mail->Body = 'Dear User,<br><br>' . nl2br($msg) . '<br><br>Best Regards,<br>Edutrack Pro Team';

            if ($mail->send()) {
                $update = "UPDATE tblenroll SET IsResponded = 1 WHERE ID = :id";
                $queryUpdate = $dbh->prepare($update);
                $queryUpdate->bindParam(':id', $_POST['respid'], PDO::PARAM_INT);
                $queryUpdate->execute();

                echo '<script>alert("Response Email Sent Successfully!"); window.location.href="manage-admission-inq.php";</script>';
            }
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Error: {$mail->ErrorInfo}');</script>";
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Edutrack Pro || Manage Admission Inquiry</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
    @media (max-width: 768px) {
      .border-left-primary {
        border-left: 4px solid #007bff !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        background-color: #fff;
      }

      .card-body {
        padding: 1rem;
      }

      .card-title {
        font-size: 1.2rem;
        font-weight: 600;
      }

      .btn-sm {
        font-size: 0.85rem;
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
                            <h3 class="page-title">Manage Admission Inquiry</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Manage Admission Inquiry</h4>
                                        </div>

                                        <?php
                                        if (isset($_GET['pageno'])) {
                                            $pageno = $_GET['pageno'];
                                        } else {
                                            $pageno = 1;
                                        }
                                        $no_of_records_per_page = 15;
                                        $offset = ($pageno - 1) * $no_of_records_per_page;
                                        $ret = "SELECT ID FROM tblenroll";
                                        $query1 = $dbh->prepare($ret);
                                        $query1->execute();
                                        $total_rows = $query1->rowCount();
                                        $total_pages = ceil($total_rows / $no_of_records_per_page);
                                        $sql = "SELECT * FROM tblenroll ORDER BY ID DESC LIMIT $offset, $no_of_records_per_page";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        ?>

                                        <!-- Table for Desktop -->
                                        <div class="table-responsive  border rounded p-1 desktop-table d-none d-md-block">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Full Name</th>
                                                        <th>Standard</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Message</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) { ?>
                                                            <tr>
                                                                <td><?php echo $cnt++; ?></td>
                                                                <td><?php echo $row->Name; ?></td>
                                                                <td><?php echo $row->Standard; ?></td>
                                                                <td><?php echo $row->Phone; ?></td>
                                                                <td><?php echo $row->Email; ?></td>
                                                                <td><?php echo $row->Message; ?></td>
                                                                <td>
                                                                    <a href="?delid=<?php echo $row->ID; ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                                                    <?php if ($row->IsResponded == 1) { ?>
                                                                        <button class="btn btn-secondary btn-sm" disabled>Already Responded</button>
                                                                    <?php } else { ?>
                                                                        <a href="javascript:void(0);" onclick="openResponseModal('<?php echo $row->ID; ?>','<?php echo $row->Email; ?>')" class="btn btn-success btn-sm">Response</a>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                    <?php }
                                                    } else {
                                                        echo "<tr><td colspan='7' class='text-center'>No Records Found</td></tr>";
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Cards for Mobile -->
                                        <div class="d-block d-md-none ">
                                            <?php $cnt = 1;
                                            foreach ($results as $row) { ?>
                                                <div class="card mb-3 p-3 shadow-sm border border-left-primary">
                                                    <h5 class="card-title text-primary"><strong>Full Name:</strong> <?php echo htmlentities($row->Name); ?></h5>
                                                    <p class="mb-2"><strong>Standard:</strong> <?php echo htmlentities($row->Standard); ?></p>
                                                    <p class="mb-2"><strong>Phone:</strong> <?php echo htmlentities($row->Phone); ?></p>
                                                    <p class="mb-2"><strong>Email:</strong> <?php echo htmlentities($row->Email); ?></p>
                                                    <p class="mb-2"><strong>Message:</strong> <?php echo htmlentities($row->Message); ?></p>
                                                    <div>
                                                        <a href="?delid=<?php echo $row->ID; ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                                        <?php if ($row->IsResponded == 1) { ?>
                                                            <button class="btn btn-secondary btn-sm" disabled>Already Responded</button>
                                                        <?php } else { ?>
                                                            <a href="javascript:void(0);" onclick="openResponseModal('<?php echo $row->ID; ?>','<?php echo $row->Email; ?>')" class="btn btn-success btn-sm">Response</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="responseModal" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <form method="post">
                                                    <input type="hidden" name="respid" id="respid">
                                                    <input type="hidden" name="respemail" id="respemail">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Send Response</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <textarea name="respmsg" class="form-control" rows="5" placeholder="Enter your response message..." required></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="sendresponse" class="btn btn-primary">Send Mail</button>
                                                        </div>
                                                    </div>
                                                </form>
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

        <!-- Scripts -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <script>
            function openResponseModal(id, email) {
                document.getElementById('respid').value = id;
                document.getElementById('respemail').value = email;
                $('#responseModal').modal('show');
            }
        </script>
    </body>

    </html>
<?php } ?>