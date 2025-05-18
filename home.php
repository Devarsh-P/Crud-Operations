<?php
include 'connect.php';
include 'pagination.php';



// Program to display URL of current page.
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https";
else
    $url = "http";

// Here append the common URL characters.
$url .= "://";

// Append the host(domain name, ip) to the URL.
$url .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
$url .= $_SERVER['REQUEST_URI'];

$url_components = parse_url($url);
// echo "<pre>";
// print_r($url_components);
// die;

if (isset($url_components['query'])) {
    parse_str($url_components['query'], $params);
    // echo "<pre>";
    // print_r($params);
    // die;
    if (isset($params['name'])) {
        $fullName = trim($params['name']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        body {
            background-color: rgb(255, 252, 252);
            display: block;
            /* Changed from flex to block */
            padding: 20px;
            /* Added padding for better spacing */
            box-sizing: border-box;
        }

        .listing {
            width: 80%;
            /* Adjust width as needed */
            margin: 0 auto;
            /* Center the listing */
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Added shadow for better look */
        }

        .table {
            width: 100%;
            max-width: 100%;
            background-color: #fff;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
            color: #333;
        }

        .pagination {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
            clear: both;
        }

        .pagination button {
            background-color: #333;
            color: #fff;
            border: 1px solid #444;
            padding: 10px 15px;
            cursor: pointer;
            outline: none;
            border-radius: 3px;
        }

        .pagination a {
            background-color: #333;
            color: white;
            border: 1px solid #444;
            padding: 10px 15px;
            cursor: pointer;
            outline: none;
            border-radius: 3px;
        }

        .pagination button.active {
            background-color: #007bff;
        }

        .pagination button:hover {
            background-color: #555;
        }

        .images {
            width: 20px;
            height: 20px;
        }

        .input-group {
            max-width: 50vw;
        }
    </style><!-- Style -->


    <link rel="stylesheet" href="import.css">

</head>

<body>

    <div class="container">
        <div class="input-group mb-3">
            <span class="input-group-text">Search</span>
            <div class="form-floating">
                <input type="text" name="name" class="form-control" value="<?php if (isset($fullName)) {
                                                                                echo $fullName;
                                                                            } ?>"
                    id="NameInput" placeholder="Username">
                <label for="NameInput">Name</label>
            </div>
            <button id="searchButton" type="button" class="btn btn-primary">Search</button>
        </div>
    </div>


    <div class="container listing">
        <a href="index.php" class="btn btn-primary" role="button">Add User</a>
        <a href="export.php" class="btn btn-success" role="button">Export</a>
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">DoB</th>
                    <th scope="col">Country</th>
                    <th scope="col">State</th>
                    <th scope="col">City</th>
                    <th scope="col">Images</th>
                    <th scope="col">Operation</th>
                </tr>
            </thead>
            <tbody>

                <?php

                // if (isset($fullName)) {
                //     $nameArray = explode(" ", $fullName);
                //     if (isset($nameArray[1])) {
                //         $sql_emp_info = mysqli_query($conn, "SELECT e.employee_id, e.first_name, e.last_name, e.mobile, e.dob, 
                //         c.name AS country_name, 
                //         s.name AS state_name, 
                //         ci.name AS city_name
                //         FROM employees e
                //         INNER JOIN countries c ON e.country = c.id
                //         INNER JOIN states s ON e.state = s.id
                //         INNER JOIN cities ci ON e.city = ci.id
                //         WHERE e.first_name LIKE '%$nameArray[0]%'
                //         AND e.last_name LIKE '%$nameArray[1]%'
                //         ORDER BY e.employee_id LIMIT $first_row,$rows_per_page;");
                //     }
                //     else {
                //         $sql_emp_info = mysqli_query($conn, "SELECT e.employee_id, e.first_name, e.last_name, e.mobile, e.dob, 
                //         c.name AS country_name, 
                //         s.name AS state_name, 
                //         ci.name AS city_name
                //         FROM employees e
                //         INNER JOIN countries c ON e.country = c.id
                //         INNER JOIN states s ON e.state = s.id
                //         INNER JOIN cities ci ON e.city = ci.id
                //         WHERE e.first_name LIKE '%$nameArray[0]%'
                //         OR e.last_name LIKE '%$nameArray[0]%'
                //         ORDER BY e.employee_id LIMIT $first_row,$rows_per_page;");
                //     }
                // }
                // else {
                //     $sql_emp_info = mysqli_query($conn, "SELECT e.employee_id, e.first_name, e.last_name, e.mobile, e.dob, 
                //     c.name AS country_name, 
                //     s.name AS state_name, 
                //     ci.name AS city_name
                //     FROM employees e
                //     INNER JOIN countries c ON e.country = c.id
                //     INNER JOIN states s ON e.state = s.id
                //     INNER JOIN cities ci ON e.city = ci.id
                //     ORDER BY e.employee_id LIMIT $first_row,$rows_per_page;");
                // }

                $count_sql = "SELECT COUNT(*) as total FROM employees e
              INNER JOIN countries c ON e.country = c.id
              INNER JOIN states s ON e.state = s.id
              INNER JOIN cities ci ON e.city = ci.id";

                $condition = ""; // Store the WHERE condition dynamically

                if (isset($fullName) && !empty(trim($fullName))) {
                    $nameArray = explode(" ", trim($fullName));

                    if (count($nameArray) > 1) {
                        $firstName = mysqli_real_escape_string($conn, $nameArray[0]);
                        $lastName = mysqli_real_escape_string($conn, $nameArray[1]);
                        $condition = " WHERE (e.first_name LIKE '%$firstName%' AND e.last_name LIKE '%$lastName%')";
                    } else {
                        $name = mysqli_real_escape_string($conn, $nameArray[0]);
                        $condition = " WHERE (e.first_name LIKE '%$name%' OR e.last_name LIKE '%$name%')";
                    }
                }

                // Get total count
                $total_result = mysqli_query($conn, $count_sql . $condition);
                $total_row = mysqli_fetch_assoc($total_result);
                $num_of_row = $total_row['total']; // Total number of records

                // Main query with pagination
                $sql = "SELECT e.employee_id, e.first_name, e.last_name, e.mobile, e.dob, 
                        c.name AS country_name, 
                        s.name AS state_name, 
                        ci.name AS city_name
                        FROM employees e
                        INNER JOIN countries c ON e.country = c.id
                        INNER JOIN states s ON e.state = s.id
                        INNER JOIN cities ci ON e.city = ci.id"
                        . $condition . // Append WHERE condition
                        " ORDER BY e.employee_id LIMIT $first_row, $rows_per_page";

                $sql_emp_info = mysqli_query($conn, $sql);
                //-------------------------------------------

                // $num_of_row = mysqli_num_rows($sql_emp_info);
                $pages = ceil($num_of_row / $rows_per_page);

                $result_emp_info = mysqli_fetch_all($sql_emp_info);

                if (!empty($result_emp_info)) {
                    $emp_id = $result_emp_info[0][0];

                    $sql_emp_image = mysqli_query($conn, "SELECT i.employee_id,i.images FROM images i
                INNER JOIN employees e ON i.employee_id = e.employee_id
                WHERE i.employee_id >= $emp_id
                ORDER BY i.employee_id;");
                    $result_emp_image = mysqli_fetch_all($sql_emp_image);

                    $index = 0;
                    $j = 0;
                    $imageURL = 'images/';

                    foreach ($result_emp_info as $row) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $row[0]; ?></th>
                            <td><?php echo $row[1]; ?></td>
                            <td><?php echo $row[2]; ?></td>
                            <td><?php echo $row[3]; ?></td>
                            <td><?php echo $row[4]; ?></td>
                            <td><?php echo $row[5]; ?></td>
                            <td><?php echo $row[6]; ?></td>
                            <td><?php echo $row[7]; ?></td>
                            <td>
                                <?php
                                for ($i = $index; $i < count($result_emp_image); $i++) {
                                    if ($row[0] === $result_emp_image[$i][0]) {
                                        echo '<img src=' . $imageURL . $result_emp_image[$i][1] . ' class="images">';
                                    } else {
                                        $index = $i;
                                        break;
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a href="update.php?updateid=<?php echo $row[0]; ?>" class="btn btn-primary" role="button">Update</a>
                                <a href="delete.php?deleteid=<?php echo $row[0]; ?>" class="btn btn-danger" role="button">Delete</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<h3>There are no records.</h3>";
                }
                ?>

            </tbody>
        </table>
    </div>

    <!-- <div class="pagination">
        <div style="color: white;">Showing page <?php if (!isset($_GET['page-num'])) {
                                                    echo 1;
                                                } else {
                                                    echo $_GET['page-num'];
                                                } ?> of
            <?php echo $pages; ?></div>
        <a role="button" onclick="PageChange(1)">First</a>
        <?php
        if (isset($_GET['page-num']) && $_GET['page-num'] > 1) {
        ?>
            <a role="button" onclick="PageChange(<?php echo $_GET['page-num'] - 1 ?>)">Previous</a>
        <?php
        } else {
        ?>
            <a>Previous</a>
        <?php
        }
        for ($i = 1; $i <= $pages; $i++) {
            echo '<a role="button" onClick="PageChange(' . $i . ')">' . $i . '</a>';
        }
        // print( $_GET['page-num']);
        // die;
        ?>

        <?php
        if (!isset($_GET['page-num'])) {
        ?>
            <a role="button" onclick="PageChange(2)">Next</a>
        <?php
        } elseif (isset($_GET['page-num']) && $_GET['page-num'] < $pages) {
        ?>
            <a role="button" onclick="PageChange(<?php echo $_GET['page-num'] + 1 ?>)">Next</a>
        <?php
        } else {
        ?>
            <a>Next</a>
        <?php
        }
        ?>
        <button role="button" onclick="PageChange(<?php echo $pages ?>)">Last</button>


    </div> -->

    <div class="pagination">
    <div style="color: white;">Showing page 
        <?php echo isset($_GET['page-num']) ? $_GET['page-num'] : 1; ?> of <?php echo $pages; ?>
    </div>

    <button onclick="PageChange(1)">First</button>

    <?php if (isset($_GET['page-num']) && $_GET['page-num'] > 1) { ?>
        <button onclick="PageChange(<?php echo $_GET['page-num'] - 1; ?>)">Previous</button>
    <?php } else { ?>
        <button disabled>Previous</button>
    <?php } ?>

    <?php for ($i = 1; $i <= $pages; $i++) { ?>
        <button onclick="PageChange(<?php echo $i; ?>)"><?php echo $i; ?></button>
    <?php } ?>

    <?php if (!isset($_GET['page-num'])) { ?>
        <button onclick="PageChange(2)">Next</button>
    <?php } elseif (isset($_GET['page-num']) && $_GET['page-num'] < $pages) { ?>
        <button onclick="PageChange(<?php echo $_GET['page-num'] + 1; ?>)">Next</button>
    <?php } else { ?>
        <button disabled>Next</button>
    <?php } ?>

    <button onclick="PageChange(<?php echo $pages; ?>)">Last</button>
</div>

    <div class="pagination">
        <!-- Import Button -->
        <button id="openModalBtn">Import CSV</button>

        <!-- Modal structure -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Import CSV File</h2>
                <input type="file" id="csvFileInput" accept=".csv"><br><br>
                <div>
                    <h3>Preview CSV Contents:</h3>
                    <pre id="csvPreview">No file selected.</pre>
                </div>
                <br>
                <button id="cancelBtn">Cancel</button>
                <button id="importBtn">Import</button>
            </div>
        </div>

    </div>


</body>

<script src="import.js"></script>

<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        name = document.getElementById('NameInput').value;
        // console.log(name);
        location.href = `?name=${encodeURIComponent(name)}`;
    });

    function PageChange(number) {
        if (<?php echo isset($fullName) ? 'true' : 'false'; ?>) {
            location.href = `?name=${encodeURIComponent(name)}&page-num=${encodeURIComponent(number)}`;
        } else {
            location.href = `?page-num=${encodeURIComponent(number)}`;
        }
    }
</script>


</html>