<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
    <?php
    include 'connect.php';
    if (isset($_GET['imageid'])) {
        $emp_id = $_GET['imageid'];
        print($emp_id);
        $sql_get_image = mysqli_query($conn, "SELECT `employee_id`,`images` FROM `employees` WHERE `employee_id` = '$emp_id'");
        $result = mysqli_fetch_all($sql_get_image);
        print_r($result);
        echo '<p>'.$result[0][0].'</p><br>';
        $imageURL = 'images/'.$result[0][1];
        echo '<img src='.$imageURL.'>';
    }

    ?>
    </div>
</body>
</html>