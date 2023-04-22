<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Пользователь не авторизован, перенаправление на главную страницу
    header("Location: index.php");
    exit;
}

if ($_SESSION['role'] !== "admin") {
    // Пользователь не является администратором, перенаправление на панель пользователя
    header("Location: user.php");
    exit;
}

// Остальной код страницы админа
?>


<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Админ-панель</h1>
    <h2 class="section-title">Управление пользователями</h2>
    
    <!-- Форма для добавления нового пользователя -->
    <div id="addUser" class="form-block">
        <h3 class="form-title">Добавить пользователя</h3>
        <form id="addUserForm" class="form">
            <label for="addLogin" class="form-label">Логин:</label>
            <input type="text" name="login" id="addLogin" class="form-input" required>
            <br>
            <label for="addPassword" class="form-label">Пароль:</label>
            <input type="password" name="password" id="addPassword" class="form-input" required>
            <br>
            <label for="addUsername" class="form-label">Имя пользователя (необязательно):</label>
            <input type="text" name="username" id="addUsername" class="form-input">
            <br>
            <label for="addRole" class="form-label">Роль:</label>
            <select name="role" id="addRole" class="form-select" required>
                <option value="user">Пользователь</option>
                <option value="admin">Администратор</option>
            </select>
            <br>
            <input type="submit" value="Добавить пользователя" class="form-submit">
        </form>
    </div>
    <!-- Здесь будет таблица со списком пользователей -->
    <div id="userList" class="table-block">
        <h3 class="section-title">Список пользователей</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Имя пользователя</th>
                    <th>Роль</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <!-- Здесь будут строки с данными пользователей -->
            </tbody>
        </table>
    </div>

    <!-- Форма для редактирования и удаления пользователей -->
    <div id="editUser" class="form-block">
        <h3 class="form-title">Редактировать пользователя</h3>
        <form id="editUserForm" class="form">
            <input type="hidden" name="id" id="editUserId" class="form-input">
            <label for="editLogin" class="form-label">Логин:</label>
            <input type="text" name="login" id="editLogin" class="form-input" required>
            <br>
            <label for="editPassword" class="form-label">Новый пароль (оставьте пустым, чтобы не изменять):</label>
            <input type="password" name="password" id="editPassword" class="form-input">
            <br>
            <label for="editUsername" class="form-label">Имя пользователя (необязательно):</label>
            <input type="text" name="username" id="editUsername" class="form-input">
            <br>
            <label for="editRole" class="form-label">Роль:</label>
            <select name="role" id="editRole" class="form-select" required>
                <option value="user">Пользователь</option>
                <option value="admin">Администратор</option>
            </select>
            <br>
            <input type="submit" value="Сохранить изменения" class="form-submit">
        </form>
        <button id="deleteUser" class="form-button">Удалить пользователя</button>
    </div>
    <button id="logout" class="logout-button">Выйти</button>



    <!-- Скрипт для обработки действий на админ-панели -->
    <script>
        $(document).ready(function() {
            // Функция для получения списка пользователей с сервера
            function loadUserList() {
                $.ajax({
                    url: "php/get_users.php",
                    method: "GET",
                    success: function(response) {
                        let users = JSON.parse(response);
                        let userTableBody = $("#userTableBody");
                        userTableBody.empty();

                        users.forEach(function(user) {
                            let row = $("<tr></tr>");
                            row.append($("<td></td>").text(user.id));
                            row.append($("<td></td>").text(user.login));
                            row.append($("<td></td>").text(user.username));
                            row.append($("<td></td>").text(user.role));
                            let actionsCell = $("<td></td>");
                            let editButton = $("<button>Редактировать</button>");

                            editButton.click(function() {
                                $("#editUserId").val(user.id);
                                $("#editLogin").val(user.login);
                                $("#editUsername").val(user.username);
                                $("#editRole").val(user.role);
                            });

                            actionsCell.append(editButton);
                            row.append(actionsCell);
                            userTableBody.append(row);
                        });
                    }
                });
            }

            loadUserList();

            // Обработчик отправки формы добавления пользователя
            $("#addUserForm").submit(function(event) {
                event.preventDefault();
                // Получение данных из формы
                let formData = $(this).serialize();

                    $.ajax({
                        url: "php/add_user.php",
                        method: "POST",
                        data: formData,
                        success: function(response) {
                            if (response === "success") {
                                alert("Пользователь успешно добавлен!");
                                loadUserList();
                            } else {
                                alert("Произошла ошибка при добавлении пользователя.");
                            }
                        }
                    });
                });

                // Обработчик отправки формы редактирования пользователя
                $("#editUserForm").submit(function(event) {
                    event.preventDefault();
                    // Получение данных из формы
                    let formData = $(this).serialize();

                    $.ajax({
                        url: "php/edit_user.php",
                        method: "POST",
                        data: formData,
                        success: function(response) {
                            if (response === "success") {
                                alert("Данные пользователя успешно обновлены!");
                                loadUserList();
                            } else {
                                alert("Произошла ошибка при обновлении данных пользователя.");
                            }
                        }
                    });
                });

                // Обработчик нажатия на кнопку удаления пользователя
                $("#deleteUser").click(function() {
                    if (confirm("Вы уверены, что хотите удалить этого пользователя?")) {
                        let userId = $("#editUserId").val();
                        $.ajax({
                        url: "php/delete_user.php",
                        method: "POST",
                        data: { id: userId },
                        success: function(response) {
                            if (response === "success") {
                                alert("Пользователь успешно удалён!");
                                loadUserList();
                            } else {
                                alert("Произошла ошибка при удалении пользователя.");
                            }
                        }
                    });
                }
            });

            // Обработчик нажатия на кнопку выхода
            $("#logout").click(function() {
                $.ajax({
                    url: "php/logout.php",
                    method: "POST",
                    success: function(response) {
                        if (response === "success") {
                            window.location.href = "index.php";
                        } else {
                            alert("Произошла ошибка при выходе.");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

                
