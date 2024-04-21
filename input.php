<?php
session_start();
$credit_history = isset($_SESSION['credit_history']) ? $_SESSION['credit_history'] : "N/A";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <!-- Your HTML head content -->
    <title>Loan Approval Prediction</title>
</head>
<body>
    <section class="form-group">
        <form action="/predict" method="post">
            <div class="container">
                <h1 class="center">LoanPredictO</h1>

                <label for="gender">Gender:</label>
                <!-- Your form fields -->
                
                <label for="credit_history">Credit History:</label>
                <span><?php echo $credit_history; ?></span>
                <!-- Display credit history here -->

                <!-- Hidden input for credit history -->
                <input type="hidden" name="credit_history" value="<?php echo $credit_history; ?>" />

                <input type="submit" class="registerbtn" value="Predict Loan Approval" />
            </div>
        </form>
    </section>
</body>
</html>
