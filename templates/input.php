<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit History</title>
</head>
<body>
    <h1>Credit History</h1>
    <?php
    // Retrieve credit history from query parameter
    $credit_history = $_GET['credit_history'];

    // Check if credit history is not empty
    if ($credit_history) {
        echo "<p>Credit History: $credit_history</p>";
    } else {
        echo "<p>No credit history found.</p>";
    }
    ?>
</body>
</html>
