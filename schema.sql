CREATE DATABASE scheme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    name CHAR(255) NOT NULL,
    code INT
);

CREATE TABLE lots (
	id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
	dt_add DATETIME,
	name CHAR(255),
	description TEXT,
	path CHAR(255),
	st_coast INT,
	end_date DATE,
	step_bids INT
);

CREATE TABLE bids (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    dt_add DATETIME,
    sum INT
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
	email CHAR(255),
	name CHAR(255),
	password CHAR(64),
    contact TEXT
);


CREATE INDEX in_cat ON categories(code);
CREATE INDEX in_lots ON lots(name);
CREATE INDEX in_bids ON bids(dt_add);
CREATE INDEX in_users ON users(email);