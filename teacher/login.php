<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT ID FROM tblteacher WHERE UserName=:username and Password=:password";
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
      setcookie("user_login", "", time() - 3600);
      setcookie("userpassword", "", time() - 3600);
    }

    $_SESSION['login'] = $_POST['username'];
    echo "<script>document.location ='dashboard.php';</script>";
  } else {
    echo "<script>alert('Invalid Details');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Teacher Edutrack Pro | Login</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #d0eaff, #f0f4f8);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .card {
      max-width: 400px;
      width: 100%;
      border: none;
      border-radius: 1rem;
    }
    .btn-outline-secondary {
      border-radius: 0 .375rem .375rem 0;
    }
  </style>
</head>
<body>

<div class="card shadow p-4 bg-white">
  <h4 class="text-center fw-bold mb-2">Teacher Edutrack Pro</h4>
  <p class="text-center text-muted mb-4">Sign in to continue</p>

  <form method="POST" novalidate>
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Enter your username" required
        value="<?php if(isset($_COOKIE['user_login'])) { echo $_COOKIE['user_login']; } ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <div class="input-group">
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required
          value="<?php if(isset($_COOKIE['userpassword'])) { echo $_COOKIE['userpassword']; } ?>">
        <button class="btn btn-outline-secondary toggle-password" type="button">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" name="remember" class="form-check-input" id="rememberMe"
        <?php if(isset($_COOKIE['user_login'])) echo "checked"; ?>>
      <label class="form-check-label" for="rememberMe">Keep me signed in</label>
    </div>

    <button type="submit" name="login" class="btn btn-success w-100 mb-3">Login</button>

    <div class="text-center mb-2">
      <a href="forgot-password.php" class="text-decoration-none">Forgot password?</a>
    </div>

    <a href="../index.php" class="btn btn-primary w-100">
      <i class="bi bi-house-door"></i> Back to Home
    </a>
  </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(".toggle-password").click(function () {
    const input = $(this).siblings("input");
    const icon = $(this).find("i");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
      icon.removeClass("bi-eye").addClass("bi-eye-slash");
    } else {
      input.attr("type", "password");
      icon.removeClass("bi-eye-slash").addClass("bi-eye");
    }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
