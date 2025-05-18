<?php

include 'connect.php';
exportToCSV($conn, 'employees', 'employees_export.csv');

function exportToCSV($conn, $tableName, $fileName = 'export.csv') {

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
    $output = fopen('php://output', 'w');

    $columnNames = ["employee_id", "first_name", "last_name", "mobile", "dob", 
    "country_name", "state_name", "city_name", "address"];
    fputcsv($output, $columnNames);

    $dataQuery = mysqli_query($conn, "SELECT e.employee_id, e.first_name, e.last_name, e.mobile, e.dob, 
                c.name AS country_name, 
                s.name AS state_name, 
                ci.name AS city_name,
                e.address
                FROM employees e
                INNER JOIN countries c ON e.country = c.id
                INNER JOIN states s ON e.state = s.id
                INNER JOIN cities ci ON e.city = ci.id;");
               
    $result_data = mysqli_fetch_all($dataQuery);

    foreach($result_data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}


?>