<?php
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($link === false) {
  print("Ошибка подключения: " . mysqli_connect_error());
    exit();
};

mysqli_set_charset($link, "utf8");
