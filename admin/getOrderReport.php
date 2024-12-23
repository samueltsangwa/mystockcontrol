
<?php 
include './includes/session.php'; 
include 'includes/slugify.php'; 
include 'includes/conn.php'; // Include the database connection

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Use the correct format 'Y-m-d'
$date = DateTime::createFromFormat('Y-m-d', $startDate);
$format = DateTime::createFromFormat('Y-m-d', $endDate);

if ($date && $format) {
    $start_date = $date->format("Y-m-d");
    $end_date = $format->format("Y-m-d");
    
    // SQL query to get the orders within the date range
    $sql = "SELECT * FROM orders WHERE order_date >= '$start_date' AND order_date <= '$end_date' and order_status = 1";
    $query = $conn->query($sql);  // Use $conn for querying the database

    // Table structure
    $table = '
    <table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
            <th>Order Date</th>
            <th>Client Name</th>
            <th>Contact</th>
            <th>Grand Total</th>
        </tr>';

    $totalAmount = 0; // Initialize total amount
    while ($result = $query->fetch_assoc()) {
        $table .= '<tr>
            <td><center>'.$result['order_date'].'</center></td>
            <td><center>'.$result['client_name'].'</center></td>
            <td><center>'.$result['client_contact'].'</center></td>
            <td><center>'.$result['grand_total'].'</center></td>
        </tr>';    
        $totalAmount += $result['grand_total']; // Sum up the total amount
    }

    // Display the total amount
    $table .= '
    <tr>
        <td colspan="3"><center>Total Amount</center></td>
        <td><center>'.$totalAmount.'</center></td>
    </tr>
    </table>';

    // Output the table
    echo $table;

} else {
    echo "Invalid date format!";
    exit;
}
?>
