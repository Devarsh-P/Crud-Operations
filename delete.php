<?php

include 'connect.php';

$uploadDir = 'images/';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['deleteid'];

    $sql_images_fetch = mysqli_query($conn, "SELECT `images` FROM `images` WHERE `employee_id` = '$id'");
    $result_images_fetch = mysqli_fetch_all($sql_images_fetch);

    $sql_total_images = mysqli_query($conn, "SELECT `images` FROM `images`;");
    $result_total_images = mysqli_fetch_all($sql_total_images);

    $total_images = array_column($result_total_images, 0);

    $counts = array_count_values($total_images);

    foreach ($result_images_fetch as $key) {
        $count = isset($counts[$key[0]]) ? $counts[$key[0]] : 0;
        if ($count < 2) {
            if (file_exists($uploadDir.$key[0])) {
                unlink($uploadDir.$key[0]);
            }
        }
    }


    $sql_delete_image = mysqli_query($conn, "DELETE FROM `images` WHERE `employee_id` = '$id'");

    $sql_delete = mysqli_query($conn, "DELETE FROM `employees` WHERE `employee_id` = '$id'");
    if($sql_delete){
        header('location:home.php');
    }
}

?>