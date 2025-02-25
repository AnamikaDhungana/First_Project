<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nepaliswadh_db";

//create a connection
$conn = mysqli_connect ($servername, $username, $password, $dbname);

//to check connection
if (!$conn) {

    die ("Connection failed: " . mysqli_connect_error());

}

//  echo "Connection successfully";
//  mysqli_close($conn);
