<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$response = ['status' => false, 'message' => '', 'step' => ''];

// Generate and send OTP
if (isset($_POST['send_otp'])) {
  $email = $_POST['email'];
  $username = $_POST['username'];

  $sql = "SELECT Email FROM tblteacher WHERE Email=:email AND UserName=:username";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':username', $username, PDO::PARAM_STR);
  $query->execute();

  if ($query->rowCount() > 0) {
    $_SESSION['otp'] = rand(100000, 999999);
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;

    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'typrojectphd@gmail.com';
      $mail->Password = 'qsktvvyjhhntufez';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;

      $mail->setFrom('typrojectphd@gmail.com', 'Edutrack Pro');
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = 'Password Reset OTP';
      $mail->Body = 'Your OTP for password reset is: <b>' . $_SESSION['otp'] . '</b>';

      $mail->send();
      $response = ['status' => true, 'message' => 'OTP sent to your email.', 'step' => 'otp'];
    } catch (Exception $e) {
      $response = ['status' => false, 'message' => 'Error sending OTP.'];
    }
  } else {
    $response = ['status' => false, 'message' => 'Email and Username do not match.'];
  }
  echo json_encode($response);
  exit();
}

// Verify OTP
if (isset($_POST['verify_otp'])) {
  if ($_SESSION['otp'] == $_POST['otp']) {
    $_SESSION['otp_verified'] = true;
    $response = ['status' => true, 'message' => 'OTP verified successfully.', 'step' => 'password'];
  } else {
    $response = ['status' => false, 'message' => 'Invalid OTP.'];
  }
  echo json_encode($response);
  exit();
}

// Reset Password
if (isset($_POST['reset_password'])) {
  if ($_SESSION['otp_verified']) {
    $newpassword = md5($_POST['newpassword']);
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];

    $sql = "UPDATE tblteacher SET Password=:newpassword WHERE Email=:email AND UserName=:username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $query->execute();

    session_unset();
    session_destroy();
    $response = ['status' => true, 'message' => 'Password successfully changed.'];
  } else {
    $response = ['status' => false, 'message' => 'Session expired. Please restart the process.'];
  }
  echo json_encode($response);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Teacher Edutrack Pro || Forgot Password</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    @media (max-width: 576px) {
      .card {
        margin: 1rem;
        padding: 1.5rem;
      }
      .form-label {
        font-size: 0.95rem;
      }
      .btn {
        font-size: 0.95rem;
      }
      .input-group .form-control,
      .input-group .btn {
        font-size: 0.95rem;
      }
    }
  </style>

  <script>
    $(document).ready(function () {
      $('#otpSection, #passwordSection, .loading').hide();

      $('#sendOtpBtn').click(function (e) {
        e.preventDefault();
        var email = $('#email').val();
        var username = $('#username').val();
        $('#sendOtpBtn').hide();
        $('.loading-email').show();

        $.post('forgot-password.php', {
          send_otp: true,
          email: email,
          username: username
        }, function (data) {
          var response = JSON.parse(data);
          alert(response.message);
          $('.loading-email').hide();
          if (response.status) {
            $('#emailSection').hide();
            $('#otpSection').fadeIn();
          } else {
            $('#sendOtpBtn').show();
          }
        });
      });

      $('#verifyOtpBtn').click(function (e) {
        e.preventDefault();
        var otp = $('#otp').val();
        $('#verifyOtpBtn').hide();
        $('.loading-otp').show();

        $.post('forgot-password.php', {
          verify_otp: true,
          otp: otp
        }, function (data) {
          var response = JSON.parse(data);
          alert(response.message);
          $('.loading-otp').hide();
          if (response.status) {
            $('#otpSection').hide();
            $('#passwordSection').fadeIn();
          } else {
            $('#verifyOtpBtn').show();
          }
        });
      });

      $('#resetPasswordBtn').click(function (e) {
        e.preventDefault();
        var newpassword = $('#newpassword').val();
        var confirmpassword = $('#confirmpassword').val();
        if (newpassword !== confirmpassword) {
          alert("Passwords do not match!");
          return;
        }
        $('#resetPasswordBtn').hide();
        $('.loading-password').show();

        $.post('forgot-password.php', {
          reset_password: true,
          newpassword: newpassword
        }, function (data) {
          var response = JSON.parse(data);
          alert(response.message);
          $('.loading-password').hide();
          if (response.status) {
            window.location.href = 'login.php';
          } else {
            $('#resetPasswordBtn').show();
          }
        });
      });

      $('.toggle-password').click(function () {
        var input = $(this).prev('input');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
          input.attr('type', 'text');
          icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
          input.attr('type', 'password');
          icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
      });
    });
  </script>
</head>

<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 w-100 shadow" style="max-width: 100%; width: 100%; max-width: 400px;">
      <div class="text-center mb-3">
        <h4 class="fw-bold">Teacher Edutrack Pro</h4>
        <p class="text-muted">Recover Password</p>
      </div>

      <!-- Email Section -->
      <div id="emailSection">
        <label class="form-label">Enter your username:</label>
        <input type="text" id="username" class="form-control mb-3" placeholder="Username" required />

        <label class="form-label">Enter your email:</label>
        <input type="email" id="email" class="form-control mb-3" placeholder="Email Address" required />

        <button id="sendOtpBtn" class="btn btn-success w-100">Get OTP</button>
        <div class="loading loading-email text-center mt-2 text-primary">Sending OTP...</div>
      </div>

      <!-- OTP Section -->
      <div id="otpSection">
        <label class="form-label">Enter OTP:</label>
        <input type="text" id="otp" class="form-control mb-3" placeholder="Enter OTP" required />
        <button id="verifyOtpBtn" class="btn btn-success w-100">Verify OTP</button>
        <div class="loading loading-otp text-center mt-2 text-primary">Verifying OTP...</div>
      </div>

      <!-- Password Reset Section -->
      <div id="passwordSection">
        <label class="form-label">New Password:</label>
        <div class="input-group mb-2">
          <input type="password" id="newpassword" class="form-control" placeholder="New Password" required />
          <button class="btn btn-outline-secondary toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <label class="form-label">Confirm Password:</label>
        <div class="input-group mb-3">
          <input type="password" id="confirmpassword" class="form-control" placeholder="Confirm Password" required />
          <button class="btn btn-outline-secondary toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <button id="resetPasswordBtn" class="btn btn-success w-100">Reset Password</button>
        <div class="loading loading-password text-center mt-2 text-primary">Resetting Password...</div>
      </div>

      <div class="text-center mt-3">
        <a href="login.php" class="text-decoration-none">Sign In</a>
      </div>
    </div>
  </div>
</body>
</html>
