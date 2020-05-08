<?php 
date_default_timezone_set("Asia/Tashkent");
require_once('helpers.php');
require_once('mysql_connect.php');

$sql_categories = "SELECT `name`, `code` FROM `categories`";
$result_categories = mysqli_query($link, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

$id_lot = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$current_lot = "SELECT lot.id, lot.name, lot.step_bids, lot.end_date, lot.st_coast, lot.description, lot.link, lot.dt_add, lot.end_date, category.name as category_name
FROM `lots` as lot
INNER JOIN `categories` as category
ON lot.category_id = category.id
WHERE lot.id = $id_lot";

$sql_bids = "SELECT users.name, bids.sum, bids.dt_add FROM `bids` as bids
INNER JOIN `users` as users
ON bids.user_id = users.id
WHERE lot_id = $id_lot
ORDER BY bids.dt_add DESC";

$result_bids = mysqli_query($link, $sql_bids);
$bids = mysqli_fetch_all($result_bids, MYSQLI_ASSOC);

if ($result_lot = mysqli_query($link, $current_lot)) {
    if (mysqli_num_rows($result_lot)) {
        $lot = mysqli_fetch_assoc($result_lot);

        $page_content = include_template("current_lot.php", [
            'categories' => $categories, 'lot' => $lot, 'bids' => $bids]);
    } 
    else {        
        $page_content = include_template("error.php", [
            'text_error' => '404 Страница не найдена'
        ]);
    }
   } else {
        $page_content = include_template("error.php", [
            'text_error' => "Ошибка подключения: " . mysqli_errno($link)
        ]);
}


print(include_template('layout.php', ['user_name' => $user_name, 'title' => 'Карточка лота', 'content' => $page_content, 'categories' => $categories]));
?>