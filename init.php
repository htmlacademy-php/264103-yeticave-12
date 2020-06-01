<?php
session_start();

define("DB_USER", "root");
define("DB_PASSWORD", "");
define("PATH_UPLOADS_IMAGE", "uploads/");
define("DB_HOST", "127.0.0.1");
define("DB_NAME", "mysql");
define("COUNT_ITEMS", "9");

date_default_timezone_set("Asia/Tashkent");

require_once "mysql_connect.php";
require_once "functions.php";

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
$result_categories = mysqli_query($con, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
