<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $stuid = $_POST['stuid'];
    $password = md5($_POST['password']);
    $sql = "SELECT StuID, ID, StudentClass FROM tblstudent WHERE (UserName=:stuid OR StuID=:stuid) AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['sturecmsstuid'] = $result->StuID;
            $_SESSION['sturecmsuid'] = $result->ID;
            $_SESSION['stuclass'] = $result->StudentClass;
        }

        if (!empty($_POST["remember"])) {
            setcookie("user_login", $_POST["stuid"], time() + (10 * 365 * 24 * 60 * 60));
            setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            if (isset($_COOKIE["user_login"])) {
                setcookie("user_login", "", time() - 3600);
                if (isset($_COOKIE["userpassword"])) {
                    setcookie("userpassword", "", time() - 3600);
                }
            }
        }
        $_SESSION['login'] = $_POST['stuid'];
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Edutrack Pro | Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-card {
      max-width: 100%;
      width: 100%;
      padding: 2rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    @media (min-width: 576px) {
      .login-card {
        width: 90%;
      }
    }

    @media (min-width: 768px) {
      .login-card {
        width: 400px;
      }
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

  <div class="login-card">
    <h4 class="fw-bold text-center">Student Edutrack Pro</h4>
    <p class="text-muted text-center mb-4">Sign in to continue</p>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Student ID / Username</label>
        <input type="text" class="form-control" name="stuid" required
               placeholder="Enter your Student ID or Username"
               value="<?php if (isset($_COOKIE['user_login'])) { echo $_COOKIE['user_login']; } ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" name="password" required
                 placeholder="Enter your password"
                 value="<?php if (isset($_COOKIE['userpassword'])) { echo $_COOKIE['userpassword']; } ?>">
          <button class="btn btn-outline-secondary toggle-password" type="button">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="remember"
               <?php if (isset($_COOKIE['user_login'])) { echo "checked"; } ?>>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $(".toggle-password").click(function () {
        var input = $(this).prev("input");
        var icon = $(this).find("i");
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
