<?php
    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

    $pass = md5($pass."wdwdwd"); // ."соль"

    $mysql = new mysqli('localhost', 'root', 'root', 'register-bd');

    $result = $mysql->query("SELECT * FROM `users` WHERE `login` = '$login' AND `pass` = '$pass'");
    
    // конвертация в массив
    $user = $result->fetch_assoc();

    if(count($user) == 0) {
        echo "Такой пользователь не найден";
        exit();
    }

    session_start();
    $_SESSION["user_name"] = $user['name'];
    $_SESSION["user_id"] = $user['id'];

    $mysql->close();

    header('Location: /');
?>