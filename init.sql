CREATE DATABASE IF NOT EXISTS test;
USE test;


CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO Users (name, password) VALUES 
('Meuritz', 'caramella1');
