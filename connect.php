<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "phptask";

$conn = new mysqli($servername, $username, $password, $database);

if(!$conn)
{
    die(mysqli_errno($conn));
}

?>