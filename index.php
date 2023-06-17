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
    </script>
</header>
<main>
    <button class="new-task-btn"><a href="new_task.html">Новое дело</a></button>

    <?php foreach ($userTodos as $todo): ?>
        <ul class="task-list">
            <li>
                <span class="task-title"><?= $todo['task'] ?></span>
                <div class="task-description"><?= $todo['description'] ?></div>
                <div class="task-actions">
                    <?php
                    $_SESSION["idTask"] = $todo['id'];
                    ?>
                    <form class="task-done-btn" action="./func/Todo_func/update_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $todo['id'] ?>">
                        <button type="submit" class="task-done-btn">Выполнено</button>
                    </form>
                    <form class="task-delete-btn" action="./func/Todo_func/update_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $todo['id'] ?>">
                        <button class="task-delete-btn">Удалить</button>
                    </form>
                    <form class="task-edit-btn" action="./func/Todo_func/update_task.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $todo['id'] ?>">
                        <button class="task-edit-btn">Редактировать</button>
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