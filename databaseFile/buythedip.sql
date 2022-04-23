-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2022 at 03:16 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buythedip`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Category_ID` int(11) NOT NULL,
  `Category_Name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Category_ID`, `Category_Name`) VALUES
(1, 'Condiment'),
(2, 'Household &amp; Cleaning Supplies'),
(3, 'Chips and Snacks'),
(4, 'Drinks'),
(5, 'Personal Care Product'),
(6, 'International Foods'),
(10, 'new categeory');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Comment_ID` int(11) NOT NULL,
  `Post_ID` int(11) NOT NULL,
  `Comment_Time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Comment` varchar(1000) NOT NULL,
  `Member_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Comment_ID`, `Post_ID`, `Comment_Time`, `Comment`, `Member_ID`) VALUES
(3, 44, '2022-03-27 23:29:58', 'Nice Product!!!', 27),
(4, 44, '2022-03-27 23:32:17', 'gggg', 27),
(5, 44, '2022-03-27 23:32:45', 'hhhh', 27),
(8, 57, '2022-03-28 03:35:08', 'nice!!!', 33),
(13, 47, '2022-04-11 07:21:52', 'nice cookies by Beryl, edited by admin\r\n', 29),
(16, 47, '2022-04-11 07:22:16', 'adam: great !!! pls elaborate more (admin)', 28),
(17, 47, '2022-04-11 07:22:25', 'reviewed', 27),
(18, 62, '2022-04-12 23:08:34', 'Great!', 28),
(24, 65, '2022-04-13 09:16:31', 'Agreed!!!', 30),
(25, 49, '2022-04-13 15:43:38', 'Great post!', 30),
(26, 61, '2022-04-13 16:49:25', 'test captcha', 30),
(27, 67, '2022-04-13 19:05:18', 'test captcha', 27);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `Member_ID` int(11) NOT NULL,
  `Login_Name` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `Area` varchar(50) NOT NULL,
  `Bonus` int(11) NOT NULL,
  `Role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`Member_ID`, `Login_Name`, `Email`, `Password`, `Street`, `Area`, `Bonus`, `Role`) VALUES
