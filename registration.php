<?php
date_default_timezone_set('Asia/Tashken');
require_once('mysql_connect.php');
require_once('helpers.php');
require_once('functions.php');
$is_auth = 0;
if ($is_auth === 1) {
    header("Location: index.php");
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'password', 'name', 'message'];
    $errors = [];

    function validate_field_email($email_field, $link)
    {
        if(!filter_var($email_field, FILTER_VALIDATE_EMAIL)) {
            return "Невалидный адрес";
        }
        $email = mysqli_real_escape_string($link, $email_field);
        $sql_query_empty_user = "SELECT `id` FROM users WHERE `email` = ?";
        $stmt = db_get_prepare_stmt($link, $sql_query_empty_user, [$email]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            return "Пользователь с этим email уже зарегистрирован";
        }
        return check_field($email_field);
    }

    $rules = [
        'email' => function() use ($link) {
            return validate_field_email($_POST['email'], $link);
        },
        'password' => function() {
            return check_field($_POST['password']);
        },
        'name' => function() {
            return check_field($_POST['name']);
        },
        'message' => function() {
            return check_field($_POST['message']);
        }
    ];


    foreach ($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    if(empty($errors)) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query_insert_database_user = "INSERT INTO `users` (`registration_dt`, `email`, `name`, `password`, `users_info`)
    VALUES
    (NOW(), ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $query_insert_database_user, [$_POST['email'], $_POST['name'], $password, $_POST['message']]);
        $result = mysqli_stmt_execute($stmt);
        if($result) {
            header("location: login.php");
            exit();
        } else {
            echo 'Ошибка вставки ' . mysqli_error($link);
        }
    } else {
        $content = include_template("sign-up.php", [
            'errors' => $errors,
        ]);
    }
} else {
    $content = include_template("sign-up.php", []);
}


print(include_template("layout.php", ['content' => $content, 'title' => 'Регистрация', 'user_name' => 'Odiljon', 'categories' => $categories, 'is_auth' => $is_auth]));