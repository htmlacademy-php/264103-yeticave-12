<section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php
            $index = 0;
            $num = count($categories);
            while ($index < $num): ?>
                <li class="promo__item promo__item--boards">
                    <a class="promo__link" href="pages/all-lots.html"><?php echo(htmlspecialchars($categories[$index])); ?></a>
                </li>
                <?php $index++; ?>
            <?php endwhile; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$lot["link"]; ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?php echo(htmlspecialchars($lot["category_id"])); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?php echo(htmlspecialchars($lot["name"])); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?php $price = $lot["st_coast"]; decorate_cost($price); ?></span>
                        </div>
                        <?php list($hours, $minutes) = get_dt_range($lots["end_date"]);?> 
                        <div class="lot__timer timer <?php if ($hours < 1) : ?> timer--finishing<?php endif;?>">
                            <?php print($hours . ":" . $minutes); ?>             
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>