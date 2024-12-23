<?php
// yourUpdateScript.php

// Include database connection and any required files
include 'db_connection.php';

// Check if data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['productName'];
    $rate = $_POST['rate'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $status = $_POST['status'];

    // SQL query to update the product
    $query = "UPDATE products SET product_name = ?, rate = ?, quantity = ?, size = ?, status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdisss", $product_name, $rate, $quantity, $size, $status, $product_id);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['success' => true, 'message' => 'Product updated successfully.']);
    } else {
        // Return failure response
        echo json_encode(['success' => false, 'message' => 'Failed to update product.']);
    }

    $stmt->close();
}
?>
