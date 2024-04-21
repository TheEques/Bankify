<?php
$conn = mysqli_connect("localhost", "root", "", "bankify");

if (!$conn) {
    echo "Connection Failed";
}
