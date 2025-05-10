<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>

<?php include_once('includes/header.php'); ?>


<!-- <div class="d-flex shadow" style="height:700px;background:linear-gradient(-45deg,#dc3545,50%,transparent 50%);">
  <div class="container-fluid my-auto">
    <div class="row">
      <div class="col-lg-6 my-auto">
        <h1 class="display-1  font-weight-bold">Dream Education</h1>
        <p>Make Your Dream Become a Reality .</p>
        <a href="user/login.php" class="btn btn-lg btn-danger ">Student Login</a>
      </div>
      <div class="col-lg-6">
        <div class="col-lg-6 mx-auto card shadow-lg">
          <div class="card-body">
            <h3 class="mx-auto text-center font-weight-bold">Public Notice Board</h3>
            <div class="testimonials">
              <div class="container">
                <div class="testimonial-nfo">
                  <marquee style="height:350px;" direction="up" onmouseover="this.stop();" onmouseout="this.start();" class="font-weight-bold">
                    <?php
                    $sql = "SELECT * from tblpublicnotice";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                      foreach ($results as $row) {               ?>


                        <a href="view-public-notice.php?viewid=<?php echo htmlentities($row->ID); ?>" target="_blank" style="color:black;text-decoration:none;">
                          <?php echo htmlentities($row->NoticeTitle); ?>(<?php echo htmlentities($row->CreationDate); ?>)</a>
                        <hr /><br />

                    <?php $cnt = $cnt + 1;
                      }
                    } ?>
                  </marquee>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
<!-- HERO SECTION WITH BACKGROUND IMAGE -->
<div class="hero-section d-flex align-items-center justify-content-center text-center text-white"
  style="min-height: 100vh; background: url('./assets/img/banner.jpg') no-repeat center center/cover;">
  <div class="container">
    <h1 class="display-3 fw-bold mb-3">Dream Education</h1>
    <p class="lead mb-4">Make your dream become a reality.</p>
    <a href="user/login.php" class="btn btn-lg btn-danger px-4 py-2">Student Login</a>
  </div>
</div>



<!-- About 7 - Bootstrap Brain Component -->
<section class="py-3 py-md-5 py-xl-8" id="about">
  <!-- <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
        <h2 class="mb-4 display-5 text-center">About Us</h2>
        <p class="text-secondary mb-5 text-center lead fs-4">We believe in the power of teamwork and collaboration. Our diverse experts work tirelessly to deliver innovative solutions tailored to our clients' needs.</p>
        <hr class="w-50 mx-auto mb-5 mb-xl-9 border-dark-subtle">
      </div>
    </div>
  </div> -->
  <div class="">
    <h2 class="font-weight-bold text-center mb-5">About Us</h2>
  </div>

  <div class="container">
    <div class="row gy-4 gy-lg-0 align-items-lg-center">
      <!-- <div class="col-12 col-lg-6">
        <img class="img-fluid rounded border border-dark" loading="lazy" src="./assets/img/logo.png" alt="About Us">
      </div> -->
      <div class="col-12 col-lg-6 mb-4 mb-lg-0 d-flex justify-content-center">
        <img class="img-fluid rounded border border-dark" loading="lazy" src="./assets/img/logo.png" alt="About Us" style="max-width: 100%; height: auto;">
      </div>
      <div class="col-12 col-lg-6 col-xxl-6">
        <div class="row justify-content-lg-end">
          <div class="col-12 col-lg-11">
            <div class="about-wrapper">
              <p class="lead mb-4 mb-md-5 text-black">Dream Education empowers students to achieve their goals through quality education and personalized guidance. With our tagline, "Make Your Dream Become Reality," we inspire learners to unlock their potential. Our institution focuses on innovation, excellence, and a nurturing environment to shape bright futures and foster lifelong success. At Dream Education, your aspirations take flight!</p>
              <div class="row gy-4 mb-4 mb-md-5">
                <div class="col-12 col-md-6">
                  <div class="card border border-dark">
                    <div class="card-body p-4">
                      <h3 class="display-5 fw-bold text-danger text-center mb-2">10+</h3>
                      <p class="fw-bold text-center m-0">Qualified Experts Team</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="card border border-dark">
                    <div class="card-body p-4">
                      <h3 class="display-5 fw-bold text-danger text-center mb-2">500+</h3>
                      <p class="fw-bold text-center m-0">Satisfied Students</p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Our Class -->
