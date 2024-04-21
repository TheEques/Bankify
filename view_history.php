<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Include database connection code and other necessary files
include 'config.php';

// Fetch transaction history based on user's email
$user_email = $_SESSION['user_email'];
$query = "SELECT type, amount, date FROM transaction_history WHERE email = '$user_email'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <style>
        h1{
          display: flex;
          justify-content: center;
          align-items: center;
          margin-bottom: 40px;
          font-size: 3.4rem;
          font-family: sans-serif;
          color: blue;
        }
        table {
            width: 60%;
            margin-top: 10px;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: 20rem;
            text-transform: uppercase;
        }
        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            background-color: #ffffff;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #888888;
        }
        .go_back{
          font-size: 1.4rem;
          text-decoration: none;
          color: blue;
          font-weight: 600;
          display: flex;
          justify-content: center;
          align-items: center;
        }
    </style>
</head>
<body>
    <h1>Transaction History</h1>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date and time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No transaction history found.</p>
    <?php endif; ?>
    <a class="go_back" href="index.php">Go back to Internet Banking</a>
</body>
</html>