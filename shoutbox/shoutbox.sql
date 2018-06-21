SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbName`
--
DROP DATABASE IF EXISTS `dbName`;
CREATE SCHEMA IF NOT EXISTS `dbName` DEFAULT CHARACTER SET utf8;
USE `dbName`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_ID` varchar(255) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Permission` int NOT NULL,
  `Status` int NOT NULL DEFAULT 2,
  PRIMARY KEY (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Standard data for table `users`
--

INSERT INTO `users` (`user_ID`, `Username`, `Password`, `Email`, `Permission`) VALUES
('B2022F48-ED35-43F9-BB61-B97A3019E004', 'creator', '$2y$10$k.eE6IORn8ODNfpktvzpLu5fnSFV5I5vQler1O.hxkL7bQK2Q5Qoq', 'test@hotmail.com', '3');
-- Username: creator
-- Password: creator

-- --------------------------------------------------------

--
-- Table structure for table `email_confirm`
--

CREATE TABLE `email_confirm` (
  `user_ID` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `randNmb` int(8) NOT NULL,
  `insertDate` varchar(20) NOT NULL,
  PRIMARY KEY (`user_ID`),
  FOREIGN KEY (`user_ID`) REFERENCES `users`(`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_confirm`
--

CREATE TABLE `password_confirm` (
  `user_ID` varchar(255) NOT NULL,
  `randNmb` int(8) NOT NULL,
  `insertDate` varchar(20) NOT NULL,
  PRIMARY KEY (`user_ID`),
  FOREIGN KEY (`user_ID`) REFERENCES `users`(`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shoutbox`
--

CREATE TABLE `shoutbox` (
  `post_ID` varchar(255) NOT NULL,
  `user_ID` varchar(255) NOT NULL,
  `Text` varchar(85) NOT NULL,
  `createdDate` varchar(50) NOT NULL,
  `Edited` int NOT NULL DEFAULT 1,
  `Status` int NOT NULL DEFAULT 1,
  `deletedDate` varchar(50),
  PRIMARY KEY (`post_ID`),
  FOREIGN KEY (`user_ID`) REFERENCES `users`(`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;