<section class="py-5 bg-light" id="classes">
  <div class="">
    <h2 class="font-weight-bold text-center mb-5">Our Classes</h2>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-3 mb-5">
        <div class="card">
          <img src="./assets/img/std1To3.jpg" class="img-fluid rounded-top" alt="">
          <div class="card-body">
            <b>Standard : 1 to 3</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 10000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>
      <div class="col-lg-3 mb-5">
        <div class="card">
          <img src="./assets/img/std4To6.jpg" class="img-fluid rounded-top" alt="">
          <div class="card-body">
            <b>Standard : 4 to 6</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 14000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>
      <div class="col-lg-3 mb-5">
        <div class="card">
          <img src="./assets/img/std7.jpg" class="img-fluid rounded-top" alt="">
          <div class="card-body">
            <b>Standard : 7</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 15000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>


          </div>
        </div>
      </div>
      <div class="col-lg-3  mb-5">
        <div class="card">
          <img src="./assets/img/std8.jpg" class="img-fluid rounded-top  " alt="">
          <div class="card-body">
            <b>Standard : 8</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 17000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm ">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>

      <div class="col-lg-3  mb-5">
        <div class="card">
          <img src="./assets/img/std9.jpg" class="img-fluid rounded-top  " alt="">
          <div class="card-body">
            <b>Standard : 9</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 20,000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm ">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>
      <div class="col-lg-3  mb-5">
        <div class="card">
          <img src="./assets/img/std10.jpg" class="img-fluid rounded-top  " alt="">
          <div class="card-body">
            <b>Standard : 10</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 25000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm ">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>
      <div class="col-lg-3  mb-5">
        <div class="card">
          <img src="./assets/img/std11.jpg" class="img-fluid rounded-top  " alt="">
          <div class="card-body">
            <b>Standard : 11</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 28000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm ">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>
      <div class="col-lg-3  mb-5">
        <div class="card">
          <img src="./assets/img/std12.jpg" class="img-fluid rounded-top  " alt="">
          <div class="card-body">
            <b>Standard : 12</b> <br>
            <p class="card-text ">
              <b>Duration : 1 Year </b><br>
              <b>Price : 30,000 -\Rs</b>
            </p>
            <!-- <button class="btn btn-block btn-danger btn-sm ">Enroll Now </button> -->
            <button onclick="window.location.href='enroll.php?standard=1 to 3'" class="btn btn-block btn-danger btn-sm">Enroll Now</button>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<section class="py-5 bg-light " id="event">
  <div class="container my-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-5">Our Events</h2>
      <!-- <p class="text-muted">Meet Our Teachers</p> -->
    </div>

    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">

        <!-- Event 1 -->
        <div class="carousel-item active">
          <div class="card mx-auto" style="max-width: 800px;">
            <img src="images/event5.jpg" class="d-block w-100" alt="Science Fair">
            <div class="card-body text-center">
              <h5 class="card-title">Essay Writing competition</h5>
              <p class="card-text">An exciting Essay Writing Competition where students get the chance to showcase their writing skills, critical thinking, and creativity.</p>
              <p class="text-muted">Date: August 20, 2024</p>
            </div>
          </div>
        </div>

        <!-- Event 2 -->
        <div class="carousel-item">
          <div class="card mx-auto" style="max-width: 800px;">
            <img src="images/event2.jpg" class="d-block w-100" alt="Sports Day">
            <div class="card-body text-center">
              <h5 class="card-title">Academic Trip 2024 </h5>
              <p class="card-text">"A thrilling day filled with exciting competitions, fun games, and unstoppable school spirit! From energetic cheers to unforgettable moments, it was a celebration of talent, teamwork, and togetherness</p>
              <p class="text-muted">Date: October 24, 2024</p>
            </div>
          </div>
        </div>

        <!-- Event 3 -->
        <div class="carousel-item">
          <div class="card mx-auto" style="max-width: 800px;">
            <img src="images/event4.jpg" class="d-block w-100" alt="Art Festival">
            <div class="card-body text-center">
              <h5 class="card-title">Farewell and Annual function</h5>
              <p class="card-text">Celebrate creativity, music, dance, and cultural diversity with us.</p>
              <p class="text-muted">Date: February 13, 2025</p>
            </div>
          </div>
        </div>

      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</section>


