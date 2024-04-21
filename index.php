<?php
include 'config.php';

session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];

    $query = "SELECT * FROM internet_banking WHERE email = '$user_email'";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows > 0) {
        $user_details = $result->fetch_assoc();
        // print_r($user_details);
    } else {
        echo "No details found.";
    }
} else {
    header("Location: page.php");
    exit();
}

// Logout
if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}


// Check balance
if (isset($_POST['check_balance'])) {
  $query_balance = "SELECT total_money FROM transaction WHERE email = '$user_email'";
  $result_balance = mysqli_query($conn, $query_balance);
  if ($result_balance) {
      $row = mysqli_fetch_assoc($result_balance);
      $balance = $row['total_money'];
      echo $balance;
      exit(); // Terminate the script after sending the balance
  } else {
      echo "Error: Unable to fetch balance.";
      exit();
  }
}
?>

<?php
include 'config.php';

// session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];

    // Fetch user details from the login database
    $query = "SELECT * FROM users1 WHERE email = '$user_email'";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows > 0) {
        // Retrieve the stored B_PIN from the internet_banking table
        $query = "SELECT B_PIN FROM internet_banking WHERE email = '$user_email'";
        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_B_PIN = $row['B_PIN'];
        } else {
            echo "No B_PIN found.";
            exit(); // Exit if B_PIN is not found
        }

        // Check if the beneficiary_account is submitted through the form
        if(isset($_POST['beneficiary_account'])){
            $beneficiary_account = $_POST['beneficiary_account'];
            // Fetch user details from the transaction database if available
            $query = "SELECT * FROM transaction WHERE email = '$user_email'";
            $result = mysqli_query($conn, $query);
            if ($result->num_rows == 0) {
                // If user details not found in the transaction database, insert initial record
                $insert_query = "INSERT INTO transaction (email, beneficiary_account, total_money) VALUES ('$user_email', '$beneficiary_account', 0)";
                mysqli_query($conn, $insert_query);
            }
        } else {
            // echo "Beneficiary account number is not provided.";
        }
    } else {
        echo "No details found.";
    }
} else {
    // Redirect to login page if user session is not set
    header("Location: page.php");
    exit();
}

// Handle depositing money
if (isset($_POST['deposit'])) {
  $amount = $_POST['deposit_amount'];
  $beneficiary_account = $_POST['beneficiary_account'];
  $entered_B_PIN = isset($_POST['B_PIN']) ? $_POST['B_PIN'] : '';

  // Debugging statements to check B_PIN values
  // echo "Entered B_PIN: " . $entered_B_PIN . "<br>";
  // echo "Stored B_PIN: " . $stored_B_PIN . "<br>";

  // Compare entered B_PIN with stored B_PIN
  if ($entered_B_PIN === $stored_B_PIN) {
      $transaction_type = 'deposit';
      $date = date('Y-m-d H:i:s');

      // Insert transaction into transaction history table
      $insert_query = "INSERT INTO transaction_history (email, type, amount, date) VALUES ('$user_email', '$transaction_type', '$amount', '$date')";
      mysqli_query($conn, $insert_query);

      // Assuming you have a function depositMoney($email, $amount, $conn) to handle depositing money
      $deposit_result = depositMoney($user_email, $amount, $conn);
      if ($deposit_result === true) {
          echo "<script>alert('Deposit successful!!');</script>";
      } else {
          echo "<script>alert('Deposit failed. Please try again.');</script>";
      }
  } else {
      echo "<script>alert('Invalid B_PIN. Please try again.');</script>";
  }
}


