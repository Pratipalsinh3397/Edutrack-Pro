<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
// echo $_SESSION['login']; // to check session is started or not?
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edutrack Pro</title>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">

  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/contacts/contact-5/assets/css/contact-5.css">
  <style>
    @media (max-width: 576px) {
      marquee a {
        font-size: 14px;
      }
    }

    .carousel-item img {
      height: 400px;
      object-fit: cover;
    }

    .card {
      border: none;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      background-color: #ffffff;
    }

    .carousel .carousel-control-prev-icon,
    .carousel .carousel-control-next-icon {
      width: 50px;
      height: 50px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
      height: 5rem;
      width: 5rem;
      background-size: 70% 70%;
    }
  </style>

  <script type="application/x-javascript">
    addEventListener("load", function() {
      setTimeout(hideURLbar, 0);
    }, false);

    function hideURLbar() {
      window.scrollTo(0, 1);
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js\jquery-1.11.0.min.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $(".scroll").click(function(event) {
        event.preventDefault();
        $('html,body').animate({
          scrollTop: $(this.hash).offset().top
        }, 900);
      });
    });
  </script>
</head>

<body>

  <!--Navbar -->
  <nav class="navbar navbar-expand-lg bg-danger text-light px-2 sticky-top" style="height: 60px;">
    <div class="container-fluid d-flex align-items-center flex-wrap">

      <!-- Notice Label -->

      <!-- Marquee Section -->
      <div class="flex-grow-1 d-flex align-items-center overflow-auto" style="overflow: hidden;">
        <!-- <span class="fw-bold text-light me-3 mb-0 text-nowrap d-none d-sm-block" >
        Public Notice:
      </span> -->
        <span class="fw-bold text-light me-2 mb-0 text-nowrap" style="white-space: nowrap;">
          Public Notice:
        </span>
        <marquee direction="right" onmouseover="this.stop();" onmouseout="this.start();" class="fw-bold w-100 mb-0 text-truncate">
          <?php
          $sql = "SELECT * from tblpublicnotice";
          $query = $dbh->prepare($sql);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);
          $total = count($results);
          $i = 0;

          if ($total > 0) {
            foreach ($results as $row) {
              $i++;
          ?>
              <a href="view-public-notice.php?viewid=<?php echo htmlentities($row->ID); ?>" target="_blank"
                class="text-white text-decoration-none mx-2">
                <?php echo htmlentities($row->NoticeTitle); ?> (<?php echo htmlentities($row->CreationDate); ?>)
              </a>

              <?php if ($i < $total) { ?>
                <span style="border-left: 1px solid white; height: 20px; display: inline-block; vertical-align: middle; margin: 0 10px;"></span>
              <?php } ?>
          <?php
            }
          }
          ?>
        </marquee>
      </div>
    </div>
  </nav>





  <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container-fluid">

      <!-- Logo -->
      <a class="navbar-brand ms-3" href="#">
        <img src="./assets/img/logo.png" height="70" width="70" alt="EP Logo" loading="lazy" />
      </a>

      <!-- Toggler for Mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <!-- Left Menu -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#classes">Classes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#event">Events</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contact Us</a>
          </li>
        </ul>

        <!-- Right Side Login Dropdown -->
        <ul class="navbar-nav ms-auto nav-flex-icons">
          <li class="nav-item dropdown">
            <div class="dropdown">
              <button class="btn btn-danger dropdown-toggle px-4 py-2 me-5 mt-2  mt-lg-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Login
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="user/login.php">Student</a></li>
                <li><a class="dropdown-item" href="teacher/login.php">Teacher</a></li>
              </ul>
            </div>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <!--/.Navbar -->