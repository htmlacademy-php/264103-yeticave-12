<?php
function decorate_cost($number = 0)
{
    $price = ceil($number);
    $format_price = number_format($price, 0, ",", " ");
    return $format_price .  " ₽";
};

function get_dt_range($value_date) {
	$time_difference = strtotime($value_date) - time();
	$time_hours = floor($time_difference / 3600);
	$time_minutes = floor(($time_difference % 3600) / 60);
	return [$time_hours, $time_minutes];
};


function get_max_price_bids($prices, $st_coast)
{
	if (!isset($prices[0]['price'])) {
        return $st_coast;
    }
    $max_value = $prices[0]['price'];
    foreach ($prices as $price) {
        if ($max_value < $price['price']) {
            $max_value = $price['price'];
        }
    }
    return $max_value;
};

function check_field($field)
{
    if (empty($field)) {
        return "Это поле обязательно к заполнению";
    }
};

function get_field_value($field_name)
{
    return $_POST[$field_name] ?? "";
}


function validation_form($data, $rules)
{
    $errors = [];
    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    $errors = array_filter($errors);
    return $errors;
};


// From add.php
 function validate_field_text($field_text, $max = NULL)
    {
        if($max && mb_strlen($field_text) > $max) {
            return "Значение должно быть не более $max символов";
        }
        return check_field($field_text);
    }

    function validate_field_price($field_price)
    {
        $price = decorate_cost($field_price, 2, ".", ",");
        if ($price <= 0) {
            return "Введите число больше нуля";
        }
        
        if(!is_numeric($field_price)) {
            return "Можно вводить только число";
        }
        
        return check_field($field_price);
    }

    function validate_field_category($field_category)
    {
        return check_field($field_category);
    }

    function validate_field_date($field_date)
    {
        $format_to_check = "Y-m-d";
        $dateTimeObj = date_create_from_format($format_to_check, $field_date);
        if ($dateTimeObj) {
            $diff_in_hours = floor((strtotime($field_date) - strtotime("now")) / 3600);
            if ($diff_in_hours <= 24) {
                return "Дата заверешния торгов, не может быть меньше 24 часов или отрицательной";
            }
        }
        return check_field($field_date);
    }

// From login.php
function get_data_user($link, $email_field)
    {
        $email = mysqli_real_escape_string($link, $email_field);
        $sql_query_data = "SELECT * FROM `users` WHERE `email` = ? ";
        $stmt = db_get_prepare_stmt($link, $sql_query_data, [$email]);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    };

    function validate_email_field($email_field, $link)
    {
        if (empty($email_field)) {
            return "Это поле обязательно к заполнению";
        }
        $result = get_data_user($link, $email_field);
        if (mysqli_num_rows($result) === 0) {
            return "Нет пользователя с таким адресом";
        }
    }

    function validate_password_field($password_field, $email_field, $link)
    {
        if(empty($password_field)) {
            return "Это поле обязательно к заполнению";
        }
        $result = get_data_user($link, $email_field);
        $user_data = $result ? mysqli_fetch_assoc($result) : null;
        if (!password_verify($password_field, $user_data["password"])) {
            return "Указан неверный пароль";
        }
    }

// From registation.php
function validate_field_email($email_field, $link)
    {
        if(filter_var($email_field, FILTER_VALIDATE_EMAIL)) {
            $email = mysqli_real_escape_string($link, $email_field);
            $sql_query_empty_user = "SELECT `id` FROM users WHERE `email` = ?";
            $stmt = db_get_prepare_stmt($link, $sql_query_empty_user, [$email_field]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                return "Пользователь с этим email уже зарегистрирован";
            }
        }
        return check_field($email_field);
    };