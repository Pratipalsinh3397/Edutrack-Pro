<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['stdId'];
    // $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_payment_id = trim($_POST['razorpay_payment_id']);
    $total_fees = $_POST['cfees'];
    $paid_fees = $_POST['cpfees'];
    $remaining_fees = $_POST['remaining_fees'];
    $remark = $_POST['remark']; // If you want to save remarks too (optional)

    try {
        // Check if record exists for student
        $check_sql = "SELECT COUNT(*) FROM feespayment WHERE stuID = :stdId";
        $check_stmt = $dbh->prepare($check_sql);
        $check_stmt->bindParam(':stdId', $student_id, PDO::PARAM_STR);
        $check_stmt->execute();
        $exists = $check_stmt->fetchColumn() > 0;

        if ($exists) {
            // Update existing row
            $sql = "UPDATE feespayment 
                    SET totalfees = :cfees, 
                        paidfees = :cpfees, 
                        remainingfees = :remaining_fees, 
                        remark = :remark, 
                        paymentID = :razorpay_payment_id
                    WHERE stuID = :stdId";

        } else {
            // Insert new row
            $sql = "INSERT INTO feespayment (stuID, totalfees, paidfees, remainingfees, paymentID, remark) 
                    VALUES (:stdId, :cfees, :cpfees, :remaining_fees, :razorpay_payment_id, :remark)";
        }

        $query = $dbh->prepare($sql);
        $query->bindParam(':stdId', $student_id, PDO::PARAM_STR);
        $query->bindParam(':cfees', $total_fees, PDO::PARAM_STR);
        $query->bindParam(':cpfees', $paid_fees, PDO::PARAM_STR);
        $query->bindParam(':remaining_fees', $remaining_fees, PDO::PARAM_STR);
        $query->bindParam(':remark', $remark, PDO::PARAM_STR);
        $query->bindParam(':razorpay_payment_id', $razorpay_payment_id, PDO::PARAM_STR);
        $query->execute();

        // ✅ Added: Fetch latest paymentDate from feespayment table
        $date_stmt = $dbh->prepare("SELECT paymentDate FROM feespayment WHERE stuID = :stdId ORDER BY ID DESC LIMIT 1");
        $date_stmt->bindParam(':stdId', $student_id, PDO::PARAM_STR);
        $date_stmt->execute();
        $payment_date = $date_stmt->fetchColumn();

        // ✅ Add paymentDate into history insert query
        $sql1 = "INSERT INTO feespaymenthistory (stuID, totalfees, paidfees, remainingfees, paymentID, remark, paymentDate) 
                 VALUES (:stdId, :cfees, :cpfees, :remaining_fees, :razorpay_payment_id, :remark, :payment_date)";

        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':stdId', $student_id, PDO::PARAM_STR);
        $query1->bindParam(':cfees', $total_fees, PDO::PARAM_STR);
        $query1->bindParam(':cpfees', $paid_fees, PDO::PARAM_STR);
        $query1->bindParam(':remaining_fees', $remaining_fees, PDO::PARAM_STR);
        $query1->bindParam(':remark', $remark, PDO::PARAM_STR);
        $query1->bindParam(':razorpay_payment_id', $razorpay_payment_id, PDO::PARAM_STR);
        $query1->bindParam(':payment_date', $payment_date, PDO::PARAM_STR); 
        $query1->execute();

        // Redirect to success page
        header("Location: success.html");
        exit();

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