// Handle withdrawing money
if (isset($_POST['withdraw'])) {
  $amount = $_POST['withdraw_amount'];
  $entered_B_PIN = isset($_POST['B_PIN']) ? $_POST['B_PIN'] : '';

  // Compare entered B_PIN with stored B_PIN
  if ($entered_B_PIN === $stored_B_PIN) {
      $transaction_type = 'withdraw';
      $date = date('Y-m-d H:i:s');

      // Insert transaction into transaction history table
      $insert_query = "INSERT INTO transaction_history (email, type, amount, date) VALUES ('$user_email', '$transaction_type', '$amount', '$date')";
      mysqli_query($conn, $insert_query);

      // Assuming you have a function withdrawMoney($email, $amount, $conn) to handle withdrawing money
      $withdraw_result = withdrawMoney($user_email, $amount, $conn);
      if ($withdraw_result === true) {
          echo "<script>alert('Withdraw successful!!');</script>";
      } else {
          echo "<script>alert('Withdraw failed. Please try again.');</script>";
      }
  } else {
      echo "<script>alert('Invalid B_PIN. Please try again.');</script>";
  }
}



// Function to deposit money into the user's account
function depositMoney($email, $amount, $conn) {
    $sql = "UPDATE transaction SET total_money = total_money + $amount WHERE email = '$email'";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to withdraw money from the user's account
function withdrawMoney($email, $amount, $conn) {
    $sql = "SELECT total_money FROM transaction WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_money = $row['total_money'];
        if ($total_money >= $amount) {
            $sql = "UPDATE transaction SET total_money = total_money - $amount WHERE email = '$email'";
            if (mysqli_query($conn, $sql)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
?>



















<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Internet Banking</title>
    <link rel="stylesheet" href="Style2.css" />
    <link rel="stylesheet" href="que2.css" />
    <link rel="icon" href="android-chrome-512x512.png" />
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
    <script defer src="js/script.js"></script>
  </head>
  <body>
    <header class="header sticky">
      <img src="Bankify (2).png" class="logo" alt="logo" />
      <nav class="main-nav">
        <ul class="main-nav-list">
          <li class="main-nav-link">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="currentColor"
              class="head-icon"
            >
              <path
                d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z"
              />
              <path
                d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z"
              />
            </svg>
            Home
          </li>
          <li class="main-nav-link">
            <a href="#price" class="main-nav-link"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="head-icon"
              >
                <path
                  d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z"
                />
              </svg>
              About us</a
            >
          </li>
          <li>
            <a class="main-nav-link1 nav-cta" href="login.php">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="head-icon"
              >
                <path
                  d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z"
                />
                <path
                  fill-rule="evenodd"
                  d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z"
                  clip-rule="evenodd"
                />
                <path
                  d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z"
                />
              </svg>
              LoanPredicto</a
            >
          </li>
          <div class="dropdown">
    <button class="dropbtn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user">
            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div class="dropdown-content">
        <button class="dropdown-contenta" onclick="logout()">Logout</button>
    </div>
</div>

        </ul>
      </nav>
      <button class="btn-mob-nav">
        <ion-icon class="icon-mob" name="menu-outline"></ion-icon>
        <ion-icon class="icon-mob" name="close-outline"></ion-icon>
      </button>
    </header>

    <section class="section-hero">
      <h1 class="main-heading">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          class="hero-icon"
        >
          <path
            fill-rule="evenodd"
            d="M9 4.5a.75.75 0 0 1 .721.544l.813 2.846a3.75 3.75 0 0 0 2.576 2.576l2.846.813a.75.75 0 0 1 0 1.442l-2.846.813a3.75 3.75 0 0 0-2.576 2.576l-.813 2.846a.75.75 0 0 1-1.442 0l-.813-2.846a3.75 3.75 0 0 0-2.576-2.576l-2.846-.813a.75.75 0 0 1 0-1.442l2.846-.813A3.75 3.75 0 0 0 7.466 7.89l.813-2.846A.75.75 0 0 1 9 4.5ZM18 1.5a.75.75 0 0 1 .728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 0 1 0 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 0 1-1.456 0l-.258-1.036a2.625 2.625 0 0 0-1.91-1.91l-1.036-.258a.75.75 0 0 1 0-1.456l1.036-.258a2.625 2.625 0 0 0 1.91-1.91l.258-1.036A.75.75 0 0 1 18 1.5ZM16.5 15a.75.75 0 0 1 .712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 0 1 0 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 0 1-1.422 0l-.395-1.183a1.5 1.5 0 0 0-.948-.948l-1.183-.395a.75.75 0 0 1 0-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0 1 16.5 15Z"
            clip-rule="evenodd"
          />
        </svg>
        Welcome to <span>Internet Banking</span> Services of Bankify!!
      </h1>
      <div class="acc-card">
        <h1 class="acc-heading">Account Details:</h1>
        <div class="acc-tag">
          Account No:
          <span><?php echo $user_details['account_no']; ?></span>
        </div>
        <div class="acc-tag">
          Name of Account holder:
          <span><?php echo $user_details['name']; ?></span>
        </div>
        <div class="acc-tag">
          Date of Birth:
          <span><?php echo $user_details['dob']; ?></span>
        </div>
        <div class="acc-tag">
          IFSC Code:
          <span><?php echo $user_details['IFSC_code']; ?></span>
        </div>
        <div class="acc-tag">
          Branch Name:
          <span><?php echo $user_details['Branch']; ?></span>
        </div>
        <!-- Button to check balance -->
          <button id="checkBalanceBtn">Check Balance</button>
          <span id="balanceResult"></span>
        
      </div>  
      <div class="credit-section">
        <div class="credit-head">
          <h1 class="acc-heading1">Credit Section</h1>
        </div>
        <div class="credit-card">
          <h1 class="credit-card-heading">
            LoanPredicto:Loan Prediction using Machine Learning
          </h1>
          <p class="card-text">
            Explore the future of lending with LoanPredicto—where machine
            learning meets financial foresight. Click to read more and discover
            how our predictive algorithms redefine the loan experience.
          </p>
          <div class="btn">
            <a class="card-btn nav-cta1" href="#">Learn More</a>
          </div>
        </div>

       <!-- Deposit Money Modal -->
<div id="depositModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="dep-head">Deposit Money</h2>
    <form id="depositForm" action="" method="post">
      <div class="form-group">
        <label for="beneficiary_account">Beneficiary's Account Number:</label>
        <input type="text" id="beneficiary_account" name="beneficiary_account" placeholder="BBXXXXXXXX"  required autocomplete="off">
      </div>
      <div class="form-group">
        <label for="deposit_amount">Amount to Deposit:</label>
        <input type="number" id="deposit_amount" name="deposit_amount" placeholder=" Enter amount" required>
        <br>
        <label for="B_PIN">B_PIN:</label>
        <input type="password" id="deposit_amount" name="B_PIN"  required>
      </div>
      <div class="form-group">
        <label>
          <input type="checkbox" id="declaration" required>
          I declare that the provided information is correct.
        </label>
      </div>
      <button type="submit" class="submit-btn" name="deposit">Deposit</button>
    </form>
  </div>
</div>

<!-- Deposit Money Button -->
<div class="deposit">
  <h1 class="credit-card-heading">Deposit Money to Account</h1>
  <p class="card-text">
    Easily boost your account balance with our seamless Deposit Money
    feature, ensuring quick and secure transactions.
  </p>
  <div class="btn1">
    <a class="card-btn nav-cta1" href="" id="depositBtn">Deposit Money</a>
  </div>
</div>


      <!-- Debit Section -->
      <div class="credit-section">
        <div class="credit-head">
          <h1 class="acc-heading1">Debit Section</h1>
        </div>

        <!-- Withdraw Money Modal -->
<div id="withdrawModal" class="modal1">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="withdraw-head">Withdraw Money</h2>
    <form id="withdrawForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="form-group">
        <label for="withdraw_amount">Amount to Withdraw:</label>
        <input type="number" id="withdraw_amount" name="withdraw_amount" placeholder="Enter amount" required>
        <br>
        <label for="B_PIN">B_PIN:</label>
        <input type="password" id="deposit_amount" name="B_PIN"  required>
      </div>
      <button type="submit" class="submit-btn" name="withdraw">Withdraw</button>
    </form>
  </div>
</div>

<!-- Withdraw Money Button -->
<div class="deposit">
  <h1 class="credit-card-heading">Withdraw Money from Account</h1>
  <p class="card-text">
    Easily access and manage your funds with our Withdraw Money feature.
  </p>
  <div class="btn2">
    <a class="card-btn nav-cta1" href="#" id="withdrawBtn">Withdraw Money</a>
  </div>
</div>

        <div class="deposit">
          <h1 class="credit-card-heading">Send Money to another Account</h1>
          <p class="card-text">
            Experience hassle-free financial transactions: Send money to another
            account effortlessly with our user-friendly and secure platform.
          </p>
          <div class="btn1">
            <a class="card-btn nav-cta1" href="#">Send Money</a>
          </div>
        </div>
        <div class="deposit">
          <h1 class="credit-card-heading">Pay the Bills with Bankify</h1>
          <p class="card-text">
            Empower your financial freedom: Effortlessly settle bills using
            Bankify, making every transaction swift, secure, and stress-free.
          </p>
          <div class="btn1">
            <a class="card-btn nav-cta1" href="#">Pay the Bills</a>
          </div>
        </div>

        <div class="deposit">
          <h1 class="credit-card-heading">Transaction History</h1>
          <p class="card-text">View your transaction History.</p>
          <div class="btn2">
            <a class="card-btn nav-cta1" href="view_history.php">View History</a>
          </div>
        </div>
      </div>
      <!-- Profile -->
      <div class="profile-section">
        <div class="credit-head1">
          <h1 class="acc-heading1">Profile Management</h1>
        </div>
        <div class="profile-card">
          <h1 class="credit-card-heading1">Change Password of Account</h1>
          <div class="btn3">
            <a class="card-btn nav-cta2" href="#">Change Password</a>
          </div>
        </div>
        <div class="profile-card1">
          <h1 class="credit-card-heading1">Change B-PIN of Account</h1>
          <div class="btn3">
            <a class="card-btn nav-cta2" href="#">Change B-PIN</a>
          </div>
        </div>
      </div>

      <form method="post" >
        
          <button class="logout">

            <input type="submit" name="logout" value="Logout">
          </button>
        
      </form>
    </section>
    <script>
        // Check if the page was accessed via the browser's history (back button)
        window.onload = function() {
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.go(1);
            };
        };

        function logout() {
    // Perform logout functionality, e.g., redirect to logout page
    window.location.href = "page.php"; // Replace with your logout functionality

    // Manipulate the browser history to remove the dashboard page from the history stack
    window.history.pushState(null, null, "page.php");
}


    </script>
   
   <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Get the modal
    var modal = document.getElementById('depositModal');

    // Get the deposit button
    var depositBtn = document.getElementById("depositBtn");

    // When the user clicks the button, open the modal 
    depositBtn.addEventListener("click", function(event) {
      event.preventDefault(); // Prevent the default action of the link
      modal.style.display = "block";
    });

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x) or outside the modal, close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    // Ensure the modal is initially hidden
    modal.style.display = "none";
  });
</script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Get the withdraw modal
    var withdrawModal = document.getElementById('withdrawModal');

    // Get the withdraw button
    var withdrawBtn = document.getElementById("withdrawBtn");

    // When the user clicks the withdraw button, open the modal 
    withdrawBtn.addEventListener("click", function(event) {
      event.preventDefault(); // Prevent the default action of the link
      withdrawModal.style.display = "block";
    });

    // Get the <span> element that closes the withdraw modal
    var span = document.getElementsByClassName("close")[1]; // Make sure this index is correct for your HTML structure

    // When the user clicks on <span> (x) or outside the modal, close the withdraw modal
    span.onclick = function() {
      withdrawModal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == withdrawModal) {
        withdrawModal.style.display = "none";
      }
    }

    // Ensure the withdraw modal is initially hidden
    withdrawModal.style.display = "none";
  });
</script>

<script>
        document.getElementById("checkBalanceBtn").addEventListener("click", function() {
            // Make an AJAX request to fetch the user's balance
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Sending the POST request to the same PHP file
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var balance = xhr.responseText;
                    document.getElementById("balanceResult").innerText = "Balance: ₹" + balance;
                }
            };
            xhr.send("check_balance=true"); // Send the check_balance parameter
        });
    </script>


  </body>
</html>
