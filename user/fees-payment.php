<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = 'rzp_test_URusGU7I9JN59g';
$api_secret = 'HaCBIlbIdWpDtzIFbYzT9W1F';
$api = new Api($api_key, $api_secret);

$sid = $_SESSION['sturecmsstuid'];
$sql = "SELECT tblstudent.StudentName,tblstudent.StudentEmail,tblstudent.ContactNumber, tblstudent.StudentClass, tblstudent.StuID, tblclass.ClassName, tblclass.Section, tblclass.fees 
        FROM tblstudent 
        JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
        WHERE tblstudent.StuID = :sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$student = $query->fetch(PDO::FETCH_OBJ);

$remainingSql = "SELECT remainingfees FROM feespayment WHERE stuID = :sid ORDER BY ID DESC LIMIT 1";
$remainingQuery = $dbh->prepare($remainingSql);
$remainingQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
$remainingQuery->execute();
$remainingResult = $remainingQuery->fetch(PDO::FETCH_OBJ);
$currentTotalFees = $remainingResult ? $remainingResult->remainingfees : $student->fees;

$historySql = "SELECT * FROM feespaymenthistory WHERE stuID = :sid ORDER BY ID DESC";
$historyQuery = $dbh->prepare($historySql);
$historyQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
$historyQuery->execute();
$paymentHistory = $historyQuery->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edutrack Pro || Fees Payment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css" />
  <style>
    @media (max-width: 576px) {
      .form-group label, .card-title {
        font-size: 14px;
      }
      .mobile-card {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        margin-bottom: 1rem;
      }
      .mobile-card p {
        margin-bottom: 0.4rem;
        font-size: 0.9rem;
      }
      .desktop-history {
        display: none;
      }
    }

    @media (min-width: 577px) {
      .mobile-history {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include_once('includes/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title text-center text-md-left">Fees Payment</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Fees Payment</li>
              </ol>
            </nav>
          </div>

          <div class="row">
            <div class="col-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php if ($currentTotalFees == 0): ?>
                    <div class="alert alert-success text-center">🎉 <strong>Your Fees Already Paid</strong></div>
                  <?php else: ?>
                    <h4 class="card-title text-center mb-4">Pay Your Fees</h4>
                    <form method="post" id="paymentForm" action="payment_success.php">
                      <div class="row">
                        <div class="form-group col-md-6 col-12">
                          <label>Student ID</label>
                          <input type="text" name="stdId" value="<?= $student->StuID ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-6 col-12">
                          <label>Student Name</label>
                          <input type="text" name="sname" value="<?= $student->StudentName ?>" class="form-control" readonly>
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-md-6 col-12">
                          <label>Class</label>
                          <input type="text" name="cname" value="<?= $student->ClassName ?> <?= $student->Section ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-6 col-12">
                          <label>Total Fees</label>
                          <input type="number" id="total_fees" name="cfees" value="<?= $currentTotalFees ?>" class="form-control" readonly>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Pay Fees</label>
                        <input type="number" id="feesPay" name="cpfees" class="form-control" required min="1" max="<?= $currentTotalFees ?>">
                      </div>

                      <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remark" class="form-control" rows="4" required></textarea>
                      </div>

                      <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id">
                      <input type="hidden" id="student_id" name="student_id" value="<?= $student->StuID ?>">
                      <input type="hidden" id="paid_fees" name="paid_fees">
                      <input type="hidden" id="remaining_fees" name="remaining_fees">

                      <div class="text-center">
                        <button type="button" id="rzp-button1" class="btn btn-primary w-100">Pay Now</button>
                      </div>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <?php if (count($paymentHistory) > 0): ?>
            <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center mb-3">Payment History</h4>

                    <!-- Desktop View -->
                    <div class="table-responsive desktop-history">
                      <table class="table table-bordered table-sm">
                        <thead>
                          <tr>
                            <th>Payment ID</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Remarks</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($paymentHistory as $row): ?>
                            <tr>
                              <td><?= htmlspecialchars($row->paymentID) ?></td>
                              <td>₹<?= htmlentities($row->paidfees) ?></td>
                              <td>₹<?= htmlentities($row->remainingfees) ?></td>
                              <td><?= htmlentities($row->remark) ?></td>
                              <td><?= htmlentities($row->paymentDate) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="mobile-history">
                      <?php foreach ($paymentHistory as $row): ?>
                        <div class="mobile-card">
                          <p><strong>Payment ID:</strong> <?= htmlspecialchars($row->paymentID) ?></p>
                          <p><strong>Paid:</strong> ₹<?= htmlentities($row->paidfees) ?></p>
                          <p><strong>Remaining:</strong> ₹<?= htmlentities($row->remainingfees) ?></p>
                          <p><strong>Remarks:</strong> <?= htmlentities($row->remark) ?></p>
                          <p><strong>Date:</strong> <?= htmlentities($row->paymentDate) ?></p>
                        </div>
                      <?php endforeach; ?>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <?php include_once('includes/footer.php'); ?>
      </div>
    </div>
  </div>

  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    document.getElementById('rzp-button1')?.addEventListener('click', function(e) {
      e.preventDefault();
      let amount = parseFloat(document.getElementById('feesPay').value);
      let totalFees = parseFloat(document.getElementById('total_fees').value);

      if (!amount || amount <= 0 || amount > totalFees) {
        alert("Please enter a valid amount between 1 and " + totalFees);
        return;
      }

      let remainingFees = Math.max(0, totalFees - amount);
      fetch('create_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount })
      })
      .then(res => res.json())
      .then(data => {
        let options = {
          key: "<?= $api_key ?>",
          amount: amount * 100,
          currency: "INR",
          name: "Edutrack Pro",
          description: "Fees Payment",
          order_id: data.order_id,
          handler: function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('paid_fees').value = amount;
            document.getElementById('remaining_fees').value = remainingFees;
            document.getElementById('paymentForm').submit();
          },
          prefill: {
            name: "<?= $student->StudentName ?>",
            email: "<?= $student->StudentEmail ?>",
            contact: "<?= $student->ContactNumber ?>"
          },
          theme: { color: "#3399cc" }
        };
        new Razorpay(options).open();
      })
      .catch(error => console.error('Payment initiation error:', error));
    });
  </script>

  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/select2/select2.min.js"></script>
  <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
</body>
</html>