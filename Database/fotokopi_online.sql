-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 03, 2025 at 04:54 PM
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
  `user_id` int DEFAULT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_upload` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_pemesanan` text COLLATE utf8mb4_general_ci,
  `jenis_warna` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran_kertas` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_copy` int DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `tanggal_pemesanan` date DEFAULT NULL,
  `status` enum('pending','processing','completed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `user_id`, `nama_pelanggan`, `file_upload`, `jenis_pemesanan`, `jenis_warna`, `ukuran_kertas`, `jumlah_copy`, `catatan`, `tanggal_pemesanan`, `status`, `created_at`, `total_harga`) VALUES
(22, 4, 'Quidem ullam cupidat', '683f17d871ea0_cv_dump.pdf', 'Laminasi,Jilid', 'Hitam Putih', 'A4', 71, 'Molestias eius vero ', '2025-06-03', 'pending', '2025-06-03 15:42:17', 35500.00),
(23, 6, 'Vel ut voluptate ut ', '683f1f9b31364__copy_Sample_document.docx', 'Print,Laminasi', 'Hitam Putih', 'Legal', 58, 'Dolore do et consequ', '2025-06-03', 'processing', '2025-06-03 16:15:24', 34800.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Jordan Hodges', 'syro@mailinator.com', '$2y$10$.8eT02QioHbfamJ07D8RP.SF2p8GpYjFRTq2AmCZGuNAHPDSpV4Di', '2025-06-03 13:33:57', 'user'),
(5, 'Administrator', 'admin@gmail.com', '$2y$10$40/l3a.vhSdtdAJaTMiWDelu76S.kec17mFtaLzkbzf1ZamltmBRa', '2025-06-03 16:10:17', 'admin'),
(6, 'asep oli', 'asep@gmail.com', '$2y$10$dy.jf4YfTIJ3NB/HKd2sUueGf9F272ke7gCuUVHzUVA.koOR3YkT2', '2025-06-03 16:11:59', 'user');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
