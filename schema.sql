-- Создали БД
CREATE DATABASE scheme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `mysql`;

-- Создание таблицы с пользователями
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
	registration_dt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email CHAR(255) NOT NULL UNIQUE,
	name CHAR(255) NOT NULL,
	password CHAR(64) NOT NULL,
    users_info TEXT NOT NULL
);

-- Создание таблицы с категориями
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(255) NOT NULL UNIQUE,
    code CHAR(255) NOT NULL
);

-- Создание таблицы с лотами
CREATE TABLE lots (
	id INT AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	name CHAR(255) NOT NULL,
	description TEXT,
	link CHAR(255) NOT NULL,
	st_coast DECIMAL(16,2) UNSIGNED NOT NULL,
	end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	step_bids DECIMAL(16,2) UNSIGNED NOT NULL,
	author_id INT NOT NULL,
	winner_id INT NOT NULL,
	category_id INT NOT NULL
);

-- Создал составной индекс для поиска по автору и категории
CREATE INDEX user_category ON lots(author_id, category_id);
-- Добавил индекс для полнотекстового поиска
CREATE FULLTEXT INDEX search_lot ON `lots`(name, description);

-- Создание таблицы со ставками
CREATE TABLE bids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    price DECIMAL(16,2) UNSIGNED NOT NULL,
    user_id INT NOT NULL,
    lot_id INT NOT NULL 
);

-- Создал составной индекс для поиска по автору и сумме
CREATE INDEX user_price ON bids(user_id, price);

CREATE INDEX in_cat ON categories(code, name);
CREATE INDEX in_lots ON lots(name, dt_add, category_id);
CREATE INDEX in_bids ON bids(dt_add, user_id, sum);
CREATE INDEX in_users ON users(email, id, name);
