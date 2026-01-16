<?php
$servername = "sql207.byetcluster.com"; 
$username = "b3_40794676";             
$password = "ElonsByethost";
$dbname = "b3_40794676_tastybites_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8"); // for hebrew
echo "Succesfully Connected";
?>