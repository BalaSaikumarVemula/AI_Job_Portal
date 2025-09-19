<?php
$servername = "localhost";
$username = "root";
$password = "1609";
$dbname = "ai_job_portal";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>
