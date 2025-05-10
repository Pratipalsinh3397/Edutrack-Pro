<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php
    $uid = $_SESSION['sturecmsuid'];
    $sql = "SELECT * from tblstudent where ID=:uid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
      foreach ($results as $row) { ?>
        <li class="nav-item nav-profile d-flex flex-column align-items-center text-center mt-3">
          <a href="#" class="nav-link p-0">
            <div class="profile-image mb-2">
              <img class="img-xs rounded-circle" src="../admin/images/<?php echo $row->Image; ?>" alt="profile image">
              <div class="dot-indicator bg-success"></div>
            </div>
            <div class="text-wrapper">
              <p class="profile-name mb-0"><?php echo htmlentities($row->StudentName); ?></p>
              <p class="designation text-muted small"><?php echo htmlentities($row->StudentEmail); ?></p>
            </div>
          </a>
        </li>
    <?php }
    } ?>

    <li class="nav-item nav-category">
      <span class="nav-link">Dashboard</span>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <span class="menu-title">Dashboard</span>
        <i class="icon-speedometer menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="view-notice.php">
        <span class="menu-title">View Notice</span>
        <i class="icon-bell menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="homework.php">
        <span class="menu-title">View Homework</span>
        <i class="icon-pencil menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="material.php">
        <span class="menu-title">View Material</span>
        <i class="icon-notebook menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="video.php">
        <span class="menu-title">View Video</span>
        <i class="icon-camrecorder menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="view-attandance.php">
        <span class="menu-title">View Attendance</span>
        <i class="icon-calendar menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="fees-payment.php">
        <span class="menu-title">Fees Payment</span>
        <i class="icon-wallet menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
  <a class="nav-link" href="feedback.php">
    <span class="menu-title">Feedback</span>
    <i class="icon-speech menu-icon"></i>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" href="manage-feedback.php">
    <span class="menu-title">Manage Feedback</span>
    <i class="icon-note menu-icon"></i>
  </a>
</li>
  </ul>
</nav>
