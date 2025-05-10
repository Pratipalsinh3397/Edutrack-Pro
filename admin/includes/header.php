<style>
  .navbar-menu-wrapper {
    position: relative;
    z-index: 1050 !important;
    overflow: visible !important;
  }

  .navbar-nav-right {
    overflow: visible !important;
    position: relative;
    z-index: 1060;
  }

  .dropdown-menu {
    right: 0 !important;
    left: auto !important;
    top: 100% !important;
    margin-top: 10px;
    z-index: 1070 !important;
    display: block;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease;
  }

  .user-dropdown:hover .dropdown-menu,
  .user-dropdown:focus-within .dropdown-menu {
    visibility: visible;
    opacity: 1;
  }

  .dropdown-header img {
    object-fit: cover;
  }

  @media (max-width: 768px) {
    .dropdown-menu {
      right: 10px !important;
    }

    .navbar-menu-wrapper {
      flex-direction: column;
    }
  }
</style>

<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row flex-wrap" style="background-color: #181824; overflow: visible;">
  <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center px-3">
    <!-- Desktop Logo -->
    <a class="navbar-brand brand-logo d-none d-lg-block" href="dashboard.php">
      <strong style="color: white; font-size: 22px; white-space: nowrap;">Edutrack Pro</strong>
    </a>

    <!-- Mobile Logo -->
    <a class="d-block d-lg-none" href="dashboard.php" style="color: white; font-weight: bold; font-size: 14px; text-decoration: none; white-space: nowrap; margin-left: 50px;">
      Edutrack Pro
    </a>
  </div>

  <?php
  $aid = $_SESSION['sturecmsaid'];
  $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':aid', $aid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);

  if ($query->rowCount() > 0) {
    foreach ($results as $row) {
  ?>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between flex-grow-1 flex-wrap px-3">
        <h6 class="mb-0 font-weight-medium d-none d-md-inline" style="color: white;">
          <?php echo htmlentities($row->AdminName); ?> - Welcome to dashboard!
        </h6>

        <ul class="navbar-nav navbar-nav-right d-flex align-items-center ml-auto">
          <li class="nav-item dropdown user-dropdown position-relative">
            <a class="nav-link dropdown-toggle d-flex align-items-center" id="UserDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="images/faces/face8.jpg" alt="Profile image">
              <span class="font-weight-normal ml-2" style="color: white;"><?php echo htmlentities($row->AdminName); ?></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img rounded-circle mb-2" style="width: 80px; height: 80px;" src="images/faces/face8.jpg" alt="Profile image">
                <p class="mb-1 mt-1 font-weight-bold"><?php echo htmlentities($row->AdminName); ?></p>
                <p class="font-weight-light text-muted mb-0"><?php echo htmlentities($row->Email); ?></p>
              </div>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="profile.php">
                <i class="dropdown-item-icon icon-user text-primary"></i> My Profile
              </a>
              <a class="dropdown-item" href="forgot-password.php">
                <i class="dropdown-item-icon icon-energy text-primary"></i> Change Password
              </a>
              <a class="dropdown-item" href="logout.php">
                <i class="dropdown-item-icon icon-power text-primary"></i> Sign Out
              </a>
            </div>
          </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu text-white"></span>
        </button>
      </div>
  <?php
    }
  }
  ?>
</nav>