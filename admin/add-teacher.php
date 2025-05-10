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
    if (isset($_POST['submit'])) {
        $thname = $_POST['thname'];
        $themail = $_POST['themail'];
        $thclass = $_POST['thclass'];
        $gender = $_POST['gender'];

        $thid = $_POST['thid'];
        $connum = $_POST['connum'];
        $uname = $_POST['uname'];
        $originalPassword = $_POST['password'];
        $password = md5($originalPassword);
        $image = $_FILES["image"]["name"];
        $ret = "select UserName from tblteacher where UserName=:uname || TeacherID=:thid";
        $query = $dbh->prepare($ret);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':thid', $thid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() == 0) {
            $extension = substr($image, strlen($image) - 4, strlen($image));
            $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Logo has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
            } else {
                $image = md5($image) . time() . $extension;
                move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $image);
                $sql = "insert into tblteacher(TeacherName,UserName,MobileNumber,Email,TeacherClass,Gender,Image,TeacherID,Password)values(:thname,:uname,:connum,:themail,:thclass,:gender,:image,:thid,:password)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':thname', $thname, PDO::PARAM_STR);
                $query->bindParam(':themail', $themail, PDO::PARAM_STR);
                $query->bindParam(':thclass', $thclass, PDO::PARAM_STR);
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':thid', $thid, PDO::PARAM_STR);
                $query->bindParam(':connum', $connum, PDO::PARAM_STR);
                $query->bindParam(':uname', $uname, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':image', $image, PDO::PARAM_STR);
                $query->execute();
                $LastInsertId = $dbh->lastInsertId();
                if ($LastInsertId > 0) {
                    echo '<script>alert("Teacher has been added.")</script>';
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'typrojectphd@gmail.com';
                        $mail->Password = 'qsktvvyjhhntufez';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port = 465;

                        $mail->setFrom('typrojectphd@gmail.com', 'Login Details');
                        $mail->addAddress($themail, $thname);

                        $mail->isHTML(true);
                        $mail->Subject = 'Dear teacher, Your Login Details';
                        $mail->Body = 'Dear ' . $thname . ',<br><br>'
                            . 'Your account has been created successfully. Below are your login credentials:<br><br>'
                            . '<b>Username:</b> ' . $uname . '<br>'
                            . '<b>Password:</b> ' . $originalPassword . '<br><br>'
                            . 'Please keep this information safe and do not share it with anyone.<br><br>'
                            . 'Best Regards,<br>'
                            . 'Edutrack Pro Team';

                        $mail->send();
                        echo '<script>alert("Confirmation Email Sent Successfully!");</script>';
                    } catch (Exception $e) {
                        echo "<script>alert('Email could not be sent. Error: {$mail->ErrorInfo}');</script>";
                    }
                    echo "<script>window.location.href ='add-teacher.php'</script>";
                } else {
                    echo '<script>alert("Something Went Wrong. Please try again")</script>';
                }
            }
        } else {
            echo "<script>alert('Username or Teacher Id  already exist. Please try again');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Add Teachers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />

    <style>
        @media (max-width: 768px) {
            .form-group {
                margin-bottom: 1rem;
            }

            .row .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .page-header h3,
            .card-title {
                text-align: center !important;
            }
        }

        @media (max-width: 576px) {
            .btn {
                width: 100%;
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
                        <h3 class="page-title"> Add Teachers </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> Add Teachers</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title text-center">Add Teacher</h3>
                                    <hr />
                                    <form class="forms-sample container-fluid" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <h4>Teacher details</h4>
                                                <hr />
                                                <div class="form-group">
                                                    <label>Teacher Name</label>
                                                    <input type="text" name="thname" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Teacher Email</label>
                                                    <input type="text" name="themail" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Teacher Class</label>
                                                    <select name="thclass" class="form-control" required>
                                                        <option value="">Select Class</option>
                                                        <?php
                                                        $sql2 = "SELECT * from tblclass";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->execute();
                                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($result2 as $row1) {
                                                        ?>
                                                            <option value="<?php echo htmlentities($row1->ID); ?>">
                                                                <?php echo htmlentities($row1->ClassName . ' ' . $row1->Section); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Teacher Photo</label>
                                                    <input type="file" name="image" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <br />
                                                <hr />
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="">Choose Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Teacher ID</label>
                                                    <input type="text" name="thid" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Number</label>
                                                    <input type="text" name="connum" class="form-control" required maxlength="10" pattern="[0-9]+">
                                                </div>
                                            </div>
                                        </div>
                                        <h4>Login details</h4>
                                        <div class="form-group">
                                            <label>User Name</label>
                                            <input type="text" name="uname" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Add</button>
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

    <!-- scripts -->
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
