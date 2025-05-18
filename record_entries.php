<?php

include 'connect.php';
header('Content-Type: application/json');

$response = ['status'=> 'success', 'error'=>[]];
$uploadDir = 'images/';
$isUpdate;
$numOf_deleted_images;
if (isset($_POST['update_id'])) {
        $update_id = htmlspecialchars(trim($_POST['update_id']));
        $isUpdate = true;
}
else{
       $isUpdate = false;
}

function CheckImage_recurence($image, $result_fetch_images){   
        if (in_array($image, $result_fetch_images)) {
                return false;
        }
        else{
        return true;}
        
}

function upload_image(){
        global $response;
        global $uploadDir;
        global $isUpdate;
        $image_name = array();
        $allowed_ext = array("jpg", "jpeg", "png", "gif");
        if ($isUpdate) {
                include 'connect.php';
                $update_id = $_POST['update_id'];
                $sql_fetch_images = mysqli_query($conn, "SELECT `images` FROM `images` WHERE `employee_id` = '$update_id';");
                $result_fetch_images = mysqli_fetch_all($sql_fetch_images);
                $result_fetch_images =  array_column($result_fetch_images, 0);
        }
        if(!empty($_FILES['image']['name'][0])) {
                foreach ($_FILES['image']['name'] as $key => $file_name) {
                        $temp_name = $_FILES['image']['tmp_name'][$key];
                        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                        $currentTime = strval(microtime());
                        $currentTime = str_replace([" ", "\t", "\n", "."], "", $currentTime);
                        $file_name = $currentTime . "." . $extension;
                        $check_image = true;
                        // if ($isUpdate) {
                        //         $check_image = CheckImage_recurence($file_name,$result_fetch_images);
                        // }
                        if (in_array($extension, $allowed_ext) && $check_image) {
                                $targetPath = $uploadDir . $file_name;
                                if (move_uploaded_file($temp_name, $targetPath)) {
                                        $image_name[] = $file_name;
                                }
                                else{
                                        $response['error']['image'] = "Failed to upload!!!";
                                }
                        }else {
                                $response['error']['image'] = "Only .jpg, .jpeg, .png, .gif types allowed!!!
                                Or the file already exist";
                        }
                }
        }
        else{
                if (!$isUpdate) {
                        $response['error']['image'] = "Please upload an image!!!";
                }
                elseif(isset($_POST['deleted_images'])) {
                        if (count($_POST['deleted_images']) === count($result_fetch_images)) {
                                $response['error']['image'] = "Please upload an image!!!";
                        }
                }
        }

        return $image_name;

}

