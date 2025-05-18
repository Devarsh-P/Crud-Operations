<?php

include 'connect.php';


$first_row = 0;
$rows_per_page = 5;

$sql_emp_rows = mysqli_query($conn, "SELECT * FROM `employees`;");
$num_of_row = mysqli_num_rows($sql_emp_rows);
$pages = ceil($num_of_row / $rows_per_page);

if (isset($_GET['page-num'])) {
    $current_page = $_GET['page-num'] - 1;
    $first_row = $current_page * $rows_per_page;
}


?>