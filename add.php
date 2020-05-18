<?php
date_default_timezone_set('Asia/Tashken');
require_once('mysql_connect.php');
require_once('helpers.php');
require_once('functions.php');
if ($is_auth === 0) {
    header("Location: index.php");
}

$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
$result_categories  = mysqli_query($link, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$require_fields = [`lot-name`, `catregory`, `message`, `lot-rate`, `lot-step`, `lot-date`];
	$errors = [];

	function validate_field_text($field_text, $max = NULL)
    {
        if($max && mb_strlen($field_text) > $max) {
            return "Значение должно быть не более $max символов";
        }
        return check_field($field_text);
    }

    function validate_field_price($field_price)
    {
        if(!is_numeric($field_price)) {
            return "Можно вводить только число";
        }
        $price = number_format($field_price, 2, '.', ',');
        if ($price <= 0) {
            return "Введите число больше нуля";
        }
        return check_field($field_price);
    }
    
    function validate_field_category($field_category)
    {
        return check_field($field_category);
    }

    function validate_field_date($field_date)
    {
        $format_to_check = 'Y-m-d';
        $dateTimeObj = date_create_from_format($format_to_check, $field_date);
        if ($dateTimeObj) {
            $diff_in_hours = floor((strtotime($field_date) - strtotime("now")) / 3600);
            if ($diff_in_hours <= 24) {
                return "Дата заверешния торгов, не может быть меньше 24 часов или отрицательной";
            }
        }
        return check_field($field_date);
    }


    $rules = [
        'lot-name' => function() {
            return validate_field_text($_POST['lot-name'],  255);
        },
        'message' => function() {
            return validate_field_text($_POST['message']);
        },
        'lot-rate' => function() {
            return validate_field_price($_POST['lot-rate']);
        },
        'category' => function() {
            return validate_field_category($_POST['category']);
        },
        'lot-step' => function() {
            return validate_field_price($_POST['lot-step']);
        },
        'lot-date' => function() {
            return validate_field_date($_POST['lot-date']);
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);
    if (empty($_FILES['lot-img']['name'])) {
        $errors['lot-img'] = 'Файл обязателен для загрузки';
    } else {
        $id_image = uniqid();
        $file_name = $id_image . $_FILES['lot-img']['name'];
        $type_file = mime_content_type($_FILES['lot-img']['tmp_name']);
        if($type_file !== "image/jpeg" && $type_file !== "image/png") {
            $errors['lot-img'] = 'Поддерживается загрузка только png, jpg, jpeg ' . $type_file;
        } else {
            move_uploaded_file($_FILES['lot-img']['tmp_name'], PATH_UPLOADS_IMAGE . $file_name);
            if($_FILES['lot-img']['error'] !== UPLOAD_ERR_OK) {
                return "Ошибка при загрузке файла - код ошибки: " . $_FILES['lot-img']['error'];
            }
        }

}

if(!count($errors)) {
        $file_url = PATH_UPLOADS_IMAGE . $id_image . $_FILES['lot-img']['name'];
        $query_insert_database_lot = "INSERT INTO `lots`
    (`dt_add`, `name`, `description`, `link`, `st_coast`, `end_date`, `step_bids`, `author_id`, `winner_id`, `category_id`)
VALUES
(NOW(), ?, ?, ?, ?, ?, ?, '3', '0', ?)";
        $stmt = mysqli_prepare($link, $query_insert_database_lot);
        mysqli_stmt_bind_param($stmt, 'ssssssi', $_POST['lot-name'],$_POST['message'], $file_url, $_POST['lot-rate'], $_POST['lot-date'], $_POST['lot-step'], $_POST['category']);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $last_id = mysqli_insert_id($link);
            
            header("Location: lot.php?id=" . $last_id);
        } else {
            echo 'Ошибка вставки ' . mysqli_error($link);
        }
    } else {
        $content = include_template("add-lot.php", ['categories' => $categories, 'errors' => $errors]);
    }
} else {
    $content = include_template("add-lot.php", ['categories' => $categories]);
};


print(include_template('layout.php', ['user_name' => 'Odiljon', 'title' => 'Добавление лота', 'content' => $content, 'categories' => $categories, 'css_calendar' => "<link href=\"css/flatpickr.min.css\" rel=\"stylesheet\">", 'is_auth' => $is_auth]));
?>