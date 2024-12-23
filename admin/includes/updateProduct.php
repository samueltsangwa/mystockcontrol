<?php
require_once 'conn.php'; // Include database connection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $rate = $_POST['rate'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $status = $_POST['status'];

    // Update query
    $sql = "UPDATE product SET product_name = ?, rate = ?, quantity = ?, size = ?, status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdissi", $product_name, $rate, $quantity, $size, $status, $product_id);

    if ($stmt->execute()) {
        // Redirect back to products.php
        header('Location: http://localhost/mystockcontrol/admin/products.php');
        exit;
    } else {
        // Redirect with error message (Optional)
        header('Location: http://localhost/mystockcontrol/admin/products.php?error=update_failed');
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>