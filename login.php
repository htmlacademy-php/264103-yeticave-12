<?php
date_default_timezone_set('Asia/Tashken');
require_once('mysql_connect.php');
require_once('helpers.php');
require_once('functions.php');

$content = include_template("login.php", [
]);

print(include_template("layout.php", ['content' => $content,  'page' => 'Вход', 'user_name' => 'Odiljon', 'categories' => $categories, 'is_auth' => $is_auth]));
?>