<?php
require_once('init.php');
require_once('helpers.php');

if (isset($_SESSION["user"])) {
    http_response_code(403);
    $content = include_template("error.php", ["categories" => $categories, "code_error" => "403", "text_error" => "Страница для незарегистрированных пользователей"]);
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    function validate_field_email($email_field, $link)
    {
        if(filter_var($email_field, FILTER_VALIDATE_EMAIL)) {
            $email = mysqli_real_escape_string($link, $email_field);
            $sql_query_empty_user = "SELECT `id` FROM users WHERE `email` = ?";
            $stmt = db_get_prepare_stmt($link, $sql_query_empty_user, [$email]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                return "Пользователь с этим email уже зарегистрирован";
            }
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

    $errors = validation_form($_POST, $rules);

    if(empty($errors)) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query_insert_database_user = "INSERT INTO `users` (`registration_dt`, `email`, `name`, `password`, `users_info`)
    VALUES
    (NOW(), ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $query_insert_database_user, [$_POST['email'], $_POST['name'], $password, $_POST['message']]);
        $result = mysqli_stmt_execute($stmt);
        if($result) {
            header("Location: login.php");
            exit();
        } else {
            echo 'Ошибка вставки ' . mysqli_error($link);
        }
    } else {
        $content = include_template("sign-up.php", ["errors" => $errors,]);
    }
} else {
    $content = include_template("sign-up.php", []);
}

print(include_template("layout.php", ["content" => $content, "title" => "Регистрация", "user_name" => $_SESSION["user"]["name"] ?? '', "categories" => $categories]));
