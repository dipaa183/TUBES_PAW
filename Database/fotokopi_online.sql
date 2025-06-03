-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 03, 2025 at 01:37 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fotokopi_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int NOT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_upload` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_pemesanan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_warna` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran_kertas` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_copy` int DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `tanggal_pemesanan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `nama_pelanggan`, `file_upload`, `jenis_pemesanan`, `jenis_warna`, `ukuran_kertas`, `jumlah_copy`, `catatan`, `tanggal_pemesanan`) VALUES
(1, 'Diva Tes', '683eab4209a19_Templare Tugas Besar PAW.docx', 'Print,Jilid', 'Hitam Putih', 'A4', 1, 'Tolong dijilid dengan cover warna merah', '2025-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Jordan Hodges', 'syro@mailinator.com', '$2y$10$.8eT02QioHbfamJ07D8RP.SF2p8GpYjFRTq2AmCZGuNAHPDSpV4Di', '2025-06-03 13:33:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
