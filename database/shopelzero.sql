-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 22, 2021 at 05:30 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopelzero`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Ordaring` int(11) DEFAULT NULL,
  `Parent` int(11) NOT NULL,
  `Visibilty` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordaring`, `Parent`, `Visibilty`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Hand Made', 'Hand Made Items', 1, 0, 0, 0, 0),
(2, 'Computers', 'Computers Items', 2, 0, 0, 0, 0),
(3, 'Cell Phones', 'Cell Phones Items', 3, 0, 0, 0, 0),
(4, 'Clothing', 'Clothing Items', 4, 0, 0, 0, 0),
(5, 'Tools', 'Home Tools', 5, 0, 0, 0, 0),
(6, 'Nokia', 'Old Phone', 3, 3, 0, 0, 0),
(7, 'Mac Book', 'beautiful Labtop', 5, 2, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `C_ID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `Comment_Date` date NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`C_ID`, `Comment`, `Status`, `Comment_Date`, `Item_ID`, `User_ID`) VALUES
(1, 'hello', 1, '2021-12-15', 4, 13);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `Tags`) VALUES
(1, 'Speaker', 'Very Good Speaker', '10', '2021-12-10', 'China', '', '1', 0, 1, 2, 13, ''),
(2, 'IPhone Xs', 'Good Phone', '600', '2021-12-10', 'USA', '', '1', 0, 1, 3, 14, ''),
(3, 'IPhone 13', 'New Phone', '1000', '2021-12-10', 'USA', '', '1', 0, 1, 3, 13, ''),
(4, 'Samsung', 'Good Arrange elements easily with the edge positioning utilities.Phone', '900', '2021-12-10', 'China', '', '1', 0, 1, 3, 13, ''),
(5, 'Football', 'Playstation Five Games', '500', '2021-12-14', 'USA', '', '4', 0, 1, 2, 13, ''),
(7, 'IPhone 7', 'this&#39;s beautiful Phone', '100', '2021-12-15', 'China', '', '3', 0, 0, 3, 13, ''),
(8, 'GTAA', 'this&#39;s beautiful game', '10', '2021-12-16', 'Prazil', '', '4', 0, 1, 2, 13, ''),
(9, 'Mouse', 'Mouse description', '20 $', '2021-12-20', 'USA', '', '1', 0, 1, 2, 15, 'mouse , coumputer , labtop'),
(10, 'Subway', 'Game mobile', '10', '2021-12-20', 'China', '', '4', 0, 1, 2, 13, 'mobile , games'),
(11, 'Keyboard', 'accessories coumputer', '10', '2021-12-20', 'USA', '', '1', 0, 1, 2, 13, 'Labtop , Coumputer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0,
  `TrustStatus` int(11) NOT NULL DEFAULT 0,
  `RegStatus` int(11) NOT NULL DEFAULT 0,
  `Date` date NOT NULL,
  `Images` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `Fullname`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `Images`) VALUES
(1, 'bodi', '202cb962ac59075b964b07152d234b70', 'bodi@gmail.com', 'bodi Mohamed', 1, 0, 1, '0000-00-00', ''),
(13, 'Abdelrahman', 'e10adc3949ba59abbe56e057f20f883e', 'Abdelrahman@gmail.com', 'Abdelrahman', 0, 0, 1, '2021-12-09', ''),
(14, 'Ahmed', 'e10adc3949ba59abbe56e057f20f883e', 'Ahmed@gmail.com', 'Ahmed Mohamed', 0, 0, 1, '2021-12-09', ''),
(15, 'mohamed', 'e10adc3949ba59abbe56e057f20f883e', 'mohamed@mo.com', '', 0, 0, 0, '2021-12-12', ''),
(17, 'gamal', 'e10adc3949ba59abbe56e057f20f883e', 'gamal@gmail.com', 'gamal Mohamed', 0, 0, 1, '2021-12-21', '54382_my.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`C_ID`),
  ADD KEY `Item_ID` (`Item_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `Member_ID` (`Member_ID`),
  ADD KEY `Cat_ID` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
