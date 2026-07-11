-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jul 2026 pada 16.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saw_security`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `no` int(11) NOT NULL,
  `kode` text DEFAULT NULL,
  `Nama Security` varchar(100) NOT NULL,
  `kedisiplinan (K1)` int(11) NOT NULL,
  `Kesiapan Fisik (K2)` int(11) NOT NULL,
  `Ketrampilan Operasional (K3)` int(11) NOT NULL,
  `Sikap Dan Etika Kerja (K4)` int(11) NOT NULL,
  `Pengalaman Kerja (K5)` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`no`, `kode`, `Nama Security`, `kedisiplinan (K1)`, `Kesiapan Fisik (K2)`, `Ketrampilan Operasional (K3)`, `Sikap Dan Etika Kerja (K4)`, `Pengalaman Kerja (K5)`) VALUES
(1, 'A1', 'Aris Franklin', 5, 4, 4, 4, 3),
(2, 'A2', 'Gilyant Sahulata', 4, 5, 3, 4, 2),
(3, 'A3', 'Hence Pratasis', 4, 4, 5, 5, 5),
(4, 'A4', 'Efraim Pedai', 3, 5, 4, 4, 4),
(5, 'A5', 'Rosdiana', 4, 3, 4, 3, 3),
(6, 'A6', 'Elisa regoy', 5, 4, 5, 4, 4),
(7, 'A7', 'Acon Yehuda', 4, 4, 4, 5, 3),
(8, 'A8', 'Kasman Abdulah', 3, 5, 3, 4, 3),
(9, 'A9', 'Reiner Kalalo', 5, 5, 4, 5, 4),
(10, 'A10', 'Mujahidin', 4, 4, 3, 3, 2),
(11, 'A11', 'Reinard Lawalata', 5, 4, 5, 5, 5),
(12, 'A12', 'Wahyudin', 4, 3, 4, 4, 4),
(13, 'A13', 'Mohamad Soleh', 3, 4, 5, 4, 3),
(14, 'A14', 'Erik Pati', 5, 5, 4, 5, 4),
(15, 'A15', 'Henry Sasarary', 4, 4, 3, 3, 3),
(16, 'A16', 'Hiskial Kaiba', 5, 4, 5, 4, 5),
(17, 'A17', 'Oktovian W.', 3, 5, 3, 3, 2),
(18, 'A18', 'Suwarno', 4, 4, 5, 5, 4),
(19, 'A19', 'Dwi Putra Prasetya', 5, 3, 4, 4, 3),
(20, 'A20', 'Marchelo Korwa', 4, 5, 5, 5, 5),
(21, 'A21', 'Samsul', 4, 4, 3, 3, 4),
(22, 'A22', 'Harsan', 3, 4, 4, 2, 3),
(23, 'A23', 'Thomas Nuboba', 4, 5, 3, 4, 4),
(24, 'A24', 'Sergius Auparay', 5, 4, 4, 4, 5),
(25, 'A25', 'Roslan', 3, 3, 4, 3, 3),
(26, 'A26', 'Yunias Anderi', 4, 4, 5, 4, 4),
(27, 'A27', 'Piethein Bonay', 5, 3, 4, 3, 4),
(28, 'A28', 'Lubis Tora', 4, 4, 3, 4, 5),
(29, 'A29', 'Adrian Upuya', 3, 4, 4, 3, 4),
(30, 'A30', 'Kadir H. Doko', 5, 4, 4, 4, 5),
(31, 'A31', 'Imam Fauzi', 3, 3, 3, 3, 4),
(32, 'A32', 'Dady Karubaba', 4, 4, 4, 4, 5),
(33, 'A33', 'Almendo Tauri', 3, 5, 4, 3, 3),
(34, 'A34', 'Jhosua Marwery', 4, 4, 3, 4, 4),
(35, 'A35', 'James Apaseray', 5, 4, 5, 4, 5),
(36, 'A36', 'Agus Merasi', 4, 3, 4, 3, 4),
(37, 'A37', 'Ishak Dimara', 4, 4, 4, 4, 4),
(38, 'A38', 'Fredi Obet N.', 5, 5, 4, 4, 5),
(39, 'A39', 'Muhammad Arif', 3, 4, 4, 3, 4),
(40, 'A40', 'Hasidik Wujon', 4, 4, 5, 4, 4),
(41, 'A41', 'Charly Manobi', 3, 3, 4, 2, 4),
(42, 'A42', 'Dominggus Fernandes', 5, 4, 4, 4, 5),
(43, 'A43', 'Rudi Sopyan', 4, 3, 4, 3, 3),
(44, 'A44', 'Adrian Ansanay', 5, 5, 4, 4, 4),
(46, 'A45', 'Joseferino', 4, 4, 4, 3, 4),
(47, 'A46', 'Kenny Kbarek', 3, 4, 3, 3, 4),
(48, 'A47', 'Roberto Nuboba', 4, 4, 3, 4, 5),
(49, 'A48', 'Rudi', 5, 4, 5, 4, 4),
(50, 'A49', 'Berty Mairuh', 3, 4, 4, 2, 3),
(51, 'A50', 'Frans Maurids', 4, 4, 4, 3, 4),
(52, 'A51', 'Ilham Pratama', 4, 4, 4, 3, 4),
(53, 'A52', 'Ronald Maitindom', 3, 4, 3, 3, 4),
(54, 'A53', 'Abdul Amin', 4, 4, 4, 4, 5),
(55, 'A54', 'Daniel Reba', 5, 5, 5, 4, 5),
(56, 'A55', 'Alfares Imbiri', 4, 3, 4, 3, 4),
(57, 'A56', 'Frenky Sineri', 3, 4, 4, 2, 4),
(58, 'A57', 'Rehiul Bonay', 4, 4, 3, 3, 3),
(59, 'A58', 'Sumardin', 5, 4, 5, 4, 4),
(60, 'A59', 'Arwin', 4, 3, 4, 3, 4),
(61, 'A60', 'Daniel Munua', 4, 5, 4, 4, 5),
(62, 'A61', 'Legowo', 3, 4, 3, 2, 3),
(63, 'A62', 'Jamaludin', 4, 4, 4, 4, 4),
(64, 'A63', 'Maikeldo', 5, 5, 4, 4, 5),
(65, 'A64', 'Titus Orlando', 4, 4, 3, 3, 4),
(66, 'A65', 'Dominggus W.', 3, 4, 4, 2, 3),
(67, 'A66', 'Yohanes Sokoy', 4, 5, 3, 4, 4),
(68, 'A67', 'Namles Morist', 5, 4, 4, 4, 5),
(69, 'A68', 'Michael Yarum', 3, 3, 4, 3, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `nomor` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `kriteria` varchar(100) NOT NULL,
  `atribut` enum('benefit','cost') NOT NULL,
  `bobot` int(11) NOT NULL,
  `normalisasi` decimal(10,5) NOT NULL DEFAULT 0.00000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`nomor`, `kode`, `kriteria`, `atribut`, `bobot`, `normalisasi`) VALUES
(1, 'K1', 'Kedisiplinan', 'benefit', 5, 0.23810),
(2, 'K2', 'Kesiapan Fisik', 'benefit', 4, 0.19048),
(3, 'K3', 'Ketrampilan Operasional', 'benefit', 5, 0.23810),
(4, 'K4', 'Sikap Dan Etika Kerja', 'benefit', 4, 0.19048),
(5, 'K5', 'Pengalaman Kerja', 'benefit', 3, 0.14286);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`nomor`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `nomor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
