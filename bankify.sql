-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2024 at 07:01 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bankify`
--

-- --------------------------------------------------------

--
-- Table structure for table `internet_banking`
--

CREATE TABLE `internet_banking` (
  `user_id` int(10) NOT NULL,
  `account_no` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` varchar(100) NOT NULL,
  `IFSC_code` varchar(50) NOT NULL,
  `Branch` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `internet_banking`
--

INSERT INTO `internet_banking` (`user_id`, `account_no`, `name`, `dob`, `IFSC_code`, `Branch`, `email`) VALUES
(1, 'BB12345678', 'Pranav Shirsat', '11/06/2004', 'BF2004', 'Mumbai', 'pranavshirsat06@gmail.com'),
(2, 'BB5555555', 'Jyoti Shirsat', '28/05/1997', 'BF2004', 'Mumbai', 'jyotishirsat401@gmail.com'),
(3, 'BB6666666', 'Shree Sawant', '6/6/2006', 'BF2004', 'Mumbai', 'shreesawan37@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `user_id` int(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `beneficiary_account` varchar(255) NOT NULL,
  `total_money` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`user_id`, `email`, `beneficiary_account`, `total_money`) VALUES
(8, 'pranavshirsat06@gmail.com', 'BB12345678', 1654);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` enum('deposit','withdraw') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`id`, `email`, `type`, `amount`, `date`) VALUES
(1, 'pranavshirsat06@gmail.com', 'deposit', '121.00', '2024-02-07 18:58:37'),
(2, 'pranavshirsat06@gmail.com', 'withdraw', '500.00', '2024-02-07 18:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` int(100) NOT NULL,
  `email` int(100) NOT NULL,
  `password` int(100) NOT NULL,
  `code` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `code`) VALUES
(0, 0, 0, 0, 7040000);

-- --------------------------------------------------------

--
-- Table structure for table `users1`
--

CREATE TABLE `users1` (
  `id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`id`, `name`, `email`, `password`, `code`) VALUES
(1, 'pranav', 'shirsatpranav11@gmail.com', 'f6791a87fef651772074cca3ba1af2ca', '656da98f251417d3b3f6985e2aa127e3'),
(2, 'Pranav Shirsat', 'pranavshirsat06@gmail.com', 'f6791a87fef651772074cca3ba1af2ca', 'c1d8b5ca6a2c3a2a1bd011f52f0434cc'),
(5, 'Jyoti Shirsat', 'jyotishirsat401@gmail.com', '2dded56f2ed69d3672ce5072d63de462', '87891b96d5ca7037c84338034e2c9f82'),
(6, 'Shree', 'shreesawan37@gmail.com', 'd28fd6c8e7a1c43e66dcf44797a760eb', 'b57c0b87224a9e19019946bb3779749a'),
(7, 'user', 'user1@gmail.com', 'd8da18899b5cd63b01724a9bbc8d6441', '8dd83e63ef4f4fb4dff8ff8120fe5a95'),
(8, 'ABC', 'xxx11@gmail.com', 'f6791a87fef651772074cca3ba1af2ca', '34d0be75bc5ec7009df9029595e7d49d'),
(9, '', '', 'd41d8cd98f00b204e9800998ecf8427e', '443600f5c4dd069737aaef1e81e0ad65');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `internet_banking`
--
ALTER TABLE `internet_banking`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users1`
--
ALTER TABLE `users1`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users1`
--
ALTER TABLE `users1`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
