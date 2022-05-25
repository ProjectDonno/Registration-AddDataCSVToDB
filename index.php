<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка данных в бд</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
<div class="container mt-4">
        <?php
        session_start();
        if($_SESSION["user_name"] == ''):
        ?>
            <div class="row">
                <div class="col">
                    <h1>Форма регистрации</h1>
                    <form action="validation-form/reg.php" method="post">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин"> <br/>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя"> <br/>
                        <input type="text" class="form-control" name="pass" id="pass" placeholder="Введите пароль"> <br/>
                        <button type="submit" class="btn btn-success">Зарегистрировать</button> <br/>
                    </form>
                </div>
                <div class="col">
                    <h1>Форма авторизации</h1>
                    <form action="validation-form/auth.php" method="post">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин"> <br/>
                        <input type="text" class="form-control" name="pass" id="pass" placeholder="Введите пароль"> <br/>
                        <button type="submit" class="btn btn-success">Авторизоваться</button> <br/>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <form action="add.php" method="post" enctype="multipart/form-data">
                <p>Привет <?=$_SESSION["user_name"]?>. Чтобы выйти нажмите <a href="exit.php">здесь</a> </p> <br/>

                <input type="file" name="file"> <br/>
                <input type="submit" value="Записать"> <br/>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>