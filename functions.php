<?php
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

?>