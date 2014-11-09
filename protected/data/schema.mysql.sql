CREATE DATABASE uicdss COLLATE utf8_general_ci;
use uicdss;
CREATE USER 'uicdss'@'localhost' IDENTIFIED BY  'uicdss';
GRANT ALL PRIVILEGES ON  `uicdss` . * TO  'uicdss'@'localhost';