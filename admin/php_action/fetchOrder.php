<?php  

require_once 'core.php';

$output = array('data' => array());

if ($connect) { // Check if the database connection is open
    // Debugging line
    if ($connect->connect_errno) {
        die("Failed to connect to MySQL: " . $connect->connect_error);
    }

    // Query for orders where status is 1
    $sql = "SELECT order_id, order_date, client_name, client_contact, payment_status FROM orders WHERE order_status = 1";
    // $result = $connect->query($sql);

    if ($result && $result->num_rows > 0) { 
        $x = 1;

        while ($row = $result->fetch_array()) {
            $orderId = $row[0];

            // Query to count items in each order
            $countOrderItemSql = "SELECT COUNT(*) FROM order_item WHERE order_id = $orderId";
            $itemCountResult = $connect->query($countOrderItemSql);
            $itemCountRow = $itemCountResult ? $itemCountResult->fetch_row()[0] : 0;

            // Determine payment status
            $paymentStatus = match ($row[4]) {
                1 => "<label class='label label-success'>Full Payment</label>",
                2 => "<label class='label label-info'>Advance Payment</label>",
                default => "<label class='label label-warning'>No Payment</label>",
            };

            // Action buttons
            $button = '<div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="orders.php?o=editOrd&i='.$orderId.'" id="editOrderModalBtn"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                        <li><a type="button" data-toggle="modal" id="paymentOrderModalBtn" data-target="#paymentOrderModal" onclick="paymentOrder('.$orderId.')"> <i class="glyphicon glyphicon-save"></i> Payment</a></li>
                        <li><a type="button" onclick="printOrder('.$orderId.')"> <i class="glyphicon glyphicon-print"></i> Print </a></li>
                        <li><a type="button" data-toggle="modal" data-target="#removeOrderModal" id="removeOrderModalBtn" onclick="removeOrder('.$orderId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
                    </ul>
                </div>';

            // Append data to output array
            $output['data'][] = array(
                $x,
                $row[1], // order date
                $row[2], // client name
                $row[3], // client contact
                $itemCountRow, // order item count
                $paymentStatus, // payment status label
                $button // action buttons
            );

            $x++;
        }
    } else {
        $output['messages'] = "No orders found";
    }

    $connect->close(); // Close the connection after all queries
} else {
    $output['messages'] = "Database connection error";
}

echo json_encode($output);
?>
