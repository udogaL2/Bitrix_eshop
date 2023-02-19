<?php /** @var string $error */ ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link href="/reset.css" rel="stylesheet">
	<link href="/style.css" rel="stylesheet">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
</head>
<body>
<div class="content-auth">
    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endif; ?>
	<div class="content-auth-title">Авторизация</div>
	<form action="/auth" method="post">
		<label class="content-auth">
			<input type="text" name="login" class="content-auth-login" placeholder="Логин" minlength="3" maxlength="15" required>
		    <span class="validity"></span>
			<input type="password" name="password" class="content-auth-password" placeholder="Пароль" minlength="3" maxlength="15" required>
		    <span class="validity"></span>
            <button class="content-auth-button" type="submit">Войти</button>
        </label>
	</form>
</div>

</body>
</html>
