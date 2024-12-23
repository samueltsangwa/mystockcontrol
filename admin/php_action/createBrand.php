<?php
require_once 'core.php';

$valid = array('success' => false, 'messages' => array());

if ($_POST) {
    // Sanitize input
    $brandName = $connect->real_escape_string(trim($_POST['brandName']));
    $brandStatus = (int)$_POST['brandStatus']; // Cast to int for security

    // Prepare the SQL statement
    $sql = "INSERT INTO brands (brand_name, brand_active, brand_status) VALUES ('$brandName', '$brandStatus', 1)";

    // Execute the query
    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";	
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while adding the brand: " . $connect->error; // Provide error message
    }

    // Close the connection
    $connect->close();

    // Return JSON response
    echo json_encode($valid);
    exit(); // Ensure no further output
}
?>
