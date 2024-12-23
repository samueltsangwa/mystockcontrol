<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    $sql = "UPDATE orders SET order_status = 2 WHERE order_id = {$orderId}";
    $orderItem = "UPDATE order_item SET order_item_status = 2 WHERE order_id = {$orderId}";

    if ($connect->query($sql) === TRUE && $connect->query($orderItem) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Removed";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while removing the order";
    }
} else {
    $valid['success'] = false;
    $valid['messages'] = "No order ID provided";
}

$connect->close();
echo json_encode($valid);
?> 