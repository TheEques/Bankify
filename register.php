<?php
include 'config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
    $code = mysqli_real_escape_string($conn, md5(rand()));

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users1 WHERE email='{$email}'")) > 0) {
        $msg = "<div class='alert alert-danger'>{$email} - This email address has already been registered.</div>";
    } else {
        if ($password === $confirm_password) {
            $sql = "INSERT INTO users1 (name, email, password, code) VALUES ('{$name}', '{$email}', '{$password}', '{$code}')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                // Redirect to acc_opening.php upon successful registration
                header("Location: templates/acc_opening.php");
                exit(); // Make sure to exit after redirecting
            } else {
                $msg = "<div class='alert alert-danger'>Error: Registration failed. Please try again.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration</title>
    <link rel="stylesheet" href="style5.css">
    <link rel="stylesheet" href="style2.css">
    <script src="main.js" type="text/javascript"></script>
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
        <img src="2914527.jpg" alt="" class="img2" />
        <div id="LoginAndRegistrationForm1">
            <h1 id="formTitle">Registration</h1>
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
						<input onclick="return ValidateRegistrationForm();" onclick="window.location.href='saving.php'" class="Submit-Btn" type="submit" value="Registration" name="submit" id="RegistrationitBtn">
					</div>
				</form>
                <p class="center mt-20 already-have-account">
                    Already have an account? 
                    <a href="page.php">Login now</a>
                </p>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Call the ShowRegistrationForm() function when the page loads
        window.onload = function() {
            ShowRegistrationForm();
        };
    </script>
    <script type="text/javascript">
        function resetForm() {
            // Reset registration form fields
            document.getElementById("RegiName").value = "";
            document.getElementById("RegiEmailAddres").value = "";
            document.getElementById("RegiPassword").value = "";
            document.getElementById("RegiConfirmPassword").value = "";
        }
    </script>
</body>
</html>