(27, 'admin', 'admin@buythedip.com', '$2y$10$Nz/7SPGTCb2dDtqm9jjFf.m6dXA.DFu6wuksJ1RQavppqPjx3hNEi', '', '', 0, 'admin'),
(28, 'adam', 'adam@buythedip.com', '$2y$10$jH.UZi46XiT9dWwQ05SWduoaupLNQn7CgYnz.RDGps4TzJgv/Op6i', 'adam', 'adam', 0, 'member'),
(29, 'Beryl', 'beryl@buythedip.com', '$2y$10$Hc2J9c53RroeQCWkd2fRN.PgK9Ymw1KipfV69KlvgnSOwoXCA/3mS', 'portage avenue', 'downtown', 0, 'member'),
(30, 'Carol', 'carol@buythedip.com', '$2y$10$KDCMsyvWxcj8w.ZJTAm/EuS8pBK1IK/hpBP1y/NJ3HFzICBFLo8RG', 'carol street', 'carol area', 0, 'member'),
(32, 'zack', 'zack@buythedip.com', '$2y$10$6NWVUuBfmz5D1JT0.MKLWuVTX4V2XbzG8dLaxVP1hMzulFXtk./MG', 'River', 'osborne', 0, 'member'),
(33, 'bash', 'bash@buythedip.com', '$2y$10$K5LvBNX6MKXfw2DmeZtvA.D174fWLR9W6WsK7bCpA4qoWVuZGIj1G', 'bash', 'bash', 0, 'admin'),
(34, 'anna', 'anna@buythedip.com', '$2y$10$oSueA9wGUYaRmmzWlSaUWebpwf5up7k6drwhCNA5xUfy7VvIWH4ii', 'anna street', 'anna area', 0, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `Post_ID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `Product_ID` int(11) NOT NULL,
  `Price` decimal(8,2) NOT NULL,
  `Title` varchar(50) NOT NULL,
  `Content` varchar(500) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Store_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`Post_ID`, `Timestamp`, `Product_ID`, `Price`, `Title`, `Content`, `Member_ID`, `Store_ID`) VALUES
(44, '2022-03-27 17:59:29', 29, '19.97', 'Great Toilet paper on sale!!!', 'Great Toilet paper on sale!!!', 27, 3),
(46, '2022-03-27 18:06:12', 31, '3.27', 'Exclusive! Lay&#039;s Ketchup Potato Chips', 'Lay&#039;s Ketchup Potato Chips', 29, 3),
(47, '2022-03-27 18:08:14', 32, '2.97', 'Good Deal!!! Mr. Christie Snak Paks Mini Chips Aho', 'Mr. Christie Snak Paks Mini Chips Ahoy!', 29, 2),
(48, '2022-03-27 18:09:35', 33, '1.97', 'Hot Deal!!! Celebration Dark Chocolate 45% Cocoa B', 'Celebration Dark Chocolate 45% Cocoa Butter Cookies', 29, 3),
(49, '2022-03-27 18:11:37', 34, '2.47', 'Great! Haribo Goldbears Gummy Candy, No Artificial', 'Haribo Goldbears Gummy Candy, No Artificial Colours', 29, 3),
(50, '2022-03-27 18:29:05', 35, '10.99', 'Toilet Paper Sale', 'Purex Soft &amp; Thick Toilet Paper, Hypoallergenic and Septic Safe, 30 Double Rolls = 60 Single Rolls', 30, 1),
(51, '2022-03-27 18:30:31', 36, '8.99', 'onSale!!! Palmolive Orange Dish Liquid', 'Palmolive Orange Dish Liquid', 30, 3),
(53, '2022-03-27 18:33:02', 38, '2.37', 'Quick!!! Sunlight Standard Dishwashing Liquid, Lem', 'Sunlight Standard Dishwashing Liquid, Lemon Fresh Scent, 800 mL', 30, 2),
(54, '2022-03-27 18:33:50', 39, '1.27', 'Good price! Snack Pack&reg; Chocolate Pudding Cups', 'Snack Pack&reg; Chocolate Pudding Cups', 30, 1),
(55, '2022-03-27 19:47:29', 28, '3.99', 'Good deal', 'chocolate', 32, 1),
(56, '2022-03-27 19:54:09', 40, '1.67', 'admin-good chocolate bar', 'admin-Great Value Dipped Chocolate Chip Granola Bars\r\nGreat Value', 32, 3),
(57, '2022-03-28 03:34:48', 41, '1.02', 'Cool! Great Value Choco Bar Sale`', 'Yeah!!!', 33, 3),
(58, '2022-03-28 13:13:19', 42, '1.77', 'great strawberry bar', 'cool snack', 34, 3),
(60, '2022-04-06 18:14:10', 43, '1.00', 'nice potato chips BBQ', 'Great Value Barbecue Flavoured Potato Chips\r\nGreat Value\r\n200 g\r\n\r\n(247)\r\nExclusive\r\n', 27, 3),
(61, '2022-04-06 19:03:08', 43, '1.00', 'adam post! great BBQ Chips', 'very tasty', 28, 3),
(62, '2022-04-09 06:09:55', 43, '1.00', 'great', 'BBQ chips', 27, 3),
(63, '2022-04-13 00:34:57', 30, '9.99', 'testCaptcha', 'testCaptcha', 28, 1),
(65, '2022-04-13 09:12:57', 29, '11.22', 'Nice Product (Carol)', 'very good price', 30, 2),
(67, '2022-04-13 17:11:16', 46, '1.00', 'Great Dental Floss On Sale', 'Great Dental Floss On Sale!!!', 27, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_ID` int(11) NOT NULL,
  `Product_Desc` varchar(100) NOT NULL,
  `Category_ID` int(11) NOT NULL,
  `Images` longblob NOT NULL,
  `Img_Link` varchar(500) NOT NULL,
  `Img_Link400` varchar(500) NOT NULL,
  `Img_Link50` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_ID`, `Product_Desc`, `Category_ID`, `Images`, `Img_Link`, `Img_Link400`, `Img_Link50`) VALUES
(28, 'Great', 1, '', '', '', ''),
(29, 'Royale', 1, '', 'uploads\\Royale_Toilet_Paper_30Rolls.png', 'uploads\\Royale_Toilet_Paper_30Rolls_medium.png', 'uploads\\Royale_Toilet_Paper_30Rolls_thumbnail.png'),
(30, 'Cashmere Soft &amp; Thick Toilet Paper', 2, '', 'uploads\\Cashmere_Toilet_Paper_30Rolls.png', 'uploads\\Cashmere_Toilet_Paper_30Rolls_medium.png', 'uploads\\Cashmere_Toilet_Paper_30Rolls_thumbnail.png'),
(31, 'Lay&#039;s Ketchup Potato Chips', 3, '', 'uploads\\Lays_Ketchup.png', 'uploads\\Lays_Ketchup_medium.png', 'uploads\\Lays_Ketchup_thumbnail.png'),
(32, 'Mr. Christie Snak Paks Mini Chips Ahoy!', 3, '', 'uploads\\Mr_Christie_Mini_Chips.png', 'uploads\\Mr_Christie_Mini_Chips_medium.png', 'uploads\\Mr_Christie_Mini_Chips_thumbnail.png'),
(33, 'Celebration Dark Chocolate 45% Cocoa Butter Cookies', 3, '', 'uploads\\Celebration_Dark_Chocolate_Cookies.png', 'uploads\\Celebration_Dark_Chocolate_Cookies_medium.png', 'uploads\\Celebration_Dark_Chocolate_Cookies_thumbnail.png'),
(34, 'Haribo Goldbears Gummy Candy, No Artificial Colours', 3, '', 'uploads\\Haribo_Goldbears_Gummy.png', 'uploads\\Haribo_Goldbears_Gummy_medium.png', 'uploads\\Haribo_Goldbears_Gummy_thumbnail.png'),
(35, 'Purex Soft &amp; Thick Toilet Paper, Hypoallergenic and Septic Safe, 30 Double Rolls = 60 Single Rol', 2, '', 'uploads\\Purex_Soft_Toilet_Paper.png', 'uploads\\Purex_Soft_Toilet_Paper_medium.png', 'uploads\\Purex_Soft_Toilet_Paper_thumbnail.png'),
(36, 'Palmolive Orange Dish Liquid', 2, '', 'uploads\\Palmolive_Orange_Dish_Liquid.png', 'uploads\\Palmolive_Orange_Dish_Liquid_medium.png', 'uploads\\Palmolive_Orange_Dish_Liquid_thumbnail.png'),
(37, 'Dawn Ultra Dishwashing Liquid Dish Soap, Original Scent', 2, '', 'uploads\\Dawn _Ultra_Dishwashing.png', 'uploads\\Dawn _Ultra_Dishwashing_medium.png', 'uploads\\Dawn _Ultra_Dishwashing_thumbnail.png'),
(38, 'Sunlight Standard Dishwashing Liquid, Lemon Fresh Scent, 800 mL', 2, '', 'uploads\\Sunlight_Standard_Dishwashing.png', 'uploads\\Sunlight_Standard_Dishwashing_medium.png', 'uploads\\Sunlight_Standard_Dishwashing_thumbnail.png'),
(39, 'Snack Pack&reg; Chocolate Pudding Cups', 3, '', 'uploads\\Snack_Pack_Chocolate.png', 'uploads\\Snack_Pack_Chocolate_medium.png', 'uploads\\Snack_Pack_Chocolate_thumbnail.png'),
(40, 'Great Value Dipped Chocolate Chip Granola Bars', 3, '', 'uploads\\Great_Value_Chocolate_Bars.png', 'uploads\\Great_Value_Chocolate_Bars_medium.png', 'uploads\\Great_Value_Chocolate_Bars_thumbnail.png'),
(41, 'Great Value Milk Chocolate Bar', 3, '', 'uploads\\Great_Value_Chocolate_bar.png', 'uploads\\Great_Value_Chocolate_bar_medium.png', 'uploads\\Great_Value_Chocolate_bar_thumbnail.png'),
(42, 'Great Value Strawberry Bar', 3, '', 'uploads\\Great_Value_Strawberry_Bars.png', 'uploads\\Great_Value_Strawberry_Bars_medium.png', 'uploads\\Great_Value_Strawberry_Bars_thumbnail.png'),
(43, 'Great Value Barbecue Flavoured Potato Chips', 3, '', 'uploads/Great_Value_BBQ_Chips.png', 'uploads/Great_Value_BBQ_Chips_medium.png', 'uploads/Great_Value_BBQ_Chips_thumbnail.png'),
(44, 'Great Value Gummy Treats Candy', 3, '', 'uploads/Great_Value_Gummy_Treats_Candy.png', 'uploads/Great_Value_Gummy_Treats_Candy_medium.png', 'uploads/Great_Value_Gummy_Treats_Candy_thumbnail.png'),
(45, 'Mini Cadbury Creme Egg', 3, '', 'uploads/Mini_Cadbury_Creme_Egg.png', 'uploads/Mini_Cadbury_Creme_Egg_medium.png', 'uploads/Mini_Cadbury_Creme_Egg_thumbnail.png'),
(46, 'Oral-B Dental Floss mint', 5, '', 'uploads/Oral-B_Dental_Floss.png', 'uploads/Oral-B_Dental_Floss_medium.png', 'uploads/Oral-B_Dental_Floss_thumbnail.png'),
(47, 'Coca-Cola 355mL Cans, 12 Pack', 4, '', 'uploads/Coca-Cola_355mL_Cans.png', 'uploads/Coca-Cola_355mL_Cans_medium.png', 'uploads/Coca-Cola_355mL_Cans_thumbnail.png');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `Store_ID` int(11) NOT NULL,
  `Store_Name` varchar(50) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Area` varchar(50) NOT NULL,
  `Postal_Code` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`Store_ID`, `Store_Name`, `Address`, `Area`, `Postal_Code`) VALUES
(1, 'Safeway', '499 River Ave, Winnipeg, MB R3L 0C9', 'River Height', 'R3L0C9'),
(2, 'No Frills', '161 Goulet St, Winnipeg, MB R2H 0R6', 'St. Boniface', 'R2H0R6'),
(3, 'Walmart Supercentre', '1000 Taylor Ave, Winnipeg, MB R3M 3A3', 'Grant Park', 'R3M3A3'),
(4, 'Costco Wholesale', '2365 McGillivray Blvd, Winnipeg, MB R3Y 0A1', 'Tuxedo Industrials', 'R3Y0A1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_ID`),
  ADD KEY `Member_ID` (`Member_ID`),
  ADD KEY `Post_ID` (`Post_ID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`Member_ID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`Post_ID`),
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `Member_ID` (`Member_ID`),
  ADD KEY `Store_ID` (`Store_ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_ID`),
  ADD KEY `Category_ID` (`Category_ID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`Store_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `Category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Comment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `Member_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `Post_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `Store_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`Member_ID`) REFERENCES `members` (`Member_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`Post_ID`) REFERENCES `posts` (`Post_ID`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`Member_ID`) REFERENCES `members` (`Member_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`Store_ID`) REFERENCES `stores` (`Store_ID`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `categories` (`Category_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
