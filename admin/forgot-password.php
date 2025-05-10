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

// Send OTP
if (isset($_POST['send_otp'])) {
  $email = $_POST['email'];
  $sql = "SELECT Email FROM tbladmin WHERE Email=:email";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->execute();

  if ($query->rowCount() > 0) {
    $_SESSION['otp'] = rand(100000, 999999);
    $_SESSION['email'] = $email;

    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'typrojectphd@gmail.com';
      $mail->Password = 'qsktvvyjhhntufez'; // Use Gmail App Password
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
    $response = ['status' => false, 'message' => 'Email not found.'];
  }
  echo json_encode($response);
  exit();
}

// Verify OTP
if (isset($_POST['verify_otp'])) {
  if ($_SESSION['otp'] == $_POST['otp']) {
    $_SESSION['otp_verified'] = true;
    $response = ['status' => true, 'message' => 'OTP verified.', 'step' => 'password'];
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

    $sql = "UPDATE tbladmin SET Password=:newpassword WHERE Email=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $query->execute();

    session_unset();
    session_destroy();
    $response = ['status' => true, 'message' => 'Password successfully changed.'];
  } else {
    $response = ['status' => false, 'message' => 'Session expired.'];
  }
  echo json_encode($response);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forgot Password - Edutrack Pro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    .login-card {
      width: 100%;
      max-width: 420px;
      padding: 2rem;
      border-radius: 15px;
      background-color: #fff;
    
    }

    @media (max-width: 576px) {
      .login-card {
        padding: 1rem;
      }
    }

    .loading {
      font-size: 0.9rem;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-card shadow p-4 w-100">
      <div class="text-center mb-3">
        <h4 class="fw-bold">Edutrack Pro</h4>
        <p class="text-muted">Recover Admin Password</p>
      </div>

      <!-- Email Section -->
      <div id="emailSection">
        <label class="form-label">Enter your email:</label>
        <input type="email" id="email" class="form-control mb-2" required>
        <button id="sendOtpBtn" class="btn btn-primary w-100">Send OTP</button>
        <div class="loading loading-email text-primary text-center mt-2 d-none">Sending OTP...</div>
      </div>

      <!-- OTP Section -->
      <div id="otpSection" class="d-none">
        <label class="form-label">Enter OTP:</label>
        <input type="text" id="otp" class="form-control mb-2" required>
        <button id="verifyOtpBtn" class="btn btn-primary w-100">Verify OTP</button>
        <div class="loading loading-otp text-primary text-center mt-2 d-none">Verifying...</div>
      </div>

      <!-- Password Reset Section -->
      <div id="passwordSection" class="d-none">
        <label class="form-label">New Password:</label>
        <div class="input-group mb-2">
          <input type="password" id="newpassword" class="form-control" required>
          <button class="btn btn-outline-secondary toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <label class="form-label">Confirm Password:</label>
        <div class="input-group mb-3">
          <input type="password" id="confirmpassword" class="form-control" required>
          <button class="btn btn-outline-secondary toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <button id="resetPasswordBtn" class="btn btn-primary w-100">Reset Password</button>
        <div class="loading loading-password text-primary text-center mt-2 d-none">Resetting...</div>
      </div>

      <div class="text-center mt-3">
        <a href="login.php" class="text-decoration-none">Back to Sign In</a>
      </div>
    </div>
  </div>

  <script>
    $(function () {
      $('#sendOtpBtn').click(function () {
        const email = $('#email').val();
        $('#sendOtpBtn').hide();
        $('.loading-email').removeClass('d-none');
        $.post('forgot-password.php', { send_otp: true, email }, function (res) {
          const response = JSON.parse(res);
          alert(response.message);
          $('.loading-email').addClass('d-none');
          if (response.status) {
            $('#emailSection').hide();
            $('#otpSection').removeClass('d-none');
          } else {
            $('#sendOtpBtn').show();
          }
        });
      });

      $('#verifyOtpBtn').click(function () {
        const otp = $('#otp').val();
        $('#verifyOtpBtn').hide();
        $('.loading-otp').removeClass('d-none');
        $.post('forgot-password.php', { verify_otp: true, otp }, function (res) {
          const response = JSON.parse(res);
          alert(response.message);
          $('.loading-otp').addClass('d-none');
          if (response.status) {
            $('#otpSection').hide();
            $('#passwordSection').removeClass('d-none');
          } else {
            $('#verifyOtpBtn').show();
          }
        });
      });

      $('#resetPasswordBtn').click(function () {
        const newpassword = $('#newpassword').val();
        const confirmpassword = $('#confirmpassword').val();
        if (newpassword !== confirmpassword) {
          alert('Passwords do not match!');
          return;
        }
        $('#resetPasswordBtn').hide();
        $('.loading-password').removeClass('d-none');
        $.post('forgot-password.php', { reset_password: true, newpassword }, function (res) {
          const response = JSON.parse(res);
          alert(response.message);
          if (response.status) {
            window.location.href = 'login.php';
          } else {
            $('#resetPasswordBtn').show();
            $('.loading-password').addClass('d-none');
          }
        });
      });

      $('.toggle-password').click(function () {
        const input = $(this).prev('input');
        const icon = $(this).find('i');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        icon.toggleClass('bi-eye bi-eye-slash');
      });
    });
  </script>
</body>
</html>
