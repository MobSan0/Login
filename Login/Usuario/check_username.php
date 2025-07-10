<?php
require_once 'connect.php';

header('Content-Type: application/json');

if(isset($_GET['username'])) {
    $username = $con->real_escape_string($_GET['username']);
    
    $stmt = $con->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    echo json_encode(['exists' => $stmt->num_rows > 0]);
    $stmt->close();
} else {
    echo json_encode(['exists' => false]);
}
?>