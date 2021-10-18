-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2021 at 10:45 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jquery`
--

-- --------------------------------------------------------

--
-- Table structure for table `task3_crud`
--

CREATE TABLE `task3_crud` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `hobby` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task3_crud`
--

INSERT INTO `task3_crud` (`id`, `name`, `dob`, `email`, `gender`, `hobby`, `state`, `subject`, `image`, `password`) VALUES
(217, 'Basia Cervantes', '2014-03-04', 'lepojywy@mailinator.com', 'Male', 'Cricket,Reading', 'Delhi', 'Chemistry,Computer,Physics', 'f9f4da819c285820.jpg', '11111111'),
(218, 'Alfreda Lewis', '2011-04-13', 'pycury@mailinator.com', 'Male', 'Cricket,Reading', 'Delhi', 'Physics', NULL, ''),
(219, 'Lionel Henson', '2016-09-17', 'zuhigiw@mailinator.com', 'Male', 'Cricket', 'Delhi', 'Computer', NULL, ''),
(221, 'Irene Mendez', '2007-01-20', 'pegyhuxe@mailinator.com', 'Female', 'Gaming', 'Gujarat', 'Computer,Physics', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task3_crud`
--
ALTER TABLE `task3_crud`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task3_crud`
--
ALTER TABLE `task3_crud`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
