<?php

include 'connect.php';
$error = [];

$imagePath = 'images/';

$updateid = $_GET['updateid'];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql_update_fetch = mysqli_query($conn, "SELECT * FROM `employees` WHERE `employee_id` = '$updateid'");
    if ($sql_update_fetch) {
        $result = mysqli_fetch_assoc($sql_update_fetch);
        $first_name = $result['first_name'];
        $last_name = $result['last_name'];
        $mobile = $result['mobile'];
        $dob = $result['dob'];
        $country_id = $result['country'];
        $state_id = $result['state'];
        $city_id = $result['city'];
        $address = $result['address'];
    }
}

?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Php Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background-color: black;
            color: white;
        }
        #warning {
            color: red;
        }
        .error-message{
            color: red;
        }
        .images{
            width: 20px;
            height: 20px;
        }
        .image-container {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        margin: 10px;
        width: 150px;
        }

        .display-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }

        .display-image:hover {
            transform: scale(1.05);
        }

        .delete-form {
            margin-top: 5px;
        }

        .delete-btn {
            padding: 2px 6px;
            font-size: 12px;
            border-radius: 4px;
            background-color: #ff5c5c;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .delete-btn:hover {
            background-color: #e04b4b;
        }


    </style>
</head>

<body>
    <div class="container" style="margin-top: 10vh;">
        <form id="form" method="POST">
            <!-- Row for First Name, Last Name, and Mobile -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" class="form-control" value="<?php if (isset($first_name)) {echo $first_name;} ?>" 
                    placeholder="Enter your first name" name="first_name" autocomplete="off" pattern="[a-z]{20}" required>
                    <div id="first_name_error" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" class="form-control" value="<?php if (isset($last_name)) {echo $last_name;} ?>" 
                    placeholder="Enter your last name" name="last_name" autocomplete="off">
                    <div id="last_name_error" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="mobile">Mobile</label>
                    <input type="tel" id="mobile" class="form-control" value="<?php if (isset($mobile)) {echo $mobile;} ?>"
                    placeholder="Enter your mobile number" pattern="^\d{10}$" name="mobile" autocomplete="off">
                    <div id="mobile_error" class="error-message"></div>
                </div>
            </div>

            <!-- Row for DOB and Country -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" class="form-control" name="dob" value="<?php if (isset($dob)) {echo $dob;} ?>">
                    <div id="dob_error" class="error-message"></div>
                </div>
                <div class="col-md-6">
                    <label for="country">Country</label>
                    <select id="country" class="form-control" name="country" onchange="SelectState()">
                        <?php
                        $sql_country_fetch = "SELECT `id`,`name` FROM `countries`";
                        $result_country = mysqli_query($conn, $sql_country_fetch);
                        $country_array = mysqli_fetch_all($result_country);
                        echo '<option value="">Select your country</option>';
                        foreach ($country_array as $name_country) {
                            if ($name_country[0] === $country_id) {
                                echo '<option value="' . $name_country[0] . '" selected>' . $name_country[1] .'</option>';
                            }
                            echo '<option value="' . $name_country[0] . '">' . $name_country[1] .'</option>';
                        }
                        ?>
                    </select>
                    <div id="country_id_error" class="error-message"></div>
                </div>
            </div>

            <!-- Dynamic State, City, and Address Fields -->
            <div class="row mb-3" id="state-row" style="visibility: hidden;">
                <div class="col-md-6">
                    <label for="state">State</label>
                    <select id="state" class="form-control" name="state" onchange="SelectCity()">
                        <!-- php code -->
                    </select>
                    <div id="state_id_error" class="error-message"></div>
                </div>
                <div class="col-md-6" id="city-row" style="visibility: hidden;">
                    <label for="city">City</label>
                    <select id="city" class="form-control" name="city">
                        <!-- php code -->
                    </select>
                    <div id="city_id_error" class="error-message"></div>
                </div>
            </div>

            <div class="mb-3" id="address-row" >
                <label for="address">Address</label>
                <input type="text" id="address" class="form-control" value="<?php if (isset($address)) {echo $address;} ?>" 
                placeholder="Enter your address" name="address" autocomplete="off">
                <div id="address_error" class="error-message"></div>
            </div>

            <div class="mb-3">
                <?php 
                    $sql_image_fetch = mysqli_query($conn, "SELECT `id`,`images` FROM images WHERE employee_id = '$updateid'");
                    $result_images = mysqli_fetch_all($sql_image_fetch);
                    foreach ($result_images as $key) {
                        echo '
                        <div class="image-container">
                            <div id="'.$key[0].'">
                            <img src="'.$imagePath.$key[1].'" alt="" class="display-image">
                              <button type="button" class="btn btn-danger btn-sm delete-btn" onclick="Delete_image('.$key[0].')">Delete</button>
                              </div>
                        </div>';
                    }

                ?>
                
            </div>

            <div class="mb-3" id="image-row">
                <h3>Add a new image.</h3>
                <input type="file" name="image[]" id="image" multiple>
                <div id="image_error" class="error-message"></div>
            </div>

            <button type="button" id="update" name="update" class="btn btn-primary">Update</button>
            <!-- <button type="button" id="home" name="home" class="btn btn-primary">Home</button> -->
            <a href="home.php?page-num=1" class="btn btn-primary text-light" role="button">Home</a>

        </form>
    </div>

   <script src="index.js"></script>
   <script>
    const deleted_images = [];
    window.onload = function(){
        var SelectedCountry_id = document.getElementById('country').value;
        if (SelectedCountry_id) {
            SelectCity("<?php echo $city_id; ?>", SelectState("<?php echo $state_id; ?>"))
        }
    }
    const updateButton = document.getElementById('update');
    if (updateButton) {
        updateButton.addEventListener('click', () => {
            Submit_Update("<?php echo $updateid ?>",deleted_images);
        });
    }
    function Delete_image(image_id){
        document.getElementById(image_id).remove();
        deleted_images.push(image_id);
    }
   </script>

</body>


</html>


