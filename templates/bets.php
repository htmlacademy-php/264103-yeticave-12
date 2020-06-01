<?php
require_once('helpers.php');
require_once('functions.php');
?>

<?php if (isset($error_bets)) : ?>
    <section class="rates container">
        <h2><?= $error_bets ?></h2>
    </section>
<? else : ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($bets as $bet) : ?>
                <?php
                if ((int)$bet["winner_id"] == $user_id) {
                    $user_winner = true;
                }
                if (get_dt_end($bet["end_date"])) {
                    $lot_end = true;
                }
                ?>
                <tr class="rates__item <?php if ($user_winner) : ?> rates__item--win <? elseif ($lot_end) : ?>rates__item--end<? endif; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= $bet["link"] ?>" width="54" height="40" alt="Сноуборд">
                        </div>
                        <?php if ($user_winner) : ?>
                            <div>
                                <h3 class="rates__title"><a href="lot.php?id=<?= $bet["id"] ?>"><?= $bet["name"] ?></a>
                                </h3>
                                <p><?= $bet["users_info"] ?></p>
                            </div>
                        <? else : ?>
                            <h3 class="rates__title"><a href="lot.php?id=<?= $bet["id"] ?>"><?= $bet["name"] ?></a></h3>
                        <? endif; ?>
                    </td>
                    <td class="rates__category">
                        <?= $bet["category"] ?>
                    </td>
                    <td class="rates__timer">
                        <?php if (!$user_winner && $lot_end) : ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <? elseif (!$user_winner) : ?>
                            <?php list($hours, $minutes) = get_dt_range($bet["end_date"]); ?>
                            <div class="timer <?php if ($hours < 1) : ?>timer--finishing<?php endif; ?>">
                                <?= $hours . " : " . $minutes; ?>
                            </div>
                        <? else: ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <? endif; ?>
                    </td>
                    <td class="rates__price">
                        <?= decorate_cost($bet["price"]); ?>
                    </td>
                    <?php list($hours, $minutes) = get_dt_difference($bet["dt_add"]); ?>

                    <td class="rates__time">
                        <?php if ($hours === 0) : ?>
                        <?= $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты',
                            'минут') . " назад" ?></td>
                <? else : ?>
                    <?= $hours . " " . get_noun_plural_form($hours, 'часа', 'часа',
                    'часов') . " " . $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты',
                    'минут') . " назад" ?></td>
                <? endif; ?>
                    </td>
                </tr>
            <? endforeach; ?>
        </table>

        <?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, '', 'my-bets.php?'); ?>
    </section>

<? endif; ?>