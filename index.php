<?php
date_default_timezone_set("Asia/Tashken");

require_once('helpers.php');
require_once('mysql_connect.php');
require_once('functions.php');

$is_auth = 1;

$user_name = 'Odiljon';

$title = "YiteCave";

mysqli_set_charset($link, "utf8");

$sql = 'SELECT `id`, `code`, `name` FROM categories';
$result_cat = mysqli_query($link, $sql);
$categories = mysqli_fetch_all($result_cat, MYSQLI_ASSOC);

$sql = 'SELECT `lots`.`id`, `lots`.`name`, `lots`.`st_coast`, `lots`.`dt_add`, `lots`.`end_date`, `lots`.`link`, `category`.`name` AS categoriy_name FROM `lots` 
INNER JOIN `categories` AS category 
ON `lots`.`category_id` = `category`.`id`
WHERE `lots`.`end_date` > NOW()
ORDER BY `dt_add` DESC LIMIT 6';

$result_lots = mysqli_query($link, $sql);
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);


print(include_template('layout.php', ['user_name' => $user_name, 'title' => 'Главная', 'content' => $content, 'lots' => $lots, 'categories' => $categories, 'is_auth' => $is_auth]));

  ?>