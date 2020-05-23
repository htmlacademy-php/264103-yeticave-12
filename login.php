<?php
require_once('init.php');
require_once('helpers.php');

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $errors = [];

    $rules = [
        "email" => function () use ($con) {
            return validate_email_field($_POST["email"], $con);
        },
        "password" => function ()  use ($con) {
            return validate_password_field($_POST["password"], $_POST["email"], $con);
        }
    ];

    $errors = validation_form($_POST, $rules);

    if (empty($errors)) {
        $result = get_data_user($con, $_POST["email"]);
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
