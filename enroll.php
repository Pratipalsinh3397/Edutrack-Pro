<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enroll Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>
<body>

<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4">Enroll Now</h2>
    <form action="enroll-action.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Standard</label>
        <select name="standard" class="form-select" required>
          <option value="">-- Select Class --</option>
          <?php
          for ($i = 1; $i <= 12; $i++) {
            echo "<option value='$i'>Standard $i</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" maxlength="10" minlength="10" pattern="[0-9]{10}" required >
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control" rows="4"></textarea>
      </div>

      <button type="submit" class="btn btn-danger w-100">Submit</button>
    </form>
  </div>
</section>

</body>
</html> -->
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>

<?php include_once('includes/header.php'); ?>
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4 fs-2 fw-bold">Enroll Now</h2>

    <form action="enroll-action.php" method="POST" class="p-3 p-md-4 shadow rounded bg-white">

      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Standard</label>
        <select name="standard" class="form-select" required>
          <option value="">-- Select Class --</option>
          <?php
          for ($i = 1; $i <= 12; $i++) {
            echo "<option value='$i'>Standard $i</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" maxlength="10" minlength="10" pattern="[0-9]{10}" placeholder="Enter 10 digit phone number" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control" rows="4" placeholder="Your message (optional)"></textarea>
      </div>

      <button type="submit" class="btn btn-danger w-100">Submit</button>
    </form>
  </div>
</section>

<?php include("includes/footer.php") ?>
