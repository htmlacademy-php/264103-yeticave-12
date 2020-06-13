<?php
session_start();
ini_set("error_reporting", E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("PATH_UPLOADS_IMAGE", "uploads/");
define("DB_HOST", "127.0.0.1");
define("DB_NAME", "mysql");
define("COUNT_ITEMS", "9");
define("MAIL", [
    "host" => "smtp.mailtrap.io",
    "port" => 2525,
    "encryption" => "tls",
    "username" => "913ac37e38ecd8",
    "password" => "64542b20aea49b",
]);
define("IMAGE_PARAMETERS", [
   "width" => 800,
   "height" => 600,
]);
define("IMAGE_QUALITY", [
    "jpeg_quality" => 60,
    "png_compression_level" => 7,
]);

date_default_timezone_set("Asia/Tashkent");

require_once "mysql_connect.php";
require_once "functions.php";

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
$result_categories = mysqli_query($con, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