<!-- Contact Start -->
<!-- Contact 5 - Bootstrap Brain Component -->
<section class="py-3 py-md-5 py-xl-8 bg-gray" id="contact">
  <!-- <div class="container">
    <div class="row">
      <div class="col-12 col-md-10 col-lg-8">
        <h3 class="fs-5 mb-2 text-secondary text-uppercase">Contact</h3>
        <h2 class="display-5 mb-4 mb-md-5 mb-xl-8">We're always on the lookout to work with new clients. Please get in touch in one of the following ways.</h2>
      </div>
    </div>
  </div> -->



  <div class="text-center mb-5">
    <h2 class="font-weight-bold  ">Contact Us</h2>
    <!--  <p class = "text-muted">Meet Our Teachers</p> -->
  </div>
  <!-- DATA INSERT IN TO DB -->
  <?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // if (!isset($_SESSION['sturecmsaid'])) {
  //     header('location:logout.php');
  //     exit();
  // }

  if (isset($_POST['submit'])) {
    try {
      $dbh = new PDO("mysql:host=localhost;dbname=studentmsdb", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]);

      // Sanitize inputs
      $fullname = htmlspecialchars(trim($_POST['fullname']));
      $phoneno = htmlspecialchars(trim($_POST['phoneno']));
      $message = htmlspecialchars(trim($_POST['message']));
      $subject = htmlspecialchars(trim($_POST['subject']));
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
      }

      $sql = "INSERT INTO tblcontactus (fullname, phoneno, subject, message, email) 
                VALUES (:fullname, :phoneno, :subject, :message, :email)";
      $query = $dbh->prepare($sql);
      $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
      $query->bindParam(':phoneno', $phoneno, PDO::PARAM_STR);
      $query->bindParam(':message', $message, PDO::PARAM_STR);
      $query->bindParam(':subject', $subject, PDO::PARAM_STR);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->execute();

      if ($dbh->lastInsertId() > 0) {
        echo '<script>alert("Message submitted successfully.");</script>';
        echo "<script>window.location.href ='index.php';</script>";
        exit();
      } else {
        echo '<script>alert("Something went wrong. Please try again.");</script>';
      }
    } catch (PDOException $e) {
      echo '<script>alert("Database Error: ' . $e->getMessage() . '");</script>';
    }
  }
  ?>


  <div class="container">
    <div class="row gy-4 gy-md-5 gy-lg-0 align-items-md-center">
      <div class="col-12 col-lg-6">
        <div class="border overflow-hidden">

          <form method="POST" enctype="multipart/form-data" class="bg-white  shadow-lg">
            <div class="row gy-4 gy-xl-5 p-4 p-xl-5">
              <div class="col-12">
                <label for="fullname" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope text-white" viewBox="0 0 16 16">
                      <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                    </svg>
                  </span>
                  <input type="email" class="form-control" id="email" name="email" value="" required>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group ">
                  <span class="input-group-text bg-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone text-light" viewBox="0 0 16 16">
                      <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                    </svg>
                  </span>
                  <input type="tel" class="form-control" id="phone" name="phoneno" value="">
                </div>
              </div>
              <div class="col-12">
                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" value="" required>
              </div>
              <div class="col-12">
                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
              </div>
              <div class="col-12">
                <div class="d-grid">
                  <button class="btn text-white btn-lg bg-danger" type="submit" name="submit">Send Message</button>
                </div>
              </div>
            </div>
          </form>

        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="row justify-content-xl-center">
          <div class="col-12 col-xl-11">
            <div class="mb-4 mb-md-5">
              <div class="mb-3 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-geo text-danger" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z" />
                </svg>
              </div>
              <div>
                <h4 class="mb-2">Office</h4>
                <p class="mb-2">Please visit us to have a discussion.</p>
                <hr class="w-50 mb-3 border-dark-subtle">
                <address class="m-0 text-secondary">21-22, Bhaktinandan Shopping Centre, Opp. Bhagirath Intercity, Bapa Sitaram Chowk, New Naroda, Ahmedabad - 382345</address>
              </div>
            </div>
            <div class="row mb-sm-4 mb-md-5">
              <div class="col-12 col-sm-6">
                <div class="mb-4 mb-sm-0">
                  <div class="mb-3 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-telephone-outbound text-danger" viewBox="0 0 16 16">
                      <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511zM11 .5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-4.146 4.147a.5.5 0 0 1-.708-.708L14.293 1H11.5a.5.5 0 0 1-.5-.5z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="mb-2">Phone</h4>
                    <p class="mb-2">Please speak with us directly.</p>
                    <hr class="w-75 mb-3 border-dark-subtle">
                    <p class="mb-0">
                      <a class="link-secondary text-decoration-none" href="tel:+918780063963">+91 87800 63963</a>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="mb-4 mb-sm-0">
                  <div class="mb-3 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-envelope-at text-danger" viewBox="0 0 16 16">
                      <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z" />
                      <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="mb-2">Email</h4>
                    <p class="mb-2">Please write to us directly.</p>
                    <hr class="w-75 mb-3 border-dark-subtle">
                    <p class="mb-0">
                      <a class="link-secondary text-decoration-none" href="mailto:dream.education.78@gmail.com">dream.education.78@gmail.com</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <div class="mb-3 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-alarm text-danger" viewBox="0 0 16 16">
                  <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z" />
                  <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z" />
                </svg>
              </div>
              <div>
                <h4 class="mb-2">Opening Hours</h4>
                <p class="mb-2">Explore our business opening hours.</p>
                <hr class="w-50 mb-3 border-dark-subtle">
                <div class="d-flex mb-1">
                  <p class="text-secondary fw-bold mb-0 me-5">Mon - Sat</p>
                  <p class="text-secondary mb-0 ml-1">9am - 8pm</p>
                </div>
                <div class="d-flex">
                  <p class="text-secondary fw-bold mb-0 me-5">Sun</p>
                  <p class="text-secondary mb-0 ml-5"> 8am - 12pm</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include("includes/footer.php") ?>