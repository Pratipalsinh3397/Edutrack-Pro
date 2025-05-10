<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php
    $aid = $_SESSION['sturecmsaid'];
    $sql = "SELECT * from tblteacher where ID=:aid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':aid', $aid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
      foreach ($results as $row) {
    ?>
        <li class="nav-item nav-profile">
          <a href="#" class="nav-link">
            <div class="profile-image">
              <img class="img-xs rounded-circle" src="../admin/images/<?php echo $row->Image; ?>" alt="profile image">
              <div class="dot-indicator bg-success"></div>
            </div>
            <div class="text-wrapper">
              <p class="profile-name"><?php echo htmlentities($row->TeacherName); ?></p>
              <p class="designation"><?php echo htmlentities($row->Email); ?></p>
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
        <i class="icon-screen-desktop menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#hw" aria-expanded="false" aria-controls="hw">
        <span class="menu-title">Homework</span>
        <i class="icon-note menu-icon"></i>
      </a>
      <div class="collapse" id="hw">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-homework.php">Add Homework</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-homeworks.php">Manage Homework</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#mt" aria-expanded="false" aria-controls="mt">
        <span class="menu-title">Material</span>
        <i class="icon-folder-alt menu-icon"></i>
      </a>
      <div class="collapse" id="mt">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-material.php">Add Material</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-material.php">Manage Material</a></li>
          <li class="nav-item"> <a class="nav-link" href="add-video.php">Add Video</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-video.php">Manage Video</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="bw-report-hw.php"> Homework </a></li>
        </ul>
      </div>
    </li>

  </ul>
</nav>
