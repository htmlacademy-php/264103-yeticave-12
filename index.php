<?php
require_once('init.php');
require_once('helpers.php');

$sql_lots = "SELECT lot.id, lot.name, lot.st_coast, lot.link, lot.dt_add, lot.end_date, category.name AS categoriy_name FROM `lots` as lot
INNER JOIN `categories` AS category
ON lot.category_id = category.id
WHERE lot.end_date > NOW()
ORDER BY `dt_add` DESC LIMIT 6";

$result_lots = mysqli_query($con, $sql_lots);
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$content = include_template("main.php", ["categories" => $categories, "lots" => $lots]);


print(include_template("layout.php", ["content" => $content, "title" => "Главная страница", "user_name" => $_SESSION["user"]["name"] ?? "", "categories" => $categories]));
