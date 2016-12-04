drop database cookbook;
CREATE DATABASE IF NOT EXISTS cookbook DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
create user if not exists 'cookbook'@'%' identified by 'cookbook';
grant all privileges on cookbook.* to 'cookbook'@'%' identified by 'cookbook';
create user if not exists 'cookbook'@'127.0.0.1' identified by 'cookbook';
grant all privileges on cookbook.* to 'cookbook'@'127.0.0.1' identified by 'cookbook';
flush privileges;
USE cookbook;

CREATE TABLE IF NOT EXISTS programuser
(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	login VARCHAR(30) NOT NULL,
	password VARCHAR(255) NOT NULL,
    admin int not null
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS category
 (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(32) NOT NULL,
    description text
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS recipe
 (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title varchar(128) not null,
	content text NOT NULL,
    user_id int unsigned not null,
    FOREIGN KEY (user_id) references programuser(id),
	recipe_category_id int unsigned not null,
	FOREIGN KEY (recipe_category_id) REFERENCES category(id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comment
(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content text NOT NULL,
    recipe_id int unsigned not null,
    FOREIGN KEY (recipe_id) REFERENCES recipe(id),
    user_id int unsigned not null,
    foreign key (user_id) references programuser(id)
)ENGINE=InnoDB;


insert into programuser values(null, 'root', 'root', 1);