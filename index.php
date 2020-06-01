<?php
require_once('init.php');
require_once('helpers.php');
require_once('get_winner.php');

$sql_lots = "SELECT 
    lot.id, 
    lot.name, 
    lot.st_coast, 
    lot.link, 
    lot.dt_add, 
    lot.end_date, 
    category.name AS category_name 
    FROM `lots` as lot
    INNER JOIN `categories` as category
    ON lot.category_id = category.id
    WHERE lot.end_date > NOW()
   ORDER BY `dt_add` DESC LIMIT " . COUNT_ITEMS;
$result_lots = mysqli_query($con, $sql_lots);
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$content = include_template("main.php", [
    "categories" => $categories,
    "lots" => $lots
]);


$layout_content = include_template("layout.php", [
    "content" => $content,
    "title_page" => "Главная страница",
    "user_name" => session_user_value("name", ""),
    "categories" => $categories,
]);

print($layout_content);