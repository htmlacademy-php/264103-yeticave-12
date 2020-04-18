INSERT INTO categories (code, name)
VALUES
("boards", "Доски и лыжи"),
("attachment", "Крепления"),
("boots", "Ботинки"),
("clothing", "Одежда"),
("tools", "Инструменты"),
("other", "Разное");

INSERT INTO users (email, name, registration_dt, password, users_info)
VALUES
("uni@mail.ru", "Алёна", NOW(), "supersecret",  "мой телефон: 8-900-222-44-22"),
("taxiuni@gmail.com", "Костя", NOW(), "supersecret", "мой телефон: 8-915-222-44-22"),
("odil@mail.ru", "Антон", NOW(), "supersecret", "мой телефон: 8-900-222-44-22"),
("bukh@gmail.com", "Максим", NOW(), "supersecret", "мой телефон: 8-900-222-44-22");

INSERT INTO lots (dt_add, name, st_coast, end_date, category_id, link, author_id, winner_id, description, step_bids)
VALUES 
(NOW() - INTERVAL 5 DAY, "2014 Rossignol District Snowboard", "10999", "2020-05-07", 1, "img/lot-1.jpg", 1, 6, 'Легкая, быстрая, стабильная, отлично выстреливающая вверх', 100),
(NOW() - INTERVAL 6 DAY, "DC Ply Mens 2016/2017 Snowboard", "15999", "2020-05-12", 1, "img/lot-2.jpg", 2, 5, 'Максимально заряженная на быстрое и агрессивное катания', 200),
(NOW() - INTERVAL 7 DAY, "Крепления Union Contact Pro 2015 года размер L/XL", "8000", "2020-05-11", 2, "img/lot-3.jpg", 3, 4, 'Отличная модель от известного брэнда, который специализируется на креплениях', 300),
(NOW() - INTERVAL 4 DAY, "Ботинки для сноуборда DC Mutiny Charocal", "10999", "2020-05-05", 3, "img/lot-4.jpg", 4, 3, 'Удобный, технологичный и стильный вариант', 400),
(NOW() - INTERVAL 3 DAY, "Куртка для сноуборда DC Mutiny Charocal", "7500", "2020-05-06", 4, "img/lot-5.jpg", 5, 2, 'Стильный силуэт подчеркнет Ваш городской образ.', 500),
(NOW() - INTERVAL 2 DAY, "Маска Oakley Canopy", "5400", "2020-05-10", 6, "img/lot-6.jpg", 6, 1, 'Такая маска идеально подойдет опытным райдерам', 600);

INSERT INTO bids (dt_add, sum, user_id, lot_id)
VALUES 
(NOW() - INTERVAL 1 DAY, "5600", 2, 6),
(NOW() - INTERVAL 2 DAY, "12050", 1, 1),
(NOW() - INTERVAL 4 HOUR, "11700", 4, 4),
(NOW() - INTERVAL 3 DAY, "16400", 3, 1);

SELECT name FROM categories;

SELECT lot.name, lot.st_coast, lot.link, category.name as category_name, bid.sum as current_price 
FROM lots as lot
INNER JOIN categories as category ON category.id = lot.category_id
INNER JOIN bids as bid ON bid.lot_id = lot.id
WHERE lot.end_date > NOW() ORDER BY lot.dt_add;

SELECT lot.name, lot.st_coast, category.name as categories_name FROM lots as lot
INNER JOIN categories as category ON category.id = lot.category_id
WHERE lot.id = 2;

UPDATE lots SET name = "2020 Маска Oakley Canopy" WHERE id = 6;

SELECT * FROM bids WHERE lot_id = 1 ORDER BY dt_add ASC;