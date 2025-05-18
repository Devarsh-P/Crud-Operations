<?php


include 'connect.php';


if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $filename = $_FILES["file"]["tmp_name"];


    // $sql_country = mysqli_query($conn, "SELECT `id`,`name` FROM countries;");
    // $sql_state = mysqli_query($conn, "SELECT `id`,`name` FROM states;");
    // $sql_city = mysqli_query($conn, "SELECT `id`,`name` FROM city;");

    // $result_country = mysqli_fetch_all($sql_country, MYSQLI_NUM);
    // $result_state = mysqli_fetch_all($sql_state, MYSQLI_NUM);
    // $result_city = mysqli_fetch_all($sql_city, MYSQLI_NUM);

    // for (; ($data = fgetcsv($file)) !== false;) {


    //     $index = array_search($data[5], $result_country);
    // }

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");

        fgetcsv($file);

        $values = []; // Store all rows as arrays

        $sql_employee_mobile = mysqli_query($conn, "SELECT `mobile` FROM employees;");
        $result_mobile = mysqli_fetch_all($sql_employee_mobile);
        $result_mobile = array_column($result_mobile, 0);
        $count = 0; 
        
        { //Country, State, City Pre-loaded Lookup data
            $queryCountries = "SELECT id,`name` FROM countries";
            $resultCountries = mysqli_query($conn, $queryCountries);
            $rowsCountries = mysqli_fetch_all($resultCountries);

            $countries = [];
            foreach ($rowsCountries as $row) {
                // $row[0] is the id, $row[1] is the country name
                $countries[strtolower(trim($row[1]))] = $row[0];
            }

            // Build the states lookup
            $queryStates = "SELECT id,`name` FROM states";
            $resultStates = mysqli_query($conn, $queryStates);
            $rowsStates = mysqli_fetch_all($resultStates);

            $states = [];
            foreach ($rowsStates as $row) {
                // $row[0] is the id, $row[1] is the state name
                $states[strtolower(trim($row[1]))] = $row[0];
            }

            // Build the cities lookup
            $queryCities = "SELECT id,`name` FROM cities";
            $resultCities = mysqli_query($conn, $queryCities);
            $rowsCities = mysqli_fetch_all($resultCities);

            $cities = [];
            foreach ($rowsCities as $row) {
                // $row[0] is the id, $row[1] is the city name
                $cities[strtolower(trim($row[1]))] = $row[0];
            }
        }

        for (; ($data = fgetcsv($file)) !== false;) {
            $count++;
            if (in_array($data[3], $result_mobile)) {
                echo "The number on the row " . $count . " already exist!!!";
                exit;
            } 
            
            {//Replacing the names with ids
                $countryName = strtolower(trim($data[5]));
                if (!isset($countries[$countryName])) {
                    echo "Country not found: " . $data[5];
                    exit;
                }
                $countryId = $countries[$countryName];

                // Replace the country name with the country ID
                $data[5] = $countryId;

                // For State
                $stateName = strtolower(trim($data[6]));
                if (!isset($states[$stateName])) {
                    echo "State not found: " . $data[6];
                    exit;
                }
                $stateId = $states[$stateName];
                // Replace the state name with the state ID
                $data[6] = $stateId;

                // For City
                $cityName = strtolower(trim($data[7]));
                if (!isset($cities[$cityName])) {
                    echo "City not found: " . $data[7];
                    exit;
                }
                $cityId = $cities[$cityName];
                // Replace the city name with the city ID
                $data[7] = $cityId;
            }

            // Sanitize and escape each value
            $data = array_map(function ($value) use ($conn) {
                return "'" . $conn->real_escape_string(trim($value)) . "'";
            }, $data);

            // Remove unwanted columns (indexes 0, 8, 9, 10)
            $indexesToRemove = [0];
            $data = array_diff_key($data, array_flip($indexesToRemove));

            // Convert to SQL format and store
            $values[] = "(" . implode(',', $data) . ")";
        }



        // Execute only if there are values to insert
        if (!empty($values)) {
            $sql_insert = "INSERT INTO `employees` (`first_name`, `last_name`, `mobile`, `dob`, `country`, `state`, `city`, `address`) VALUES " . implode(',', $values);

            if (!mysqli_query($conn, $sql_insert)) {
                echo "Error inserting rows: " . $conn->error;
            } else {
                echo "Successfully inserted " . count($values) . " rows!";
            }
        } else {
            echo "No valid data to insert.";
        }

        // Close file and connection
        fclose($file);
        $conn->close();
    }
} else {
    echo "No file uploaded or there was an upload error.";
}
