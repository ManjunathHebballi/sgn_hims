<?php
// Database configuration
$database_host = '192.168.1.99'; // Local MySQL server IP address
$database_port = '3306'; // MySQL server port (default is 3306)
$database_name = 'carefoul_bgh715'; // Database name
$database_user = 'bgh'; // Database username
$database_pass = 'B#10Bql%12SkL@'; // Database password

// Connect to the local MySQL database
$mysqli = new mysqli($database_host, $database_user, $database_pass, $database_name, $database_port);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to fetch data from the 'country' table
$sql = "SELECT * FROM `country`";
$result = $mysqli->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Fetch associative array of fetched data
    $data = array();
    while ($row = $result->fetch_assoc()) {
        // Add each row's data to the result array
        $data[] = $row;
    }
    // Output data as JSON
    echo json_encode($data);
} else {
    // No records found
    echo json_encode(array('message' => 'No records found'));
}

// Close database connection
$mysqli->close();
?>
