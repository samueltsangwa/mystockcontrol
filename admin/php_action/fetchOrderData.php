<?php

require_once 'db_connect.php';

$valid = array('order' => array(), 'order_item' => array());

// Check if 'orderId' is set in POST data
if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    $sql = "SELECT orders.order_id, orders.order_date, orders.client_name, orders.client_contact, orders.sub_total, 
                   orders.vat, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, 
                   orders.payment_type, orders.payment_status 
            FROM orders 	
            WHERE orders.order_id = {$orderId}";

    // Execute query only if the connection is open
    if ($connect) {
        $result = $connect->query($sql);

        // Check if the query returned any results
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_row();
            $valid['order'] = $data;
        } else {
            $valid['order'] = null; // No matching order found
        }
        
        $connect->close(); // Close the connection after query execution
    } else {
        $valid['messages'] = "Database connection error";
    }
} else {
    $valid['messages'] = "No order ID provided";
}

echo json_encode($valid);
?>
