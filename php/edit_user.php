<?php
require_once 'config.php';

$userId = $_POST['id'];
$login = $_POST['login'];
$password = $_POST['password'];
$username = $_POST['username'];
$role = $_POST['role'];

$query = "UPDATE users SET login = :login, password = :password, username = :username, role = :role WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':login', $login, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':role', $role, PDO::PARAM_STR);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$result = $stmt->execute();

if ($result) {
    echo "success";
} else {
    echo "error";
}
