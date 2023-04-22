<?php
// Параметры подключения
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "323";

try {
    // Создание подключения
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Установка атрибутов PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Вывод ошибки подключения
    echo "Ошибка подключения: " . $e->getMessage();
}

