-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 05:58 PM
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
  `username` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`user_id`, `name`, `username`, `phone`, `address`, `email`, `password`, `points`) VALUES
(1111, 'Joey', 'joeyzihui', '+60 12-345 6789', '123 Jalan Ampang, Kuala Lumpur', 'joeybaobei@gmail.com', MD5('1223334444'), 1192);

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
  `delivery_fee` decimal(10,2) DEFAULT 0.00,
  `items` text DEFAULT NULL,
  `order_status` varchar(20) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_type`, `total_price`, `delivery_fee`, `items`, `order_status`, `order_date`) VALUES
(1, 1111, 'Dine-In', 19.40, 0.00, '[{"name":"Laksa + Teh C Beng Special","image":"combo_A.jpg","basePrice":19.4,"comboName":"Combo","comboPrice":0,"preferences":[],"quantity":1,"totalPrice":19.4}]', 'Completed', '2026-06-19 04:30:00'),
(2, 1111, 'Takeaway', 17.90, 0.00, '[{"name":"Laksa + Fruit Rojak","image":"combo_B.jpg","basePrice":17.9,"comboName":"Combo","comboPrice":0,"preferences":[],"quantity":1,"totalPrice":17.9}]', 'Completed', '2026-06-19 12:45:00'),
(3, 1111, 'Dine-In', 19.40, 0.00, '[{"name":"Laksa + Teh C Beng Special","image":"combo_A.jpg","basePrice":19.4,"comboName":"Combo","comboPrice":0,"preferences":[],"quantity":1,"totalPrice":19.4}]', 'Completed', '2026-06-23 12:30:00'),
(4, 1111, 'Delivery', 20.99, 5.20, '[{\"name\":\"Masisso Signature Laksa\",\"image\":\"laksa.jpg\",\"basePrice\":14.9,\"comboName\":\"A La Carte (Just the Masisso Signature Laksa)\",\"comboPrice\":0,\"preferences\":[],\"quantity\":1,\"totalPrice\":14.9}]', 'Pending', '2026-06-25 14:32:47');
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

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch` enum('Masisso JB City Square','Masisso Mount Austin','Masisso Paradigm Mall') NOT NULL,
  `position` enum('staff','admin','super admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `name`, `username`, `gender`, `phone`, `email`, `password`, `branch`, `position`) VALUES
(2000, 'Shan', 'shan', 'Female', '+60 12-345 6789', 'shanliang@gmail.com', MD5('1234567890'), 'Masisso JB City Square', 'super admin'),
(2001, 'Choong', 'choong', 'Female', '+60 11-222 3333', 'zhi_hui@masisso.com', MD5('adminPass123'), 'Masisso Mount Austin', 'admin'),
(2002, 'Ling', 'ling', 'Female', '+60 11-444 5555', 'yeo_ling@masisso.com', MD5('adminPass456'), 'Masisso Paradigm Mall', 'admin'),
(2003, 'Jin Xuan', 'jinxuan', 'Female', '+60 16-777 8888', 'jinxuan@masisso.com', MD5('staffPass1'), 'Masisso JB City Square', 'staff'),
(2004, 'Alvin Tan', 'alvintan', 'Male', '+60 17-123 4567', 'alvin@masisso.com', MD5('staffPass2'), 'Masisso JB City Square', 'staff'),
(2005, 'Siti Aminah', 'sitiaminah', 'Female', '+60 19-876 5432', 'siti@masisso.com', MD5('staffPass3'), 'Masisso Mount Austin', 'staff'),
(2006, 'Kumar Rao', 'kumarrao', 'Male', '+60 13-987 6543', 'kumar@masisso.com', MD5('staffPass4'), 'Masisso Mount Austin', 'staff'),
(2007, 'Chloe Wong', 'chloewong', 'Female', '+60 18-345 6789', 'chloe@masisso.com', MD5('staffPass5'), 'Masisso Paradigm Mall', 'staff'),
(2008, 'Muhammad Faiz', 'muhammadfaiz', 'Male', '+60 14-567 8901', 'faiz@masisso.com', MD5('staffPass6'), 'Masisso Paradigm Mall', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_customer` (`user_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1112;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2009;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`user_id`) REFERENCES `customer` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
