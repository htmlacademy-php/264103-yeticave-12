<?php
require_once('init.php');
require_once('helpers.php');

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

        $content = include_template("current_lot.php", [
            'categories' => $categories, 'lot' => $lot, 'bids' => $bids]);
    } else {
        $content = include_template("error.php", [
            "code_error" => "404", "text_error" => "Не удалось найти страницу с лотом №-" . "<b>" . $id_lot . "</b>",
        ]);
    }
} else {
        $content = include_template("error.php", [
            'text_error' => "Ошибка подключения: " . mysqli_errno($link)
        ]);
}

print(include_template("layout.php", ["content" => $content, "user_name" => $_SESSION["user"]["name"] ?? "", "title" => "Страница лота", "categories" => $categories]));