function Delete_images(){
        include 'connect.php';
        global $uploadDir;
        global $response;
        $deleted_images = [];
        if (!empty($_POST['deleted_images']) && empty($response['error'])) {
                foreach ($_POST['deleted_images'] as $key) {
                        $deleted_images[] = intval($key);
                }
                $ids = implode(',', $deleted_images);

                //Getting all the images names according to the delete images ids.
                $sql_fetch_images = mysqli_query($conn, "SELECT `images` FROM `images` WHERE `id` IN ($ids)");
                $result_fetch_images = mysqli_fetch_all($sql_fetch_images);

                $sql_total_images = mysqli_query($conn, "SELECT `images` FROM `images`;");
                $result_total_images = mysqli_fetch_all($sql_total_images);

                $total_images = array_column($result_total_images, 0);

                $counts = array_count_values($total_images);
                
                foreach ($result_fetch_images as $key) {
                        $count = isset($counts[$key[0]]) ? $counts[$key[0]] : 0;
                        if ($count < 2) {
                                if (file_exists($uploadDir.$key[0])) {
                                        unlink($uploadDir.$key[0]);
                                }
                        }
                }
                
                $sql_delete_images = mysqli_query($conn, "DELETE FROM `images` WHERE `id` IN ($ids)");
        }
        else{return;}
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $mobile = htmlspecialchars(trim($_POST['mobile']));
        $dob = htmlspecialchars(trim($_POST['dob']));
        $country_id = htmlspecialchars(trim($_POST['country_id']));
        $state_id = htmlspecialchars(trim($_POST['state_id']));
        $city_id = htmlspecialchars(trim($_POST['city_id']));
        $address = htmlspecialchars(trim($_POST['address']));
        $file_name = upload_image();
        Delete_images();
                
                
                foreach (["first_name", "last_name", "mobile", "dob", "country_id", "state_id", "city_id", "address"] as $key) {
                        if (empty($_POST[$key])) {
                                if ($key === 'state_id') {
                                        # code...
                                        if ($_POST['state_visibility'] === 'true') {
                                                $response['error'][$key] = "You missed this one!!!";
                                        }
                                }
                        elseif ($key === 'city_id') {
                                # code...
                                if ($_POST['city_visibility'] === 'true') {
                                        $response['error'][$key] = "You missed this one!!!";
                                }
                        }
                        else {
                                $response['error'][$key] = "You missed this one!!!";
                        }
                }
                elseif ($key === 'first_name' && !preg_match("/^[A-Za-z ]{1,20}$/", $_POST['first_name'])) {
                        $response['error'][$key] = "Enter valid characters of max 20 length!!!";
                }
                elseif ($key === 'last_name' && !preg_match("/^[A-Za-z ]{1,20}$/", $_POST['last_name'])) {
                        $response['error'][$key] = "Enter valid characters of max 20 length!!!";
                }
                elseif ($key === 'mobile' && !preg_match("/^[0-9]{10}$/", $_POST['mobile'])) {
                        $response['error'][$key] = "Enter only numerics upto 10 digits!!!";
                }
                elseif ($key === 'address' && !preg_match("/^[a-zA-Z0-9]{1,50}$/", $_POST['address'])) {
                        $response['error'][$key] = "Enter valid characters of max 50 length!!!";
                }
                
        }
        
        if (!isset($_POST['update_id'])) {
                $sql_check_unique = mysqli_query($conn, "SELECT `mobile` FROM `employees` WHERE `mobile` = '$mobile'");
                $result_check = mysqli_fetch_all($sql_check_unique);
                if (!empty($result_check)) {
                        $response['error']['mobile'] = "This number already exist please enter a different one!!!";
                }
        }
        
       

        if (empty($response['error'])) {

                if (!isset($_POST['update_id'])) {
                        $sql_employee = mysqli_query($conn, "INSERT INTO `employees`(`employee_id`, `first_name`, `last_name`, `mobile`, `dob`, `country`, `state`, `city`, `address`) 
                        VALUES ('','$first_name','$last_name','$mobile','$dob','$country_id','$state_id','$city_id','$address')");

                        $sql_get_empId = mysqli_query($conn, "SELECT `employee_id` FROM employees ORDER BY employee_id DESC LIMIT 1;");
                        $result_empId = mysqli_fetch_all($sql_get_empId);
                        $employee_id = $result_empId[0][0];
                        foreach ($file_name as $key) {
                                $sql_upload_image = mysqli_query($conn, "INSERT INTO `images`(`employee_id`, `images`) VALUES ('$employee_id','$key')");
                        }

                        if ($sql_employee) {
                                $response['status'] = 'success';
                                // $response['message'] = "Values inserted successfully";
                        }
                        else{
                                $response['status'] = 'error';
                                $response['message'] = 'database error!!!';
                        }
                }
                else {
                        // $image_name = upload_image();
                        // $update_id = htmlspecialchars(trim($_POST['update_id']));
                        $sql_update = mysqli_query($conn, "UPDATE `employees` SET `first_name`='$first_name',`last_name`='$last_name',`mobile`='$mobile',
                        `dob`='$dob',`country`='$country_id',`state`='$state_id',`city`='$city_id',`address`='$address' WHERE `employee_id` = '$update_id'");
                        
                        if (!empty($file_name)) {
                                foreach ($file_name as $key) {
                                        $sql_upload_image = mysqli_query($conn, "INSERT INTO `images`(`employee_id`, `images`) VALUES ('$update_id','$key')");
                                }                        
                        }

                        // for ($i=0; $i < mysqli_num_rows($sql_image_rows); $i++) { 
                        //         $sql_upload_image = mysqli_query($conn, "UPDATE images 
                        //         SET images='$file_name[$i]' 
                        //         WHERE employee_id = '$update_id' 
                        //         AND images = (
                        //         SELECT images FROM (
                        //                 SELECT images 
                        //                 FROM images 
                        //                 WHERE employee_id = '$update_id' 
                        //                 ORDER BY images ASC 
                        //                 LIMIT 1 OFFSET $i
                        //         ) AS temp
                        //         );");
                        // }

                        if ($sql_update) {
                                $response['status'] = 'success';
                                // $response['message'] = "Values inserted successfully";
                        }
                        else{
                                $response['status'] = 'error';
                                $response['message'] = 'database error!!!';
                        }
                }

        
                // echo "Values inserted successfully";
        }
        else{
                $response['status'] = 'error';
        }

        
        
}
echo json_encode($response);



?>