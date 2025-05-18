<?php

include 'connect.php';

$error = [];

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Php Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #warning {
            color: red;
        }
        .error-message{
            color: red;
        }
    </style>
</head>

<body>
    <div class="container" style="margin-top: 10vh;">
        <form id="form" method="POST" enctype="multipart/form-data">
            <!-- Row for First Name, Last Name, and Mobile -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" class="form-control"  
                    placeholder="Enter your first name" name="first_name" autocomplete="off" pattern="[a-z]{20}" required>
                    <div id="first_name_error" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" class="form-control"  
                    placeholder="Enter your last name" name="last_name" autocomplete="off" required>
                    <div id="last_name_error" class="error-message"></div>
                </div>
                <div class="col-md-4">
                    <label for="mobile">Mobile</label>
                    <input type="tel" id="mobile" class="form-control" 
                    placeholder="Enter your mobile number" pattern="^\d{10}$" name="mobile" autocomplete="off" required>
                    <div id="mobile_error" class="error-message"></div>
                </div>
            </div>

            <!-- Row for DOB and Country -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" class="form-control" name="dob" required>
                    <div id="dob_error" class="error-message"></div>
                </div>
                <div class="col-md-6">
                    <label for="country">Country</label>
                    <select id="country" class="form-control" name="country" onchange="SelectState()" required>
                        <?php
                        $sql_country_fetch = "SELECT `id`,`name` FROM `countries`";
                        $result_country = mysqli_query($conn, $sql_country_fetch);
                        $country_array = mysqli_fetch_all($result_country);
                        echo '<option value="">Select your country</option>';
                        foreach ($country_array as $name_country) {
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
                    <select id="state" class="form-control" name="state" onchange="SelectCity()" required>
                        <!-- php code -->
                    </select>
                    <div id="state_id_error" class="error-message"></div>
                </div>
                <div class="col-md-6" id="city-row" style="visibility: hidden;">
                    <label for="city">City</label>
                    <select id="city" class="form-control" name="city" required>
                        <!-- php code -->
                    </select>
                    <div id="city_id_error" class="error-message"></div>
                </div>
            </div>

            <div class="mb-3" id="address-row" >
                <label for="address">Address</label>
                <input type="text" id="address" class="form-control" placeholder="Enter your address" name="address" autocomplete="off" required>
                <div id="address_error" class="error-message"></div>
            </div>

            <div class="mb-3" id="image-row">
                <input type="file" name="image[]" id="image" multiple>
                <div id="image_error" class="error-message"></div>
            </div>

            <button type="button" id="submit" name="submit" class="btn btn-primary">Submit</button>
            <!-- <button type="button" id="home" name="home" class="btn btn-primary">Home</button> -->
            <a href="home.php?page-num=1" class="btn btn-primary text-light" role="button">Home</a>

        </form>
    </div>

   <script src="index.js"></script>
   <script>
    const submitButton = document.getElementById('submit');
    if (submitButton) {
        submitButton.addEventListener('click', () => {
            Submit_Update();
        });
    }
   </script>

</body>


</html>


<?php



?>