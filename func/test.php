


<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Название вашего сайта</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">

</head>

<body>
	<header>
		<div class="logo">
			<span class="logo-text">To-Do</span>
		</div>
		<nav>
			<ul>
				<li><a href="login.html">Вход</a></li>
				<li><a href="register.html">Регистрация</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<div class="login-form">
			<h2>Вход</h2>
			<form class="form__to" action="func/login.php" method="post">
				<div class="form-row">
					<label for="login">Логин:</label>
					<input type="text" id="login" name="login" required>
				</div>
				<div class="form-row">
					<label for="password">Пароль:</label>
					<input type="password" id="password" name="password" required>
				</div>
				<div>
				<?php

                session_start();
                require_once "../func/connect.php";
                $data = json_decode($_COOKIE["user"], true);
                $id = $data["user_id"];
                $sql = $pdo->prepare("SELECT * FROM todos WHERE user_id = :user_id");
                $sql->execute(array(":user_id" => $id));
                $userTodos = $sql->fetchAll(PDO::FETCH_ASSOC);
                print_r($userTodos);

                ?>
				</div>
				<button type="submit">Вход</button>
				<button type="submit">Вспомнить пароль</button>
			</form>
		</div>
	</main>
	<footer>
		<!-- Здесь будет подвал страницы -->
	</footer>
</body>

</html>