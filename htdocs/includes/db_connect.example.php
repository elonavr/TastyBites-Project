<?php
// db_connect.example.php

$servername = "sql207.byetcluster.com"; 
$username = "YOUR_USERNAME_HERE"; 
$password = "YOUR_PASSWORD_HERE"; 
$dbname = "YOUR_DATABASE_NAME_HERE"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>