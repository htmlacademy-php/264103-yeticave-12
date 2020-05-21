<?php
require_once('init.php');
require_once('helpers.php');

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $errors = [];

    function get_data_user($link, $email_field)
    {
        $email = mysqli_real_escape_string($link, $email_field);
        $sql_query_data= "SELECT * FROM `users` WHERE `email` = ? ";
        $stmt = db_get_prepare_stmt($link, $sql_query_data, [$email]);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    function validate_email_field($email_field, $link)
    {
        if (empty($email_field)) {
            return "Это поле обязательно к заполнению";
        }
        $result = get_data_user($link, $email_field);
        if (mysqli_num_rows($result) === 0) {
            return "Нет пользователя с таким адресом";
        }
    }

    function validate_password_field($password_field, $email_field, $link)
    {
        if(empty($password_field)) {
            return "Это поле обязательно к заполнению";
        }
        $result = get_data_user($link, $email_field);
        $user_data = $result ? mysqli_fetch_assoc($result) : null;
        if (!password_verify($password_field, $user_data["password"])) {
            return "Указан неверный пароль";
        }
    }

    $rules = [
        "email" => function () use ($link) {
            return validate_email_field($_POST["email"], $link);
        },
        "password" => function ()  use ($link) {
            return validate_password_field($_POST["password"], $_POST["email"], $link);
        }
    ];

    $errors = validation_form($_POST, $rules);

    if (empty($errors)) {
        $result = get_data_user($link, $_POST["email"]);
        $_SESSION["user"] = $result ? mysqli_fetch_assoc($result) : null;
        header("Location: index.php");
        exit();
    } else {
        $content = include_template("login.php", [
            "errors" => $errors, "text_errors" => "Вы ввели неверный email/пароль",
        ]);
    }
} else {
    $content = include_template("login.php", []);
}

print(include_template("layout.php", ["content" => $content, "title" => "Авторизация на сайте", "categories" => $categories]));
