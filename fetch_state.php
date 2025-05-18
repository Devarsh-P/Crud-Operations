<?php
include 'connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country_id = mysqli_real_escape_string($conn,$_POST['country_id']);
    $update_state_id = mysqli_real_escape_string($conn,$_POST['update_state_id']);
    // if (isset($_POST['update_state'])) {
    // }

    // $relate_country = mysqli_query($conn, "SELECT `id` FROM `countries` WHERE `name` = '$country'");
    // $relate_result = mysqli_fetch_assoc($relate_country);

    // $country_id = $relate_result['id'];

    $sql_state_fetch = mysqli_query($conn, "SELECT `id`,`name` FROM `states` WHERE `country_id` = '$country_id'");
    $result_state = mysqli_fetch_all($sql_state_fetch);
    echo '<option value="">Select your state</option>';
    foreach ($result_state as $state_array) {
        if ($update_state_id === $state_array[0]) {
            echo '<option value="'.$state_array[0].'" selected>'.$state_array[1].'</option>';
            continue;
        }
        echo '<option value="'.$state_array[0].'">'.$state_array[1].'</option>';
    }

    
}

?>

