-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 07:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masisso_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`user_id`, `name`, `email`, `phone`, `address`, `password`, `points`) VALUES
(1111, 'Joey', 'joeybaobei@gmail.com', '+60 12-345 6789', '123 Jalan Ampang, Kuala Lumpur', '1223334444', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `preferences` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `name`, `price`, `description`, `category`, `image_url`, `preferences`, `is_available`) VALUES
(1, 'Masisso Signature Laksa', 14.90, 'Authentic thin rice vermicelli with chicken, shrimp, and omelette.', 'A La Carte', 'laksa.jpg', '{\"Laksa\": [\"No Coriander\", \"No Shrimp Sauce\", \"Extra Sambal\"]}', 1),
(2, 'Laksa Sarawak Super Pedas', 16.90, 'Extra spicy authentic Sarawak Laksa.', 'A La Carte', 'laksa.jpg', '{\"Laksa\": [\"No Coriander\", \"No Shrimp Sauce\", \"Extra Sambal\"]}', 0),
(3, 'Laksa + Teh C Beng Special', 19.40, 'Signature Laksa paired with 3-layer tea.', 'Combo', 'combo_A.jpg', '{\"Laksa\": [\"No Coriander\", \"No Shrimp Sauce\", \"Extra Sambal\"], \"Teh C Beng Special\": [\"Less Ice\", \"Less Sugar\"]}', 1),
(4, 'Laksa + Fruit Rojak', 17.90, 'Signature Laksa paired with fresh fruit rojak.', 'Combo', 'combo_B.jpg', '{\"Laksa\": [\"No Coriander\", \"No Shrimp Sauce\", \"Extra Sambal\"], \"Fruit Rojak\": [\"No Spicy\", \"More Spicy\"]}', 1),
(5, 'Teh C Beng Special', 4.50, 'Authentic Sarawak 3-layer tea with palm sugar.', 'Drinks', 'TehCBengSpecial.jpg', '{\"Teh C Beng Special\": [\"Less Ice\", \"Less Sugar\"]}', 1),
(6, 'Fruit Rojak', 6.90, 'Fresh cut fruits with crushed peanuts and shrimp paste.', 'Sides', 'rojak.jpg', '{\"Fruit Rojak\": [\"No Spicy\", \"More Spicy\"]}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `discount_type` varchar(20) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_spend` decimal(10,2) DEFAULT 0.00,
  `valid_until` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`offer_id`, `code`, `title`, `description`, `discount_type`, `discount_value`, `min_spend`, `valid_until`) VALUES
(1, 'MINUS5', '🎫 RM 5 OFF', 'Min. spend RM 50. Valid for all items.', 'fixed', 5.00, 50.00, '2027-01-01'),
(2, '15OFF', '🎫 15% OFF', 'Valid for all items. No minimum spend.', 'percentage', 15.00, 0.00, '2027-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_type` varchar(20) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_status` varchar(20) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `reward_name` varchar(100) NOT NULL,
  `points_required` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `reward_name`, `points_required`, `image_url`) VALUES
(1, 'Teh C Beng Special', 500, 'TehCBengSpecial.jpg'),
(2, 'Fruit Rojak', 800, 'rojak.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1112;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customer` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
