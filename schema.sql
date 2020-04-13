CREATE DATABASE scheme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    name CHAR(255) NOT NULL UNIQUE,
    code CHAR(255) NOT NULL
);

CREATE TABLE lots (
	id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
	dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
	name CHAR(255) NOT NULL,
	description TEXT,
	link CHAR(255) NOT NULL,
	st_coast INT,
	end_date DATETIME DEFAULT CURRENT_TIMESTAMP,
	step_bids INT,
	author_id INT NOT NULL,
	winner_id INT NOT NULL,
	category_id INT NOT NULL
);

CREATE TABLE bids (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
    sum INT,
    user_id INT NOT NULL,
    lot_id INT NOT NULL 
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
	registration_dt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email CHAR(255) NOT NULL UNIQUE,
	name CHAR(255) NOT NULL,
	password CHAR(64) NOT NULL,
    users_info TEXT NOT NULL
);


CREATE INDEX in_cat ON categories(code, name);
CREATE INDEX in_lots ON lots(name, dt_add, category_id);
CREATE INDEX in_bids ON bids(dt_add, user_id, sum);
CREATE INDEX in_users ON users(email, id, name);
