<?php
require_once 'config.php'; // Подключение файла с настройками базы данных

$login = $_POST['login']; // Получение логина из формы
$password = $_POST['password']; // Получение пароля из формы

$query = "SELECT * FROM users WHERE login = :login"; // Запрос на выборку пользователей из базы данных по логину
$stmt = $pdo->prepare($query); // Подготовка запроса
$stmt->bindParam(':login', $login, PDO::PARAM_STR); // Привязка параметров к запросу
$stmt->execute(); // Выполнение запроса
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Получение данных пользователя из базы данных

if ($user && $user['password'] == $password) { // Если пользователь найден и пароль верный
    // Пароль верный, авторизация успешна
    session_start(); // Начало сессии
    $_SESSION['user_id'] = $user['id']; // Сохранение идентификатора пользователя в сессии
    $_SESSION['username'] = $user['username']; // Сохранение имени пользователя в сессии
    $_SESSION['role'] = $user['role']; // Сохранение роли пользователя в сессии
    echo $_SESSION['role']; // Вывод роли пользователя
} else {
    // Неверный логин или пароль
    echo "error"; // Вывод сообщения об ошибке
}
