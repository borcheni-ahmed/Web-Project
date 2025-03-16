-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 10:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`post_id`, `title`, `content`, `image_url`, `created_at`) VALUES
(1, 'How to Choose the Best Football Boots', 'Choosing the right football boots is essential for performance. Here are some tips...', 'assets/images/insta-1.jpg', '2025-03-15 13:28:45'),
(2, 'Top 5 Football Drills for Beginners', 'If you want to improve your game, start with these five fundamental drills...', 'assets/images/insta-2.jpg', '2025-03-15 13:28:45'),
(3, 'The Evolution of Football Jerseys', 'Football jerseys have come a long way, from cotton shirts to high-tech materials...', 'assets/images/insta-3.jpg', '2025-03-15 13:28:45'),
(4, 'Nutrition Tips for Football Players', 'Your diet affects your performance on the field. Here are some nutrition tips...', 'assets/images/insta-4.jpg', '2025-03-15 13:28:45'),
(5, 'The Best Football Stadiums in the World', 'From the Camp Nou to Old Trafford, explore the most iconic stadiums...', 'assets/images/insta-5.jpg', '2025-03-15 13:28:45'),
(6, 'Understanding Football Formations', 'Football tactics are key to success. Learn about different formations...', 'assets/images/insta-6.jpg', '2025-03-15 13:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Men Collections', 'Explore our collection of men\'s footwear.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(2, 'Women Collections', 'Discover stylish footwear for women.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(3, 'Sports Collections', 'High-performance shoes for sports enthusiasts.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(4, 'Nike', 'Official Nike footwear collection.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(5, 'Adidas', 'Official Adidas footwear collection.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(6, 'Puma', 'Official Puma footwear collection.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(7, 'Bata', 'Affordable and comfortable Bata shoes.', '2025-03-14 23:23:58', '2025-03-14 23:23:58'),
(8, 'Apex', 'Durable and reliable Apex footwear.', '2025-03-14 23:23:58', '2025-03-14 23:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Ala', 'reggggggggggggggggg dfbdrhdfhdf', '2025-03-15 14:04:25'),
(2, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Ala', 'reggggggggggggggggg dfbdrhdfhdf', '2025-03-15 14:06:49'),
(3, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Zammel', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-03-15 14:07:04'),
(4, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Zammel', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-03-15 14:16:55'),
(5, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Ala', 'reggggggggggggggggg dfbdrhdfhdf', '2025-03-15 14:17:08'),
(6, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Zammel', 'eqyrhrdhdf', '2025-03-15 14:17:19'),
(7, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Zammel', 'eqyrhrdhdf', '2025-03-15 14:17:40'),
(8, 'Zammel Adnen', 'adnen.zammel@sfax.r-iset.tn', 'Zammel', 'eqyrhrdhdf', '2025-03-15 14:42:03');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 120.85, 'pending', '2025-03-15 23:05:46', '2025-03-16 10:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `Collections` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `Collections`, `image_url`, `created_at`, `updated_at`) VALUES
(2, 'Leather Mens Slipper', 'Stylish leather slippers for men.', 90.50, 36, 1, 'Women', 'http://localhost/PHP_PROJECT/assets/images/product-2.jpg', '2025-03-14 23:28:51', '2025-03-16 00:35:47'),
(3, 'Simple Fabric Shoe', 'Lightweight and breathable fabric shoes.', 60.00, 40, 2, 'Men', 'http://localhost/PHP_PROJECT/assets/images/product-3.jpg', '2025-03-14 23:28:51', '2025-03-15 16:46:02'),
(4, 'Air Jordan 7 Retro', 'Iconic Air Jordan sneakers.', 170.85, 20, 4, 'Sports', 'http://localhost/PHP_PROJECT/assets/images/product-4.jpg', '2025-03-14 23:28:51', '2025-03-15 16:46:45'),
(5, 'Nike Air Max 270 SE', 'Stylish and comfortable Nike Air Max.', 120.85, 25, 4, 'Women', 'http://localhost/PHP_PROJECT/assets/images/product-5.jpg', '2025-03-14 23:28:51', '2025-03-15 16:46:22'),
(6, 'Adidas Sneakers Shoes', 'Classic Adidas sneakers for everyday wear.', 100.85, 35, 5, 'Men', 'http://localhost/PHP_PROJECT/assets/images/product-6.jpg', '2025-03-14 23:28:51', '2025-03-15 16:46:05'),
(7, 'Nike Basketball Shoes', 'High-performance basketball shoes.', 120.85, 15, 4, 'Sports', 'http://localhost/PHP_PROJECT/assets/images/product-7.jpg', '2025-03-14 23:28:51', '2025-03-15 16:46:46'),
(9, 'Nike jorden', 'Nike jorden', 234.00, 6, 2, 'Men', 'uploads/product-7.jpg', '2025-03-16 00:44:29', '2025-03-16 00:44:29'),
(10, 'tailltchi', 'tailltchi', 169.00, 12, 1, 'Men', 'uploads/product-2.jpg', '2025-03-16 09:48:14', '2025-03-16 09:48:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `created_at`, `updated_at`, `role`) VALUES
(2, 'adnen_zammel23', 'adnen.zammel@sfax.r-iset.tn', '$2y$10$8rwXu8IkKK/TwuWXNSMNqOQ0G3mSaft8GnR9DYkx11G5xvwydciXS', 'Adnen', 'Zammel', '2025-03-14 22:14:54', '2025-03-15 23:16:44', 'admin'),
(4, 'Ahmed borchani', 'borcheniahmed0@gmail.com', '$2y$10$iLLLs.x7Xi0iQotqWrAVlOSCH7/RimGiduBKAWmCU1UUEpTcelXo.', 'Ahmed ', 'borchani', '2025-03-16 00:16:18', '2025-03-16 00:16:18', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
