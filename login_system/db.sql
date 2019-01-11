SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `login_system`;
CREATE SCHEMA IF NOT EXISTS `login_system` DEFAULT CHARACTER SET utf8;
USE `login_system`;

CREATE TABLE `users` (
  `userId` varchar(255) NOT NULL PRIMARY KEY,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Permission` int NOT NULL DEFAULT 1,
  `Status` int NOT NULL DEFAULT 2
);

INSERT INTO `users` (`userId`, `Username`, `Password`, `Email`, `Permission`) VALUES
('5BC36F7F9E168', 'creator', '$2y$10$k.eE6IORn8ODNfpktvzpLu5fnSFV5I5vQler1O.hxkL7bQK2Q5Qoq', 'test@hotmail.com', '3');
-- Username: creator
-- Password: creator

CREATE TABLE `email_confirm` (
  `userId` varchar(255) NOT NULL PRIMARY KEY,
  `Email` varchar(100) NOT NULL,
  `randNmb` int(8) NOT NULL,
  `insertDate` varchar(20) NOT NULL,
  FOREIGN KEY (`userId`) REFERENCES `users`(`userId`)
);

CREATE TABLE `password_confirm` (
  `userId` varchar(255) NOT NULL PRIMARY KEY,
  `randNmb` int(8) NOT NULL,
  `insertDate` varchar(20) NOT NULL,
  FOREIGN KEY (`userId`) REFERENCES `users`(`userId`)
);
