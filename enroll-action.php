<?php
include('includes/dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $standard = $_POST['standard'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  $sql = "INSERT INTO tblenroll (Name, Standard, Phone, Email, Message) VALUES (:name, :standard, :phone, :email, :message)";
  $query = $dbh->prepare($sql);
  $query->bindParam(':name', $name, PDO::PARAM_STR);
  $query->bindParam(':standard', $standard, PDO::PARAM_STR);
  $query->bindParam(':phone', $phone, PDO::PARAM_STR);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':message', $message, PDO::PARAM_STR);

  if ($query->execute()) {
    echo "<script>alert('Enrollment Inquiry Send Successful!'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Something went wrong!'); window.location.href='enroll.php';</script>";
  }
}
?>
