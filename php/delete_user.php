<?php
require_once 'config.php';

$userId = $_POST['id'];

$query = "DELETE FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$result = $stmt->execute();

if ($result) {
    echo "success";
} else {
    echo "error";
}
