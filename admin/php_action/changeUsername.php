<?php
session_start(); // Start the session
require_once 'conn.php';

if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];

    if (!empty($username) && !empty($email)) {
        // Use prepared statements for security
        $stmt = $connect->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $username, $email, $user_id);

        if ($stmt->execute()) {
            // Set success message in session
            $_SESSION['success_message'] = "Changes have been saved successfully.";
            // Successfully updated, redirect to setting.php
            header("Location: http://localhost/stocksale/setting.php");
            exit(); // Make sure to call exit after header
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Both Username and Email fields are required.";
    }

    $connect->close();
}
?>
