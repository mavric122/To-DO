<?php
session_start();
require_once "./func/connect.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Название вашего сайта</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>

<header>
    <div class="logo">
        <span class="logo-text">To-Do</span>
    </div>
    <div>
        <?php if (isset($_COOKIE["user"])) {
            $data = json_decode($_COOKIE["user"], true);
            $login = $data["login"];
            $id = $data["user_id"];
            $sql = $pdo->prepare("SELECT * FROM todos WHERE user_id = :user_id AND Status = 1");
            $sql->execute(array(":user_id" => $id));
            $userTodos = $sql->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <nav>
                <ul>
                    <li><a href="#">
                            <?php
                            echo $login;
                            ?>
                        </a></li>
                    <li><a href="#" onclick="logout()">Выход</a></li>
                </ul>
            </nav>
        <?php } else { ?>
            <nav>
                <ul>
                    <li><a href="login.html">Вход</a></li>
                    <li><a href="register.html">Регистрация</a></li>
                </ul>
            </nav>
        <?php } ?>
    </div>

    <script>
        function logout() {
            // Удаление куки
            document.cookie = "user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

            // Перенаправление на страницу выхода или другую страницу
            window.location.href = "index.php";
        }

        // Обработчик события клика на кнопках
        document.querySelectorAll('.task-done-btn, .task-delete-btn, .task-edit-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var taskId = this.dataset.taskId;
                var formData = new FormData();
                formData.append('task_id', taskId); // Добавление task_id в FormData
                formData.append('action', 'edit'); // Добавление значения 'edit' для action

                // Отправка данных на сервер с использованием Fetch API
                fetch('func/Todo_func/update_task.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data); // Обработка ответа от сервера
                    })
                    .catch(error => {
                        console.error('Произошла ошибка:', error);
                    });
            });
        });
    </script>
</header>

<main>
    <button class="new-task-btn"><a href="new_task.html">Новое дело</a></button>
    <button class="new-task-btn"><a href="completed_task.php">Законченные дела</a></button>
    <div>
        <?php
        if (isset($_SESSION["msg"])) {
            echo($_SESSION["msg"]);
            unset($_SESSION["msg"]);
        }
        ?>
    </div>
    <?php foreach ($userTodos as $todo): ?>
        <ul class="task-list">
            <li>
                <span class="task-title"><?= $todo['task'] ?></span>
                <div class="task-description"><?= $todo['description'] ?></div>
                <div class="task-actions">
                    <form class="task-done" action="./func/Todo_func/update_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $todo['id'] ?>">
                        <input type="hidden" name="action" value="done"> <!-- Новое поле action -->
                        <button type="submit" class="task-done-btn btn" data-task-id="<?= $todo['id'] ?>">Выполнено</button>
                    </form>
                    <form class="task-delete" action="./func/Todo_func/delete_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $todo['id'] ?>">
                        <input type="hidden" name="action" value="delete"> <!-- Новое поле action -->
                        <button class="task-delete-btn btn" data-task-id="<?= $todo['id'] ?>">Удалить</button>
                    </form>
                    <form class="task-edit" action="./func/Todo_func/edit_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $todo['id'] ?>">
                        <button class="task-edit-btn btn" data-task-id="<?= $todo['id'] ?>">Редактировать</button>
                    </form>
                </div>
            </li>
        </ul>
    <?php endforeach; ?>
</main>
<!-- Здесь будет подвал страницы -->
</footer>
</body>

</html>