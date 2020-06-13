<?php
require_once("init.php");
require_once("helpers.php");

if (!isset($_SESSION["user"])) {
    header("Location: /");
}

list($count_lots, $page_count) = compute_pagination_offset_and_limit(
    $con,
    "SELECT COUNT(id) AS 'count' FROM `bids` WHERE `user_id` = ?",
    get_value_from_user_session("id")
);
$current_page = get_page_value();
$offset = get_offset_items($current_page, COUNT_ITEMS);
$current_id = get_escape_string($con, get_value_from_user_session("id"));
$result_bids = mysqli_query(
    $con,
    "SELECT
            lots.name, 
            lots.link, 
            categories.name AS category, 
            lots.end_date, 
            bids.dt_add, 
            bids.price, 
            lots.description, 
            lots.id, 
            lots.winner_id, 
            users.users_info  
        FROM `bids` AS bids 
        JOIN `lots` AS lots ON lots.id = bids.lot_id 
        JOIN `categories` AS categories ON categories.id = lots.category_id
        JOIN `users` AS users ON users.id = lots.author_id
        WHERE `user_id` = " . $current_id . " 
        ORDER BY bids.dt_add DESC 
        LIMIT " . COUNT_ITEMS . " OFFSET " . $offset
);
get_error($con);
$bets = mysqli_fetch_all($result_bids, MYSQLI_ASSOC);

$content = include_template("bets.php", [
    "bets" => $bets,
    "user_id" => get_value_from_user_session("id"),
    "count_lots" => $count_lots,
    "page_count" => $page_count,
    "current_page" => $current_page,
]);

$layout_content = include_template("layout.php", [
    "content" => $content,
    "title_page" => "Страница лота",
    "user_name" => get_value_from_user_session("name"),
    "categories" => $categories,
]);

print($layout_content);