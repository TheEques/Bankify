<?php
include 'config.php';
session_start();

// Function to update transaction data and credit history for a user
function updateTransactionData($email, $conn) {
    // Fetch user's transaction data from the database
    $query = "SELECT SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END) AS total_deposits, " .
             "SUM(CASE WHEN type = 'withdraw' THEN amount ELSE 0 END) AS total_withdrawals " .
             "FROM transaction_history WHERE email='{$email}'";
    $transaction_result = mysqli_query($conn, $query);
    
    if ($transaction_result) {
        $transaction_data = mysqli_fetch_assoc($transaction_result);

        // Calculate credit history
        $total_deposits = $transaction_data['total_deposits'] ?? 0;
        $total_withdrawals = $transaction_data['total_withdrawals'] ?? 0;
        $credit_history = ($total_withdrawals > 0) ? ($total_deposits / $total_withdrawals > 100 ? 1 : 0) : 0;

        // Update or create user's record in the JSON file
        $data = json_decode(file_get_contents('user_data.json'), true);
        $data[$email] = array(
            'total_deposits' => $total_deposits,
            'total_withdrawals' => $total_withdrawals,
            'credit_history' => $credit_history
        );
        file_put_contents('user_data.json', json_encode($data));

        return true;
    } else {
        return false;
    }
}

// Login
if (isset($_POST['submit1'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));

    $sql = "SELECT * FROM users1 WHERE email='{$email}' AND password='{$password}'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_email'] = $row['email'];

        // Update transaction history for the user
        $update_query = "UPDATE transaction_history SET user_email = '{$email}' WHERE user_email IS NULL";
        mysqli_query($conn, $update_query);

        // Update transaction data and credit history for the user
        if (updateTransactionData($email, $conn)) {
            header("Location: index.php");
            exit();
        } else {
            $msg = "<div class='alert alert-danger'>Error updating transaction data.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
    }
}
?>






<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" href="style5.css">
	<link rel="stylesheet" href="style2.css">
</head>
<body>
<header class="header sticky">
      <img src="Bankify (2).png" class="logo" alt="logo" />
      <nav class="main-nav">
        <ul class="main-nav-list">
          <li class="main-nav-link" onclick="window.location.href='index1.html'">
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
        </ul>
      </nav>
      <button class="btn-mob-nav">
        <ion-icon class="icon-mob" name="menu-outline"></ion-icon>
        <ion-icon class="icon-mob" name="close-outline"></ion-icon>
      </button>
    </header>
	<div class="container">
  <img src="2914527.jpg" alt="" class="img1" />
		<div id="LoginAndRegistrationForm">
			<h1 id="formTitle">Login</h1>
			<div id="formSwitchBtn">
				<button onclick="ShowLoginForm()"  id="ShowLoginBtn" class="active">Login Here</button>
				<!-- <button onclick="ShowRegistrationForm()"  id="ShowRegistrationBtn">Registration</button> -->
			</div>
			<div id="LoginFrom">
            <form action="" method="post">
					<div class="center">
						<input id="LoginEmail" class="input-text" name="email" type="text" placeholder="Email Address" autocomplete="off"> 
						<input id="LoginPassword" class="mt-10 input-text" name="password" type="password" placeholder="Password" autocomplete="off">
					</div>
					
					<div class="forgot-pass-remember-me mt-10">
						<div class="forgot-pass">
							<a id="ForgotPassword" href="JavaScript:void(0);" onclick="ShowForgotPasswordForm()" >Forgot Password?</a>
						</div>
						<div class="remember-me">
							<input id="rememberMe" type="checkbox">
							<label for="rememberMe">Remember Me</label>
						</div>
					</div>

					<div class="center mt-20">
						<input onclick="return ValidateLoginForm();"  class="Submit-Btn" type="submit" name="submit1" value="Login" id="LoginBtn">
					</div>
				</form>
				<p class="center mt-20 dont-have-account">
					Don't have an account? 
					<a href="register.php">Registration now</a>
				</p>
			</div>
			<div id="RegistrationFrom">
            <?php echo $msg; ?>
                <form action="" method="post">
					<div class="center">
						<input id="RegiName" class="input-text" type="text" placeholder="Full Name" name="name" value="<?php if (isset($_POST['submit'])) { echo $name; } ?>">
						<input id="RegiEmailAddres" class="input-text mt-10" type="email" placeholder="Email Address" name="email"value="<?php if (isset($_POST['submit'])) { echo $email; } ?>">
						<input id="RegiPassword" class="mt-10 input-text" type="password" placeholder="Password" name="password">
						<input id="RegiConfirmPassword" class="mt-10 input-text" type="password" placeholder="Confirm Password" name="confirm-password">
					</div>
					<div class="center mt-20">
						<input onclick="return ValidateRegistrationForm();" class="Submit-Btn" type="submit" value="Registration" name="submit" id="RegistrationitBtn">
					</div>
				</form>
				<p class="center mt-20 already-have-account">
					Already have an account? 
					<a href="#" onclick="ShowLoginForm()">Login now</a>
				</p>
			</div>
			<div id="ForgotPasswordForm">
				<form action="">
					<div class="center mt-20">
						<input class="input-text " type="email" id="forgotPassEmail" placeholder="Email Address">
					</div>
					<div class="center mt-20">
						<input onclick="return ValidateForgotPasswordForm();" class="Submit-Btn" type="submit" value="Reset Password" id="PasswordResetBtn" >
					</div>
				</form>
				<p class="center mt-20 already-have-account">
					Back to the 
					<a href="JavaScript:void(0);" onclick="ShowLoginForm()">Login page</a> | <a href="JavaScript:void(0);" onclick="ShowRegistrationForm()">Registration page</a>
				</p>
			</div>
		</div>
	</div>

	<script src="main.js" type="text/javascript"></script>
	<script src="validation.js" type="text/javascript"></script>
    <script type="text/javascript" src="script.js"></script>

<script src="jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
		<script type="text/javascript">
        function resetForm() {
            // Reset registration form fields
            document.getElementById("LoginEmail").value = "";
            document.getElementById("LoginPassword").value = "";
        }
    </script>
		
		 
</body>
</html>