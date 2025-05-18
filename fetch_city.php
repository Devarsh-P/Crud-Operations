<?php

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $state_id = mysqli_real_escape_string($conn, $_POST['state_id']);
    $update_city_id = mysqli_real_escape_string($conn, $_POST['update_city_id']);

    // $relate_state = mysqli_query($conn, "SELECT `id` FROM `states` WHERE `name` = '$state'");
    // $relate_result_state = mysqli_fetch_assoc($relate_state);

    // $state_id = $relate_result_state['id'];

    $sql_city_fetch = mysqli_query($conn, "SELECT `id`,`name` FROM `cities` WHERE `state_id` = '$state_id'");
    echo '<option value="">Select your city</option>';
    while($city_array = mysqli_fetch_assoc($sql_city_fetch)){
        if ($update_city_id === $city_array['id']) {
            echo '<option value="'.$city_array['id'].'" selected>'.$city_array['name'].'</option>';
            continue;
        }
        echo '<option value="'.$city_array['id'].'">'.$city_array['name'].'</option>';
    }
    

}

?>