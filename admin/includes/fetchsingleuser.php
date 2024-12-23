<?php
include 'includes/conn.php';

if (isset($_GET['userId'])) {
    $userId = intval($_GET['userId']);
    $stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        echo json_encode($user ? $user : ['error' => 'User not found']);
    } else {
        echo json_encode(['error' => 'Database query failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>