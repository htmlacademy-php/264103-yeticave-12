<?php
$link = mysqli_connect("127.0.0.1", "root", "", "mysql");
if (!$link) {
  print("Ошибка подключения: " . mysqli_connect_error());
    exit();
};

mysqli_set_charset($link, "utf8");

?>