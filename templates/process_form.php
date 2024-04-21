<?php
include 'config.php';


$account_type = $_POST['type']; 


$sql = "INSERT INTO account_opening_forms (account_type, full_name, father_name, mother_name, gender, date_of_birth, phone_number, email, aadhar_number, pan_number, country, state, district, address, marital_status, education_details, occupation, annual_income, nominee_name, nominee_relation, nominee_dob, nominee_gender, nominee_phone_number)
        VALUES ('$account_type', '$full_name', '$father_name', '$mother_name', '$gender', '$date_of_birth', '$phone_number', '$email', '$aadhar_number', '$pan_number', '$country', '$state', '$district', '$address', '$marital_status', '$education_details', '$occupation', '$annual_income', '$nominee_name', '$nominee_relation', '$nominee_dob', '$nominee_gender', '$nominee_phone_number')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
