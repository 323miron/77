<?php
require_once 'config.php'; // Подключение файла с настройками базы данных

$login = $_POST['login'];
$password = $_POST['password'];
$username = $_POST['username'];
$role = $_POST['role'];

// Проверка, существует ли пользователь с таким же логином в базе данных
$query = "SELECT * FROM users WHERE login = :login";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':login', $login, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) { // Если пользователь уже существует
    echo "error"; // Вывод сообщения об ошибке
} else { // Если пользователь не существует
    // Добавление нового пользователя в базу данных
    $query = "INSERT INTO users (login, password, username, role) VALUES (:login, :password, :username, :role)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->execute();

    echo "success"; // Вывод сообщения об успехе
}
