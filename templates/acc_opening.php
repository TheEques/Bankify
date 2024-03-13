<?php
include 'config.php';
// Function to generate random account number
function generateAccountNumber() {
  // Generate random 8-digit number
  $random_number = mt_rand(10000000, 99999999);
  // Concatenate with 'BB'
  $account_number = 'BB' . $random_number;
  return $account_number;
}
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign form data to variables
    $account_type = isset($_POST['type']) ? $_POST['type'] : '';
    $full_name = isset($_POST['ful_name']) ? $_POST['ful_name'] : '';
    $father_name = isset($_POST['f_name']) ? $_POST['f_name'] : '';
    $mother_name = isset($_POST['m_name']) ? $_POST['m_name'] : '';
    $gender = isset($_POST['gen']) ? $_POST['gen'] : '';
    $date_of_birth = isset($_POST['dob']) ? $_POST['dob'] : '';
    $phone_number = isset($_POST['mob']) ? $_POST['mob'] : '';
    $email = isset($_POST['em']) ? $_POST['em'] : '';
    $aadhar_number = isset($_POST['adhar']) ? $_POST['adhar'] : '';
    $pan_number = isset($_POST['pan']) ? $_POST['pan'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $state = isset($_POST['state']) ? $_POST['state'] : '';
    $district = isset($_POST['district']) ? $_POST['district'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $marital_status = isset($_POST['status']) ? $_POST['status'] : '';
    $education_details = isset($_POST['edu']) ? $_POST['edu'] : '';
    $occupation = isset($_POST['job']) ? $_POST['job'] : '';
    $annual_income = isset($_POST['income']) ? $_POST['income'] : '';
    $nominee_name = isset($_POST['nom']) ? $_POST['nom'] : '';
    $nominee_relation = isset($_POST['relation']) ? $_POST['relation'] : '';
    $nominee_dob = isset($_POST['n_dob']) ? $_POST['n_dob'] : '';
    $nominee_gender = isset($_POST['n_gender']) ? $_POST['n_gender'] : '';
    $nominee_phone_number = isset($_POST['n_no']) ? $_POST['n_no'] : '';
    $B_PIN = isset($_POST['b_pin']) ? $_POST['b_pin'] : '';

    // Generate account number
    $account_number = generateAccountNumber();

    // Set default values for IFSC_code and Branch
    $IFSC_code = "BF2004";
    $Branch = "Mumbai";

    // Perform insertion into the database
    $sql = "INSERT INTO account_opening_forms (account_type, full_name, father_name, mother_name, gender, date_of_birth, phone_number, email, aadhar_number, pan_number, country, state, district, address, marital_status, education_details, occupation, annual_income, nominee_name, nominee_relation, nominee_dob, nominee_gender, nominee_phone_number)
            VALUES ('$account_type', '$full_name', '$father_name', '$mother_name', '$gender', '$date_of_birth', '$phone_number', '$email', '$aadhar_number', '$pan_number', '$country', '$state', '$district', '$address', '$marital_status', '$education_details', '$occupation', '$annual_income', '$nominee_name', '$nominee_relation', '$nominee_dob', '$nominee_gender', '$nominee_phone_number')";

    if ($conn->query($sql) === TRUE) {
        // Insert data into internet_banking table
        $sql_internet_banking = "INSERT INTO internet_banking (account_no, name, dob, IFSC_code, Branch, email,B_PIN) VALUES ('$account_number', '$full_name', '$date_of_birth', '$IFSC_code', '$Branch', '$email', '$B_PIN')";
        if ($conn->query($sql_internet_banking) === TRUE) {
            // Alert message
            ?>
            <script>
                alert("Form submitted successfully! Your account number is <?php echo $account_number; ?>");
            </script>
            <?php
        } else {
            echo "Error inserting data into internet_banking table: " . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // If the form has not been submitted, display a message or redirect as desired
    echo "Please fill out the form to submit.";
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account opening Form</title>
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 0.9rem;
      }

      .acc {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 10px 10px;
        border: 1px solid black;
        margin: 10px 10px;
      }

      .center {
        text-align: center;
      }

      .frm {
        padding: 10px 10px;
        margin: 10px 10px;
        /* border: 1px black solid; */
      }

      .con {
        margin: 10px 10px;
        padding: 0px 10px;
      }

      .add {
        padding: 5px 5px;
        margin: 5px 5px;
      }

      input[type="text"],
      input[type="number"],
      input[type="email"],
      input[type="date"],
      select,
      input[type="radio"] {
        padding: 6px 13px;
        margin: 10px 10px;
        display: inline block;
        background: #f1f1f1;
      }
      input[type="text"]:focus,
      input[type="number"]:focus,
      input[type="email"]:focus,
      input[type="date"]:focus {
        background-color: rgb(154, 225, 219);
      }
      .details {
        border: 2px red solid;
        background: #e1edff;
        padding: 1rem;
        margin-bottom: 2rem;
      }
      .sub {
        text-align: center;
      }
      .btn {
        cursor: pointer;
      }
      .submit-btn {
        padding: 0.5rem 1rem;
        background: #1057c0;
        color: #fff;
        border-radius: 8px;
        font-weight: 700;
        margin-top: 1rem;
      }
    </style>
  </head>

  <body>
    <h1 class="center">Account Opening Form</h1>
    <div class="acc">
      <form action="" method="post">
        <div class="frm">
          <div class="con">
            <label for="acc_type"><b>Select Account Type : </b></label>
            <label for="sav">i) Savings</label
            ><input type="radio" name="type" id="sav" value="Saving" />
            <label for="curr">ii) Current</label>
            <input type="radio" name="type" id="curr" value="Current" />
          </div>
          <div class="details">
            <h3>1) Personal Details</h3>
            <div class="con">
              <label for="nme"><b>Enter your Full name </b></label>
              <input
                type="text"
                name="ful_name"
                id="nme"
                placeholder="Full Name"
                required
              />
            </div>
            <div class="con">
              <label for="p_name"><b>Father Name: </b></label>
              <input
                type="text"
                name="f_name"
                id="p_name"
                placeholder="Father Name"
                required
              />
              <label for="p1_name"><b>Mother Name : </b></label>
              <input
                type="text"
                name="m_name"
                id="p1_name"
                placeholder="Mother Name"
                required
              />
            </div>
            <div class="con">
              <b> Gender</b>: <label for="male">i)Male</label
              ><input type="radio" name="gen" id="male" value="Male" />
              <label for="female">ii)Female</label
              ><input type="radio" name="gen" id="female" value="Female"/>
              <label for="other">iii)Other</label
              ><input type="radio" name="gen" id="other" value="Other" />
            </div>
            <div class="con">
              <label for="dob"><b>Date of Birth: </b></label>
              <input type="date" name="dob" id="dob" required />
            </div>
            <div class="con">
              <label for="mob"><b>Phone Number :</b></label>
              <input
                type="number"
                name="mob"
                id="mob"
                placeholder="Phone Number"
                required
              />
            </div>
            <div class="con">
              <label for="email"><b>Email Id: </b></label>
              <input
                type="email"
                name="em"
                id="email"
                required
                placeholder="Email-id"
              />
            </div>
            <div class="con">
              <label for="uid"><b>Aadhar number: </b></label>
              <input
                type="text"
                name="adhar"
                id="uid"
                placeholder="Aadhar Card Number"
                required
              />
            </div>
            <div class="con">
              <label for="pan"><b>Pan number: </b></label>
              <input
                type="text"
                name="pan"
                id="pan"
                placeholder="Pan Card Number"
                required
              />
            </div>
            <div class="con">
              <label for="country"><b>Country: </b></label>
              <select name="country" id="country" onchange="loadStates()">
                <option selected>Select country</option>
              </select>
              <label for="state"><b>State: </b></label>
              <select name="state" id="state" onchange="loadCities()">
                <option selected>Select State</option>
              </select>
              <label for="city"><b>District: </b></label>
              <select name="district" id="city">
                <option selected>Select City</option>
              </select>
            </div>
          </div>
          <div class="details">
            <h3>2) Address Details and Other Details</h3>
            <div class="con">
              <b>Address : </b>
              <div class="add">
                <label for="apart"><b>i)Apartment/House No: </b></label
                ><input type="text" name="apart" id="apart" required />
                <label for="build"><b>ii)Building Name: </b></label
                ><input type="text" name="build" id="build" />
                <label for="street"><b>iii)Street/Road: </b></label
                ><input type="text" name="street" id="street" />
                <label for="taluka"><b>iv)Taluka: </b></label
                ><input type="text" name="taluka" id="taluka" />
                <label for="dis"><b>v)District:</b></label
                ><input type="text" name="dis" id="dis" />
                <label for="stt"><b>vi)State:</b></label
                ><input type="text" name="stt" id="stt" />
                <label for="p_code"><b>vii)Pin Code:</b></label
                ><input type="number" name="p_code" id="p_code" required />
              </div>
            </div>
            <div class="con">
              <label for="mar"><b>Marital Status: </b></label>
              <label for="married"
                >i)Married:<input type="radio" name="status" id="married" value="Married"
              /></label>
              <label for="unmarried"
                >ii)Unmarried:<input type="radio" name="status" id="unmarried"
              value="Unmarried"/></label>
              <label for="divorced"
                >iii)Divorced:<input type="radio" name="status" id="divorced"
              value="Divorced"/></label>
            </div>
            <div class="con">
              <label for="edu"><b>Education Details :</b></label>
              <select name="edu" id="edu">
                <option value="primary">Below 10th</option>
                <option value="secondary">10th</option>
                <option value="h_secondary">12th</option>
                <option value="ug">Graduate</option>
                <option value="pg">Post-Graduate</option>
                <option value="phd">P.H.D</option>
                <option value="illiterate">Illiterate</option>
              </select>
            </div>
            <div class="con">
              <label for="occ"><b>Occupation</b></label>
              <label for="pvt"
                >i)Private Job:<input type="radio" name="job" id="pvt"
              value="Private"/></label>
              <label for="gvt"
                >ii)Government Job:<input type="radio" name="job" id="gvt"
              value="Government"/></label>
              <label for="bman"
                >iii)Business:<input type="radio" name="job" id="bman"
              value="Business"/></label>
              <label for="self"
                >iv)Self-Employed:<input type="radio" name="job" id="self"
              value="Self-Employed"/></label>
              <label for="stud"
                >v)Student:<input type="radio" name="job" id="stud"
              value="Student"/></label>
              <label for="ret"
              value="Retired">vi)Retired:<input type="radio" name="job" id="ret"
              /></label>
              <label for="other"
                >vii)Other:<input type="radio" name="job" id="other"
              value="other"/></label>
            </div>
            <div class="con">
              <label for="income"><b>Annual Income: </b></label>
              <input
                type="number"
                name="income"
                id="income"
                required
                placeholder="Income in Rupees"
              />
            </div>
          </div>
          <div class="details">
            <h3>
              3) Nominee Details(Nominee is compulsory for Account Opening)
            </h3>
            <div class="con">
              <label for="nom"><b>i)Nominee Name: </b></label>
              <input
                type="text"
                name="nom"
                id="nom"
                required
                placeholder="Enter Full name"
              />
              <label for="relation"
                ><b>ii)Relation of Nominee with you: </b></label
              >
              <input
                type="text"
                name="relation"
                id="relation"
                required
                placeholder="Relation"
              />
              <label for="n_dob"><b>iii)Date of Birth:</b></label>
              <input type="date" name="n_dob" id="n_dob" />
              <label for="nom_gender"><b>iv)Gender:</b></label>
              <label for="n_male">(i)Male</label>
              <input type="radio" name="n_gender" id="n_male" value="n_male" />
              <label for="n_female">(ii)Female</label>
              <input type="radio" name="n_gender" id="n_female" value="n_female" />
              <label for="n_other">(iii)Other</label>
              <input type="radio" name="n_gender" id="n_other" value="n_other"/>
              <label for="n_no"><b>v)Phone Number</b></label>
              <input type="number" name="n_no" id="n_no" />
            </div>
          </div>
          <div class="details">

            <h3>4) Set Banking PIN</h3>
            <div class="con">
              <label for="b_pin"><b>Set Banking PIN (B_PIN):</b></label>
              <input type="text" name="b_pin" id="b_pin" required />
            </div>
            <div class="con">
              <label for="confirm_b_pin"><b>Confirm Banking PIN (B_PIN):</b></label>
              <input type="text" name="confirm_b_pin" id="confirm_b_pin" required />
            </div>
          </div>
            <div class="declare">
            <h3>5) Declaration</h3>
            <span class="dec"
              ><input type="checkbox" name="dec1" id="dec1" />“I, hereby declare
              that all the information submitted by me in the application form
              is correct, true and valid. I will present the supporting
              documents as and when required. If the above information is false,
              I will be legaly punishable.”</span
            >
            <br>
            <span style="color:red; font-weight:600">Remember the B_PIN for further transactions!!</span>
          </div>
          <div class="sub">
            <button type="submit" class="btn submit-btn">Submit</button>
          </div>
        </div>
      </form>
    </div>
    <script src="state_drop.js"></script>
    
  </body>
</html>
