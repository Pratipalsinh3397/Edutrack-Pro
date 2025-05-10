<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="profile-image">
          <img class="img-xs rounded-circle" src="images/faces/face8.jpg" alt="profile image">
          <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
          <?php
          $aid = $_SESSION['sturecmsaid'];
          $sql = "SELECT * from tbladmin where ID=:aid";

          $query = $dbh->prepare($sql);
          $query->bindParam(':aid', $aid, PDO::PARAM_STR);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);

          $cnt = 1;
          if ($query->rowCount() > 0) {
            foreach ($results as $row) { ?>
              <p class="profile-name"><?php echo htmlentities($row->AdminName); ?></p>
              <p class="designation"><?php echo htmlentities($row->Email); ?></p><?php $cnt = $cnt + 1;
                                                                                }
                                                                              } ?>
        </div>
      </a>
    </li>

    <li class="nav-item nav-category">
      <span class="nav-link">Dashboard</span>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <span class="menu-title">Dashboard</span>
        <i class="icon-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">Class</span>
        <i class="icon-grid menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-class.php">Add Class</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-class.php">Manage Class</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
        <span class="menu-title">Students</span>
        <i class="icon-graduation menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic1">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-students.php">Add Students</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-students.php">Manage Students</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic2">
        <span class="menu-title">Teacher</span>
        <i class="icon-user-following menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic2">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-teacher.php">Add Teacher</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-teacher.php">Manage Teacher</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <span class="menu-title">Notice</span>
        <i class="icon-bell menu-icon"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-notice.php"> Add Notice </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-notice.php"> Manage Notice </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#at" aria-expanded="false" aria-controls="at">
        <span class="menu-title">Attendance</span>
        <i class="icon-check menu-icon"></i>
      </a>
      <div class="collapse" id="at">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-attandance.php"> Add Attendance </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-attandance.php"> Manage Attendance </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth1" aria-expanded="false" aria-controls="auth1">
        <span class="menu-title">Public Notice</span>
        <i class="icon-globe menu-icon"></i>
      </a>
      <div class="collapse" id="auth1">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-public-notice.php"> Add Public Notice </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-public-notice.php"> Manage Public Notice </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth2" aria-expanded="false" aria-controls="auth2">
        <span class="menu-title">Contact</span>
        <i class="icon-envelope-open menu-icon"></i>
      </a>
      <div class="collapse" id="auth2">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="manage-contact-us.php"> Manage Contact Us </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-admission-inq.php"> Manage Admission Inq. </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth22" aria-expanded="false" aria-controls="auth22">
        <span class="menu-title">Reports</span>
        <i class="icon-chart menu-icon"></i>
      </a>
      <div class="collapse" id="auth22">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="between-dates-reports.php"> Student </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-fees-payment.php"> Manage Fees Payment</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="view-feedback.php">
        <span class="menu-title">View Feedback</span>
        <i class="icon-speech menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="search.php">
        <span class="menu-title">Search</span>
        <i class="icon-magnifier menu-icon"></i>
      </a>
    </li>

  </ul>
</nav>