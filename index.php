<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin.php");
            exit;
        } else if ($_SESSION['role'] === 'user') {
            header("Location: user.php");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="login-container">
        <h1>TG77FORM</h1>
        <h2>Вход</h2>
        <form id="loginForm">
            <label for="login">Почта:</label>
            <input type="text" name="login" id="login" required>
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Войти">
        </form>
        <div id="loginError" style="color: red;">Неверный логин или пароль</div>
    </div>
    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(event) {
                event.preventDefault(); // Отмена отправки формы по умолчанию
                let login = $("#login").val(); // Получение значения логина из формы
                let password = $("#password").val(); // Получение значения пароля из формы

                $.ajax({
                    url: "php/login.php", // URL для отправки AJAX запроса
                    method: "POST", // Метод HTTP запроса
                    data: { // Данные, которые будут отправлены на сервер
                        login: login,
                        password: password
                    },
                    success: function(response) { // Функция, которая будет выполнена при успешном завершении запроса
                        if (response === "admin") { // Если пользователь является администратором
                            // Пользователь является администратором, перенаправление на админ-панель
                            window.location.href = "admin.php";
                        } else if (response === "user") { // Если пользователь является обычным пользователем
                            // Пользователь является обычным пользователем, перенаправление на панель пользователя
                            window.location.href = "user.php";
                        } else {
                            // Ошибка авторизации, отображение сообщения об ошибке
                            $("#loginError").show();
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
