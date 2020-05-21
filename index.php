<?php
require_once('init.php');
require_once('helpers.php');

$sql = "SELECT lot.id, lot.name, lot.st_coast, lot.dt_add, lot.end_date, lot.link, category.name AS categoriy_name FROM `lots` as lot
INNER JOIN `categories` AS category
ON lot.category_id = category.id
WHERE lot.end_date > NOW()
ORDER BY `dt_add` DESC LIMIT 6";

$result_lots = mysqli_query($link, $sql);
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);


print(include_template("layout.php", ["content" => $content, "user_name" => $_SESSION["user"]["name"] ?? "", "title" => "Главная страница", "lots" => $lots, "categories" => $categories]));
