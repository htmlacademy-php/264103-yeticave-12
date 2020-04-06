<?php
date_default_timezone_set("Europe/Moscow");

require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Odiljon'; // укажите здесь ваше имя

$title = "YiteCave";

function decorate_cost($input) {
  $symbol = " ₽";

    if (is_int($input)) {
      $output = print(number_format($input, 0, ".", " ")) . $symbol;
    }

  return $output;
};

function get_dt_range($value_date) {
	$time_difference = strtotime($value_date) - time();
	$time_hours = floor($time_difference / 3600);
	$time_minutes = floor(($time_difference % 3600) / 60);
	return [$time_hours, $time_minutes];
};


$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

$items = [
                [
                  "name" => "2014 Rossignol District Snowboard",
                  "category" => "Доски и лыжи",
                  "cost" => 10999,
                  "picture" => "img/lot-1.jpg",
                  "endDate" => "2020-05-07"
                ],

                [
                  "name" => "DC Ply Mens 2016/2017 Snowboard",
                  "category" => "Доски и лыжи",
                  "cost" => 159999,
                  "picture" => "img/lot-2.jpg",
                  "endDate" => "2020-05-12"
                ],

                [
                  "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
                  "category" => "Крепления",
                  "cost" => 8000,
                  "picture" => "img/lot-3.jpg",
                  "endDate" => "2020-05-11"
                ],

                [
                  "name" => "Ботинки для сноуборда DC Mutiny Charocal",
                  "category" => "Ботинки",
                  "cost" => 10999,
                  "picture" => "img/lot-4.jpg",
                  "endDate" => "2020-05-05"
                ],

                [
                  "name" => "Куртка для сноуборда DC Mutiny Charocal",
                  "category" => "Одежда",
                  "cost" => 7500,
                  "picture" => "img/lot-5.jpg",
                  "endDate" => "2020-05-06"
                ],

                [
                  "name" => "Маска Oakley Canopy",
                  "category" => "Разное",
                  "cost" => 5400,
                  "picture" => "img/lot-6.jpg",
                  "endDate" => "2020-05-10"
                ]

              ];


$content = include_template('main.php', ['categories' => $categories, 'items' => $items]);
$layout = include_template('layout.php', ['user_name' => $user_name, 'title' => 'Главная', 'content' => $content, 'items' => $items]);

print($layout);

?>

            