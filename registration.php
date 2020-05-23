<?php
require_once('init.php');
require_once('helpers.php');

if (isset($_SESSION["user"])) {
    http_response_code(403);
    $content = include_template("error.php", [
        "categories" => $categories,
        "code_error" => "403",
        "text_error" => "Страница для незарегистрированных пользователей"
    ]);
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    $rules = [
        'email' => function() use ($con) {
            return validate_field_email($_POST['email'], $con);
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
        $stmt = db_get_prepare_stmt($con, $query_insert_database_user, [$_POST['email'], $_POST['name'], $password, $_POST['message']]);
        $result = mysqli_stmt_execute($stmt);
        if($result) {
            header("location: login.php");
            exit();
        } else 
            echo 'Ошибка вставки ' . mysqli_error($con);
    } else {
        $content = include_template("sign-up.php", ["errors" => $errors,]);
    }
} else {
    $content = include_template("sign-up.php", []);
}

print(include_template("layout.php", ["content" => $content, "title" => "Регистрация", "user_name" => $_SESSION["user"]["name"] ?? '', "categories" => $categories]));
