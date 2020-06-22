<?php
require_once "init.php";
require_once "helpers.php";
require_once "validate_functions.php";
require_once "vendor/autoload.php";

if (!isset($_SESSION["user"])) {
    http_response_code(403);
    $content = include_template("error.php", [
        "categories" => $categories,
        "code_error" => "403",
        "text_error" => "Страница доступна только зарегистрированным пользователям"
    ]);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = []; 
    $lot_name = post_value("lot-name");
    $message = post_value("message");
    $lot_rate = post_value("lot-rate");
    $category = post_value("category");
    $lot_step = post_value("lot-step");
    $lot_date = post_value("lot-date");
    $lot_img = get_file("lot-img", "");
    $author_id = get_value_from_user_session("id");
    $id_image = uniqid();

    $errors = validate(
        [
            "lot-name" => $lot_name,
            "message" => $message,
            "lot-rate" => $lot_rate,
            "category" => $category,
            "lot-step" => $lot_step,
            "lot-date" => $lot_date,
            "lot-img" => $lot_img,
        ],
        [
            "lot-name" => [
                not_empty("Введите название лота"),
                str_length_gt(256),
            ],
            "message" => [
                not_empty("Введите описание лота"),
            ],
            "lot-rate" => [
                not_empty("Цена должна быть больше 0"),
                it_is_number(),
                check_price_greater_than_zero(),
                str_length_gt(9),
            ],
            "category" => [
                not_empty("Укажите категорию лота"),
            ],
            "lot-step" => [
                not_empty("Цена должна быть больше 0"),
                it_is_number(),
                check_price_greater_than_zero(),
                str_length_gt(9),
            ],
            "lot-date" => [
                not_empty("Введите дату окончания дейтсвия лота"),
                checking_date_on_format_and_date_lot_end(),
            ],
            "lot-img" => [
                is_file_uploaded(),
                checking_add_image(),
                checking_type_image(),
            ]
        ]
    );

    if (!count($errors)) {
        move_file_to_folder($id_image, $lot_img, PATH_UPLOADS_IMAGE);
        resize_and_watermark_image_of_lot($id_image . $lot_img["name"]);
        $file_url = PATH_UPLOADS_IMAGE . $id_image . $lot_img["name"];
        $query_insert_database_lot = "INSERT INTO `lots` (
            `name`,
            `description`,
            `link`,
            `st_coast`,
            `end_date`,
            `step_bids`,
            `author_id`,
            `category_id`
        )
        VALUES(
            ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $query_insert_database_lot, [
            $lot_name,
            $message,
            $file_url,
            $lot_rate,
            $lot_date,
            $lot_step,
            $author_id,
            $category
        ]);
        
        $result = mysqli_stmt_execute($stmt);
 
        $content = "";
        
        if ($result) {
            $last_id = mysqli_insert_id($con);
            header("Location: lot.php?id=$last_id");
        }
        echo "Ошибка вставки " . mysqli_error($con);
    } else {
        $content = include_template("add-lot.php", [
            "categories" => $categories,
            "errors" => $errors,
            "id_category" => post_value("category", null),
        ]);
    }
} else {
    $content = include_template("add-lot.php", [
        "categories" => $categories,
        "id_category" => null,
    ]);
}

$layout_content = include_template("layout.php", [
    "content" => $content,
    "title_page" => "Добавление нового лота",
    "user_name" => get_value_from_user_session("name"),
    "categories" => $categories,
    "css_calendar" => "<link href=\"css/flatpickr.min.css\" rel=\"stylesheet\">"
]);

print($layout_content);