<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $stuname = $_POST['stuname'];
    $stuemail = $_POST['stuemail'];
    $stuclass = $_POST['stuclass'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $stuid = $_POST['stuid'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $connum = $_POST['connum'];
    $altconnum = $_POST['altconnum'];
    $address = $_POST['address'];
    $eid = $_GET['editid'];
    $sql = "update tblstudent set StudentName=:stuname,StudentEmail=:stuemail,StudentClass=:stuclass,Gender=:gender,DOB=:dob,StuID=:stuid,FatherName=:fname,MotherName=:mname,ContactNumber=:connum,AltenateNumber=:altconnum,Address=:address where ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':stuname', $stuname, PDO::PARAM_STR);
    $query->bindParam(':stuemail', $stuemail, PDO::PARAM_STR);
    $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':dob', $dob, PDO::PARAM_STR);
    $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':mname', $mname, PDO::PARAM_STR);
    $query->bindParam(':connum', $connum, PDO::PARAM_STR);
    $query->bindParam(':altconnum', $altconnum, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Student has been updated")</script>';
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edutrack Pro || Update Students</title>
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include_once('includes/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title"> Update Students </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Update Students Details</li>
              </ol>
            </nav>
          </div>

          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">Update Students Details</h4>
                  <hr />
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <?php
                    $eid = $_GET['editid'];
                    $sql = "SELECT tblstudent.StudentName,tblstudent.id as sid,tblstudent.StudentEmail,tblstudent.StudentClass,tblstudent.Gender,tblstudent.DOB,tblstudent.StuID,tblstudent.FatherName,tblstudent.MotherName,tblstudent.ContactNumber,tblstudent.AltenateNumber,tblstudent.Address,tblstudent.UserName,tblstudent.Password,tblstudent.Image,tblstudent.DateofAdmission,tblclass.ClassName,tblclass.Section from tblstudent join tblclass on tblclass.ID=tblstudent.StudentClass where tblstudent.ID=:eid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                      foreach ($results as $row) {
                    ?>
                      <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <h4>Student details</h4>
                          <hr />
                          <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" name="stuname" value="<?php echo htmlentities($row->StudentName); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Student Email</label>
                            <input type="email" name="stuemail" value="<?php echo htmlentities($row->StudentEmail); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Student Class</label>
                            <select name="stuclass" class="form-control" required>
                              <option value="<?php echo htmlentities($row->StudentClass); ?>">
                                <?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?>
                              </option>
                              <?php
                              $sql2 = "SELECT * from tblclass ";
                              $query2 = $dbh->prepare($sql2);
                              $query2->execute();
                              $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                              foreach ($result2 as $row1) {
                              ?>
                                <option value="<?php echo htmlentities($row1->ID); ?><?php echo htmlentities($row1->Section); ?>">
                                  <?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?>
                                </option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control" required>
                              <option value="<?php echo htmlentities($row->Gender); ?>"><?php echo htmlentities($row->Gender); ?></option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?php echo htmlentities($row->DOB); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Student ID</label>
                            <input type="text" name="stuid" value="<?php echo htmlentities($row->StuID); ?>" class="form-control" readonly>
                          </div>
                          <div class="form-group">
                            <label>Student Photo</label><br />
                            <img src="images/<?php echo $row->Image; ?>" class="img-fluid mb-2" width="100" height="100">
                            <a href="changeimage.php?editid=<?php echo $row->sid; ?>">Edit Image</a>
                          </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                          <h4>Parents/Guardian's details</h4>
                          <hr />
                          <div class="form-group">
                            <label>Father's Name</label>
                            <input type="text" name="fname" value="<?php echo htmlentities($row->FatherName); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Mother's Name</label>
                            <input type="text" name="mname" value="<?php echo htmlentities($row->MotherName); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" name="connum" value="<?php echo htmlentities($row->ContactNumber); ?>" class="form-control" maxlength="10" pattern="[0-9]+" required>
                          </div>
                          <div class="form-group">
                            <label>Alternate Contact Number</label>
                            <input type="text" name="altconnum" value="<?php echo htmlentities($row->AltenateNumber); ?>" class="form-control" maxlength="10" pattern="[0-9]+" required>
                          </div>
                          <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required><?php echo htmlentities($row->Address); ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h4>Login details</h4>
                          <div class="form-group">
                            <label>User Name</label>
                            <input type="text" name="uname" value="<?php echo htmlentities($row->UserName); ?>" class="form-control" readonly>
                          </div>
                          <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" value="<?php echo htmlentities($row->Password); ?>" class="form-control" readonly>
                          </div>
                          <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Update</button>
                        </div>
                      </div>
                    <?php }
                    } ?>
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