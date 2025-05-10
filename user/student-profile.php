<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsstuid'] == 0)) {
  header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edutrack Pro || View Student Profile</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css" />


  <!-- Custom Mobile Card Style -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
    }

    .mobile-profile {
      display: none;
      padding: 1rem;
    }

    @media (max-width: 767px) {
      .table-responsive {
        display: none;
      }

      .mobile-profile {
        display: block;
      }

      .profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 20px;
        margin-top: 20px;
        text-align: center;
      }

      .profile-img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #4e73df;
        margin-bottom: 15px;
      }

      .profile-name {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 6px;
      }

      .profile-id {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 18px;
      }

      .profile-info {
        text-align: left;
        margin-bottom: 12px;
        background-color: #f9fbfd;
        border-left: 5px solid #4e73df;
        padding: 10px 15px;
        border-radius: 6px;
      }

      .profile-info strong {
        display: block;
        font-weight: bold;
        font-size: 15px;
        color: #2c3e50;
        margin-bottom: 4px;
      }

      .profile-info span {
        font-size: 14px;
        color: #333;
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

          <!-- DESKTOP VIEW -->
          <div class="table-responsive">
            <table class="table table-bordered">
              <?php
              $sid = $_SESSION['sturecmsstuid'];
              $sql = "SELECT tblstudent.*, tblclass.ClassName, tblclass.Section FROM tblstudent 
                      JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                      WHERE tblstudent.StuID = :sid";
              $query = $dbh->prepare($sql);
              $query->bindParam(':sid', $sid, PDO::PARAM_STR);
              $query->execute();
              $results = $query->fetchAll(PDO::FETCH_OBJ);
              if ($query->rowCount() > 0) {
                foreach ($results as $row) {
              ?>
                  <tr class="table-primary text-center">
                    <th colspan="4" style="font-size: 20px;">Student Profile</th>
                  </tr>
                  <tr><th>Name</th><td><?php echo $row->StudentName; ?></td><th>Email</th><td><?php echo $row->StudentEmail; ?></td></tr>
                  <tr><th>Class</th><td><?php echo $row->ClassName . ' ' . $row->Section; ?></td><th>Gender</th><td><?php echo $row->Gender; ?></td></tr>
                  <tr><th>DOB</th><td><?php echo $row->DOB; ?></td><th>Student ID</th><td><?php echo $row->StuID; ?></td></tr>
                  <tr><th>Father Name</th><td><?php echo $row->FatherName; ?></td><th>Mother Name</th><td><?php echo $row->MotherName; ?></td></tr>
                  <tr><th>Contact</th><td><?php echo $row->ContactNumber; ?></td><th>Alternate Contact</th><td><?php echo $row->AltenateNumber; ?></td></tr>
                  <tr><th>Address</th><td><?php echo $row->Address; ?></td><th>Username</th><td><?php echo $row->UserName; ?></td></tr>
                  <tr>
                    <th>Profile Photo</th>
                    <td colspan="3">
                      <?php if (!empty($row->Image)) { ?>
                        <img src="../admin/images/<?php echo $row->Image; ?>" style="max-width:150px;" class="rounded img-thumbnail">
                      <?php } else {
                        echo "No image available.";
                      } ?>
                    </td>
                  </tr>
                  <tr><th>Date of Admission</th><td colspan="3"><?php echo $row->DateofAdmission; ?></td></tr>
              <?php
                }
              }
              ?>
            </table>
          </div>

          <!-- MOBILE PROFILE CARD -->
          <div class="mobile-profile">
            <?php
            if ($query->rowCount() > 0) {
              foreach ($results as $row) {
            ?>
                <div class="profile-card">
                  <img class="profile-img" src="../admin/images/<?php echo $row->Image ?: 'default.png'; ?>" alt="Profile Picture">
                  <div class="profile-name"><?php echo $row->StudentName; ?></div>
                  <div class="profile-id">Student ID: <?php echo $row->StuID; ?></div>

                  <div class="profile-info"><strong>Class</strong><span><?php echo $row->ClassName . ' ' . $row->Section; ?></span></div>
                  <div class="profile-info"><strong>Email</strong><span><?php echo $row->StudentEmail; ?></span></div>
                  <div class="profile-info"><strong>Gender</strong><span><?php echo $row->Gender; ?></span></div>
                  <div class="profile-info"><strong>Date of Birth</strong><span><?php echo $row->DOB; ?></span></div>
                  <div class="profile-info"><strong>Father's Name</strong><span><?php echo $row->FatherName; ?></span></div>
                  <div class="profile-info"><strong>Mother's Name</strong><span><?php echo $row->MotherName; ?></span></div>
                  <div class="profile-info"><strong>Contact Number</strong><span><?php echo $row->ContactNumber; ?></span></div>
                  <div class="profile-info"><strong>Alternate Number</strong><span><?php echo $row->AltenateNumber; ?></span></div>
                  <div class="profile-info"><strong>Address</strong><span><?php echo $row->Address; ?></span></div>
                  <div class="profile-info"><strong>Username</strong><span><?php echo $row->UserName; ?></span></div>
                  <div class="profile-info"><strong>Date of Admission</strong><span><?php echo $row->DateofAdmission; ?></span></div>
                </div>
            <?php
              }
            }
            ?>
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
</body>

</html>
<?php } ?>
