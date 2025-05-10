<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $sql = "SELECT ID FROM tbladmin WHERE UserName=:username and Password=:password";
  $query = $dbh->prepare($sql);
  $query->bindParam(':username', $username, PDO::PARAM_STR);
  $query->bindParam(':password', $password, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  if ($query->rowCount() > 0) {
    foreach ($results as $result) {
      $_SESSION['sturecmsaid'] = $result->ID;
    }

    if (!empty($_POST["remember"])) {
      setcookie("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60));
      setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
    } else {
      if (isset($_COOKIE["user_login"])) {
        setcookie("user_login", "", time() - 3600);
        if (isset($_COOKIE["userpassword"])) {
          setcookie("userpassword", "", time() - 3600);
        }
      }
    }
    $_SESSION['login'] = $_POST['username'];
    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
  } else {
    echo "<script>alert('Invalid Details');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Edutrack Pro | Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
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
        padding: 1.5rem;
        margin: 0 1rem;
      }
    }
  </style>
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

  <div class="login-card shadow">
    <h4 class="text-center fw-bold mb-1">Admin Edutrack Pro</h4>
    <p class="text-center text-muted mb-4">Sign in to continue</p>

    <form method="POST" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" placeholder="Enter your username" required 
          value="<?php if(isset($_COOKIE['user_login'])) { echo $_COOKIE['user_login']; } ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" name="password" placeholder="Enter your password" required
            value="<?php if(isset($_COOKIE['userpassword'])) { echo $_COOKIE['userpassword']; } ?>">
          <button class="btn btn-outline-secondary toggle-password" type="button">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="remember" 
          <?php if(isset($_COOKIE['user_login'])) { echo 'checked'; } ?>>
        <label class="form-check-label">Keep me signed in</label>
      </div>

      <button type="submit" class="btn btn-success w-100" name="login">Login</button>

      <div class="text-center mt-3">
        <a href="forgot-password.php" class="text-decoration-none">Forgot password?</a>
      </div>

      <div class="mt-3">
        <a href="../index.php" class="btn btn-primary w-100">
          <i class="bi bi-house-door"></i> Back to Home
        </a>
      </div>
    </form>
  </div>

  <script>
    $(document).ready(function () {
      $(".toggle-password").click(function () {
        const input = $(this).prev("input");
        const icon = $(this).find("i");
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          icon.removeClass("bi-eye").addClass("bi-eye-slash");
        } else {
          input.attr("type", "password");
          icon.removeClass("bi-eye-slash").addClass("bi-eye");
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
