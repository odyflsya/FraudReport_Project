-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Bulan Mei 2026 pada 04.49
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fraud_project2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-audrey@gmail.com|127.0.0.1', 'i:1;', 1778462560),
('laravel-cache-audrey@gmail.com|127.0.0.1:timer', 'i:1778462560;', 1778462560),
('laravel-cache-zaizhamichella@gmail.com|127.0.0.1', 'i:1;', 1778472171),
('laravel-cache-zaizhamichella@gmail.com|127.0.0.1:timer', 'i:1778472171;', 1778472171),
('laravel-cache-zaizhamichella09@gmailcom|127.0.0.1', 'i:1;', 1778472191),
('laravel-cache-zaizhamichella09@gmailcom|127.0.0.1:timer', 'i:1778472191;', 1778472191);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `email_otps`
--

CREATE TABLE `email_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `email_otps`
--

INSERT INTO `email_otps` (`id`, `email`, `code_hash`, `expires_at`, `used_at`, `created_at`, `updated_at`) VALUES
(1, 'sitiaudreyfalisya8@gmail.com', '$2y$12$2q/fren8gVIXE25ZcGS1suiy6iJd0dX/dR8idDPk4T9T7F7kNwzs2', '2026-04-30 02:06:47', '2026-04-29 19:06:47', '2026-04-29 19:05:56', '2026-04-29 19:06:47'),
(2, 'zaizhamichella09@gmail.com', '$2y$12$TbVMIp0yGkQR2RQlFyTE3ulwsRwTXv/z2YC5OIq9zwV.O928Ow3s2', '2026-05-11 03:30:37', '2026-05-10 20:30:37', '2026-05-10 19:34:41', '2026-05-10 20:30:37'),
(3, 'zaizhamichella09@gmail.com', '$2y$12$pwaV5EVPyEu2Nicb2yEq3eiIBvm415neSATy44FX8QlxE1hAgFQUy', '2026-05-11 03:30:37', '2026-05-10 20:30:37', '2026-05-10 19:39:12', '2026-05-10 20:30:37'),
(4, 'zaizhamichella09@gmail.com', '$2y$12$9dz5f14oddBg/psN5cwuA.9lIEllOpsXYmZELg1a2jXSUqsaujWqm', '2026-05-11 02:44:29', '2026-05-10 19:44:29', '2026-05-10 19:44:07', '2026-05-10 19:44:29'),
(5, 'kakakche09@gmail.com', '$2y$12$nb0Ul//aWrZiRlUOh37mSe8Gko64Vu0ivW86boWn4iaul2QRQVZhq', '2026-05-13 01:42:40', '2026-05-12 18:42:40', '2026-05-10 20:26:51', '2026-05-12 18:42:40'),
(6, 'zaizhamichella09@gmail.com', '$2y$12$zdFdUGm6N8Yw5PAGbjn6M.spk9WO1jaObQb/uCwWf0f9gwwuukh.K', '2026-05-11 03:35:58', '2026-05-10 20:35:58', '2026-05-10 20:30:38', '2026-05-10 20:35:58'),
(7, 'zaizhamichella09@gmail.com', '$2y$12$WPni3.PW6Pq3vmreeniit.53dk4dGkvazsh7bFy78mg.B5BlEQUm.', '2026-05-11 03:37:25', '2026-05-10 20:37:25', '2026-05-10 20:35:59', '2026-05-10 20:37:25'),
(8, 'kakakche09@gmail.com', '$2y$12$1AdHY8g1ixIm2IXGgz4DWedoLQrknRbbabl1Y5.b7vuJX2KSRRcAa', '2026-05-13 01:42:40', '2026-05-12 18:42:40', '2026-05-12 18:42:10', '2026-05-12 18:42:40'),
(9, 'kakakche09@gmail.com', '$2y$12$QCCBJa9Vs1uzt59AJWVk0eXV8dGDuOdZvwOkzqDfVv9frkOqV0oPe', '2026-05-13 01:43:04', '2026-05-12 18:43:04', '2026-05-12 18:42:41', '2026-05-12 18:43:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasus`
--

CREATE TABLE `kasus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_komponen` varchar(255) NOT NULL,
  `deskripsi_fraud` text NOT NULL,
  `divisi_unit` varchar(255) NOT NULL,
  `status_penanganan` varchar(255) NOT NULL,
  `jenis_laporan` enum('semester','signifikan') NOT NULL DEFAULT 'semester',
  `tindak_lanjut_ljk` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aktivitas_terkait_id` bigint(20) UNSIGNED NOT NULL,
  `pihak_dirugikan_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kasus`
--

INSERT INTO `kasus` (`id`, `user_id`, `kode_komponen`, `deskripsi_fraud`, `divisi_unit`, `status_penanganan`, `jenis_laporan`, `tindak_lanjut_ljk`, `created_at`, `updated_at`, `aktivitas_terkait_id`, `pihak_dirugikan_id`) VALUES
(2, NULL, '01', 'tessssssssssssssssssssssssssssssssssssssssssssssssssssssss', 'tes', 'proses', 'semester', NULL, '2026-04-14 19:03:58', '2026-04-14 19:03:58', 2, 1),
(3, NULL, '02', 'tesssssssssssssssssssssssssssssssssssssss', 'tes', 'proses', 'semester', NULL, '2026-04-14 19:24:12', '2026-04-14 19:24:12', 1, 1),
(4, NULL, '03', 'tesssssss', 'tes', 'proses', 'semester', NULL, '2026-04-14 19:30:45', '2026-04-14 19:30:45', 1, 2),
(5, NULL, '04', 'tes', 'tes', 'proses', 'semester', NULL, '2026-04-14 19:41:39', '2026-04-14 19:41:39', 5, 2),
(6, NULL, '0101000000', 'tes', 'tes', '002', 'semester', NULL, '2026-04-14 19:48:10', '2026-04-27 03:08:55', 6, 2),
(7, NULL, '0101000000', 'Pemberian kredit yang tidak mengedepankan prinsip prudential banking dan penyalahgunaan dana tujuan kredit sehingga menimbulkan kerugian Bank', 'Kantor Cabang Stabat', '002', 'semester', NULL, '2026-04-15 18:40:02', '2026-04-26 19:41:12', 3, 1),
(8, NULL, '0101000000', 'Melakukan tindakan kecurangan berupa mark up nilai pengadaan barang/jasa serta membebankan biaya konsumsi pribadi dengan menggunakan biaya promosi penjualan', 'Divisi Tresuri', '002', 'semester', NULL, '2026-04-27 19:02:47', '2026-04-27 19:02:47', 6, 1),
(9, NULL, '0101000000', 'tes', 'Kantor Cabang Stabat', '001', 'signifikan', 'tes', '2026-04-27 21:47:22', '2026-04-27 21:47:22', 9, 1),
(10, 6, '0101000000', 'Pemberian kredit yang tidak mengedepankan prinsip prudential banking dan penyalahgunaan dana tujuan kredit sehingga menimbulkan kerugian Bank', 'Kantor Cabang Stabat', '002', 'semester', NULL, '2026-04-29 19:40:02', '2026-04-29 19:40:02', 2, 1),
(11, 6, '0101000000', 'Penggunaan sebahagian/seluruh dana kredit (topengan/tempilan), double financing, melakukan rekayasa pada analisa kredit dengan usaha fiktif serta memperoleh fee atas realisasi kredit', 'KC Tembung', '002', 'semester', NULL, '2026-04-29 19:59:19', '2026-04-29 19:59:19', 2, 1),
(12, 6, '0101000000', 'Orang yang memiliki niat jahat dengan membuat skenario penipuan pada KCSy Medan Ringroad dan menerima aliran dana realisasi kredit.', 'Kantor Cabang Syariah Medan Ringroad', '001', 'signifikan', 'Melakukan perbaikan ketentuan dan penguatan pengendalian intern serta melakukan kajian hukum untuk perlu tidaknya melaporkan para pelaku eksternal kepada aparat penegak hukum', '2026-04-29 21:05:27', '2026-04-29 21:05:27', 2, 1),
(13, 6, '0101000000', 'Penggunaan sebahagian/seluruh dana kredit (topengan/tempilan), double financing, melakukan rekayasa pada analisa kredit dengan usaha fiktif serta memperoleh fee atas realisasi kredit', 'Divisi Tresuri', '002', 'semester', NULL, '2026-05-06 20:23:38', '2026-05-06 20:23:38', 11, 1),
(14, 9, '0101000000', 'Tindakan kecurangan berupa Memutus/memberikan kredit tidak dengan prinsip kehati-hatian sehingga sebahagian dana kredit digunakan oleh pihak lain (agen), usaha yang dibiayai merupakan usaha fiktif serta menerima keuntungan pribadi berupa fee dari agen serta penggunaan sebahagian dana realisasi kredit dan memberikan fee atas realisasi kredit kepada petugas bank.', 'Kantor Cabang Pembantu Serbelawan', '001', 'semester', NULL, '2026-05-10 20:00:17', '2026-05-10 20:00:17', 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasus_jenis_fraud`
--

CREATE TABLE `kasus_jenis_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_fraud_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kasus_jenis_fraud`
--

INSERT INTO `kasus_jenis_fraud` (`id`, `kasus_id`, `jenis_fraud_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 2, 11, NULL, '2026-04-14 19:03:58', '2026-04-14 19:03:58'),
(3, 3, 11, NULL, '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, 1, NULL, '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, 11, 'tes', '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(9, 7, 9, NULL, '2026-04-26 19:41:12', '2026-04-27 01:30:09'),
(10, 6, 7, NULL, '2026-04-27 03:08:55', '2026-04-27 03:09:05'),
(11, 8, 8, NULL, '2026-04-27 19:02:47', '2026-04-28 19:05:56'),
(12, 9, 5, NULL, '2026-04-27 21:47:23', '2026-04-29 01:28:40'),
(13, 10, 9, NULL, '2026-04-29 19:40:02', '2026-05-06 20:00:56'),
(14, 11, 9, NULL, '2026-04-29 19:59:19', '2026-05-06 20:02:52'),
(16, 12, 8, NULL, '2026-04-29 21:07:05', '2026-05-06 20:04:18'),
(17, 13, 8, NULL, '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(18, 14, 9, NULL, '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasus_kejadian_fraud`
--

CREATE TABLE `kasus_kejadian_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `kejadian_id` bigint(20) UNSIGNED NOT NULL,
  `kode_kejadian` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kasus_kejadian_fraud`
--

INSERT INTO `kasus_kejadian_fraud` (`id`, `kasus_id`, `kejadian_id`, `kode_kejadian`, `created_at`, `updated_at`) VALUES
(2, 2, 1, NULL, '2026-04-14 19:03:58', '2026-04-14 19:03:58'),
(3, 3, 1, NULL, '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, 1, NULL, '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, 1, NULL, '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(10, 7, 1, '12345678', '2026-04-26 19:41:12', '2026-04-27 01:30:09'),
(11, 6, 2, '12345678', '2026-04-27 03:08:55', '2026-04-27 03:09:05'),
(12, 8, 4, 'AS22025000005', '2026-04-27 19:02:47', '2026-04-28 19:05:56'),
(13, 9, 5, 'BS22025000001', '2026-04-27 21:47:23', '2026-04-29 01:28:40'),
(14, 10, 4, 'AS22025000005', '2026-04-29 19:40:02', '2026-05-06 20:00:56'),
(15, 11, 4, 'AS22025000002', '2026-04-29 19:59:19', '2026-05-06 20:02:52'),
(16, 12, 5, 'BS22025000001', '2026-04-29 21:05:27', '2026-05-06 20:04:18'),
(17, 13, 4, 'AS22025000003', '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(18, 14, 6, 'CS22025000001', '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasus_kelemahan`
--

CREATE TABLE `kasus_kelemahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `kelemahan_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kasus_kelemahan`
--

INSERT INTO `kasus_kelemahan` (`id`, `kasus_id`, `kelemahan_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 2, 1, NULL, '2026-04-14 19:03:58', '2026-04-14 19:03:58'),
(3, 3, 1, NULL, '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, 16, NULL, '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, 16, 'tes', '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(6, 6, 16, 'tes', '2026-04-14 19:48:10', '2026-04-27 03:09:05'),
(7, 7, 16, 'lainnya', '2026-04-15 18:40:02', '2026-04-27 01:30:09'),
(9, 8, 3, NULL, '2026-04-27 19:02:47', '2026-04-28 19:05:56'),
(10, 10, 1, NULL, '2026-04-29 19:40:02', '2026-05-06 20:00:56'),
(11, 11, 1, NULL, '2026-04-29 19:59:19', '2026-05-06 20:02:52'),
(12, 13, 12, NULL, '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(13, 14, 15, NULL, '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasus_lokasi`
--

CREATE TABLE `kasus_lokasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `lokasi_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kasus_lokasi`
--

INSERT INTO `kasus_lokasi` (`id`, `kasus_id`, `lokasi_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 2, 1, NULL, '2026-04-14 19:03:58', '2026-04-14 19:03:58'),
(3, 3, 1, '123', '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, 1, '123', '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, 1, '123', '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(6, 6, 12, 'tes', '2026-04-14 19:48:10', '2026-04-27 03:09:05'),
(9, 7, 5, '098', '2026-04-26 19:41:12', '2026-04-27 01:30:09'),
(10, 8, 1, '3396', '2026-04-27 19:02:47', '2026-04-28 19:05:56'),
(11, 9, 8, '3396', '2026-04-27 21:47:23', '2026-04-29 01:28:40'),
(12, 10, 5, '3302', '2026-04-29 19:40:02', '2026-05-06 20:00:56'),
(13, 11, 5, '3301', '2026-04-29 19:59:19', '2026-05-06 20:02:52'),
(14, 12, 5, '3396', '2026-04-29 21:05:27', '2026-05-06 20:04:18'),
(15, 13, 8, '3302', '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(16, 14, 8, '3304', '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasus_penanganan`
--

CREATE TABLE `kasus_penanganan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `tindakan_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kasus_penanganan`
--

INSERT INTO `kasus_penanganan` (`id`, `kasus_id`, `tindakan_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 2, 2, NULL, '2026-04-14 19:03:58', '2026-04-14 19:03:58'),
(3, 3, 14, NULL, '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, 1, NULL, '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, 14, 'tes', '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(6, 6, 14, 'tes', '2026-04-14 19:48:10', '2026-04-27 03:09:05'),
(7, 7, 14, 'lainnya', '2026-04-15 18:40:02', '2026-04-27 01:30:09'),
(9, 8, 5, NULL, '2026-04-27 19:02:47', '2026-04-28 19:05:56'),
(10, 10, 5, NULL, '2026-04-29 19:40:02', '2026-05-06 20:00:56'),
(11, 11, 5, NULL, '2026-04-29 19:59:20', '2026-05-06 20:02:52'),
(12, 13, 9, NULL, '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(13, 14, 10, NULL, '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kerugian_fraud`
--

CREATE TABLE `kerugian_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `ljk_rill` decimal(15,2) DEFAULT NULL,
  `ljk_potensial` decimal(15,2) DEFAULT NULL,
  `ljk_recovery` decimal(15,2) DEFAULT NULL,
  `konsumen_rill` decimal(15,2) DEFAULT NULL,
  `konsumen_potensial` decimal(15,2) DEFAULT NULL,
  `konsumen_recovery` decimal(15,2) DEFAULT NULL,
  `pihak_lain_rill` decimal(15,2) DEFAULT NULL,
  `pihak_lain_potensial` decimal(15,2) DEFAULT NULL,
  `pihak_lain_recovery` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kerugian_fraud`
--

INSERT INTO `kerugian_fraud` (`id`, `kasus_id`, `ljk_rill`, `ljk_potensial`, `ljk_recovery`, `konsumen_rill`, `konsumen_potensial`, `konsumen_recovery`, `pihak_lain_rill`, `pihak_lain_potensial`, `pihak_lain_recovery`, `created_at`, `updated_at`) VALUES
(2, 2, 10000.00, 10000.00, 9999.99, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-14 19:03:59', '2026-04-14 19:03:59'),
(3, 3, 10000.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, 100000.00, 100000.00, 99999.99, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, 1000.00, 1000.00, 1000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(6, 6, 1000.00, 1000.00, 1000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-14 19:48:10', '2026-04-14 19:48:10'),
(7, 7, 10000.00, 56780.00, 273723.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-15 18:40:02', '2026-04-15 18:40:02'),
(8, 8, 627434972.00, 0.00, 503035240.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-27 19:02:47', '2026-04-27 19:02:47'),
(9, 9, 0.00, 1234566.98, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-04-27 21:47:23', '2026-04-27 21:47:23'),
(10, 10, 441912635.00, 275604923.00, 717517558.00, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-29 19:40:02', '2026-05-06 20:00:56'),
(11, 11, 530615630.00, 637674893.00, 1115439324.00, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-29 19:59:20', '2026-05-06 20:02:52'),
(12, 12, 0.00, 177661418.00, 0.00, 0.00, NULL, 0.00, 0.00, NULL, 0.00, '2026-04-29 21:05:27', '2026-05-06 20:04:18'),
(13, 13, 123456789.00, 123456789.00, 123456789.00, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(14, 14, 50.00, 50.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_09_000001_create_kasus_table', 1),
(5, '2026_04_09_000002_create_ref_jenis_fraud_table', 1),
(6, '2026_04_09_000003_create_ref_lokasi_fraud_table', 1),
(7, '2026_04_09_000004_create_ref_kelemahan_fraud_table', 1),
(8, '2026_04_09_000005_create_ref_tindakan_penanganan_table', 1),
(9, '2026_04_09_000006_create_ref_kejadian_fraud_table', 1),
(10, '2026_04_09_000007_create_ref_pencegahan_fraud_table', 1),
(11, '2026_04_09_000008_create_kasus_jenis_fraud_table', 1),
(12, '2026_04_09_000009_create_kasus_lokasi_table', 1),
(13, '2026_04_09_000010_create_kasus_kelemahan_table', 1),
(14, '2026_04_09_000011_create_kasus_penanganan_table', 1),
(15, '2026_04_09_000012_create_kasus_kejadian_fraud_table', 1),
(16, '2026_04_09_000013_create_waktu_fraud_table', 1),
(17, '2026_04_09_000014_create_kerugian_fraud_table', 1),
(18, '2026_04_09_000015_create_pencegahan_fraud_table', 1),
(19, '2026_04_09_000016_create_pelaku_fraud_table', 1),
(20, '2026_04_10_000017_create_ref_aktivitas_terkait_table', 1),
(21, '2026_04_10_000018_create_ref_pihak_dirugikan_table', 1),
(22, '2026_04_10_000019_alter_kasus_table_add_foreign_keys', 1),
(23, '2026_04_10_000020_create_ref_jabatan_table', 1),
(24, '2026_04_10_000021_create_ref_jenis_identitas_table', 1),
(25, '2026_04_10_000022_create_ref_status_pelaku_table', 1),
(26, '2026_04_10_000023_alter_pelaku_fraud_table_normalize_fields', 1),
(27, '2026_04_10_000024_alter_ref_aktivitas_terkait_add_unique_kode', 1),
(28, '2026_04_10_000025_alter_ref_pihak_dirugikan_add_unique_kode', 1),
(29, '2026_04_10_025818_add_kode_to_ref_kelemahan_fraud_table', 1),
(30, '2026_04_16_013359_create_sessions_table', 1),
(31, '2026_04_27_000001_add_kode_kejadian_to_kasus_kejadian_fraud_table', 2),
(32, '2026_04_09_000014_add_jenis_laporan_and_tindak_lanjut_ljk_to_kasus_table', 3),
(33, '2026_04_29_000001_create_email_otps_table', 4),
(34, '2026_04_30_000001_add_user_id_to_kasus_table', 5),
(35, '2025_05_04_000000_add_profile_photo_to_users_table', 6),
(36, '2026_05_07_000000_modify_kerugian_fraud_nullable', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('zaizhamichella09@gmail.com', '$2y$12$0396PlVFw0nsDtqcrXp5..P2XAJpCLosntBfhQKqhq51Co/Cb8Wfe', '2026-05-17 18:48:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelaku_fraud`
--

CREATE TABLE `pelaku_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `kategori` enum('internal','eksternal') NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nomor_identitas` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat_identitas` text NOT NULL,
  `alamat_domisili` text NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `ket_jabatan_kejadian` text DEFAULT NULL,
  `ket_jabatan_diketahui` text DEFAULT NULL,
  `keterangan` text NOT NULL,
  `sanksi` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis_identitas_id` bigint(20) UNSIGNED NOT NULL,
  `status_pelaku_id` bigint(20) UNSIGNED NOT NULL,
  `jabatan_saat_kejadian_id` bigint(20) UNSIGNED NOT NULL,
  `jabatan_saat_diketahui_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pelaku_fraud`
--

INSERT INTO `pelaku_fraud` (`id`, `kasus_id`, `kategori`, `nama`, `nomor_identitas`, `jenis_kelamin`, `alamat_identitas`, `alamat_domisili`, `tempat_lahir`, `tanggal_lahir`, `ket_jabatan_kejadian`, `ket_jabatan_diketahui`, `keterangan`, `sanksi`, `created_at`, `updated_at`, `jenis_identitas_id`, `status_pelaku_id`, `jabatan_saat_kejadian_id`, `jabatan_saat_diketahui_id`) VALUES
(7, 7, 'internal', 'Wije Kumar', '1205032505890007', 'L', 'Jl. D.I Panjaitan Lingk II Kel/Desa Sidomulyo Kecamatan Stabat Kabupaten Langkat', 'Jl. D.I Panjaitan Lingk II Kel/Desa Sidomulyo Kecamatan Stabat Kabupaten Langkat', 'Bela Rakyat', '2026-04-16', 'Account Officer KC Stabat', 'Account Officer KC Stabat', '-', 'Telah dijatuhkan Sanksi PHK', '2026-04-27 01:30:10', '2026-04-27 01:30:10', 1, 1, 13, 13),
(9, 6, 'eksternal', 'tes', '12345678', 'L', 'tes', 'tes', 'tes', '2026-04-15', 'tes', 'tes', 'tes', 'tes', '2026-04-27 03:09:05', '2026-04-27 03:09:05', 1, 1, 8, 12),
(12, 8, 'internal', 'Mahyuni Miraza', '1205032505890007', 'P', 'Jl Arafah No 67 Komp Al Barokah Kel/Desa Sampali Kecamatan Percut Sei Tuan', 'Jl Arafah No 67 Komp Al Barokah Kel/Desa Sampali Kecamatan Percut Sei Tuan', 'Medan', '2026-04-28', 'Pemimpin Bidang Institusi Keuangan', 'Diberlakukan tindakan administratif (Pegawai Non Aktif)', '', 'Telah dijatuhkan Sanksi PHK', '2026-04-28 19:05:56', '2026-04-28 19:05:56', 1, 1, 12, 12),
(13, 9, 'internal', 'Khairul Lizan', '1205032505890007', 'L', 'tes', 'tes', 'Medan', '2026-04-28', 'Account Officer KC Stabat', 'Account Officer KC Stabat', '001 (Konsumen)', 'tes', '2026-04-29 01:28:40', '2026-04-29 01:28:40', 1, 1, 12, 13),
(19, 10, 'internal', 'Wije Kumar', '1205032505890007', 'L', 'Jl. D.I Panjaitan Lingk II Kel/Desa Sidomulyo Kecamatan Stabat Kabupaten Langkat', 'Jl. D.I Panjaitan Lingk II Kel/Desa Sidomulyo Kecamatan Stabat Kabupaten Langkat', 'Bela Rakyat', '2026-04-14', 'Account Officer KC Stabat', 'Account Officer KCP Brandan (Non Job)', '', 'Telah dijatuhkan Sanksi PHK', '2026-05-06 20:00:56', '2026-05-06 20:00:56', 1, 1, 13, 13),
(20, 11, 'internal', 'Yudi Prabowo', '1205032505890007', 'L', 'Komp Marelan Indah Lingk 13 Kelurahan Rengas Pulau Kecamatan Medan Marelan Kota Medan', 'Komp Marelan Indah Lingk 13 Kelurahan Rengas Pulau Kecamatan Medan Marelan Kota Medan', 'Medan', '2026-04-30', 'Account Officer KC Tembung', 'Account Officer KC Tembung', '', 'PHK dikarenakan mangkir', '2026-05-06 20:02:52', '2026-05-06 20:02:52', 1, 1, 13, 13),
(21, 12, 'internal', 'Khairul Lizan', '1205032505890007', 'L', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', 'Medan', '2026-04-30', 'Account Officer KC Stabat', 'Account Officer KCP Brandan (Non Job)', '', 'Kajian untuk dilaporkan ke APH', '2026-05-06 20:04:18', '2026-05-06 20:04:18', 1, 1, 13, 13),
(22, 13, 'internal', 'Yudi Prabowo', '1205032505890007', 'L', 'Penggunaan sebahagian/seluruh dana kredit (topengan/tempilan), double financing, melakukan rekayasa pada analisa kredit dengan usaha fiktif serta memperoleh fee atas realisasi kredit', 'Penggunaan sebahagian/seluruh dana kredit (topengan/tempilan), double financing, melakukan rekayasa pada analisa kredit dengan usaha fiktif serta memperoleh fee atas realisasi kredit', 'Medan', '2026-05-07', 'Account Officer KC Stabat', 'Account Officer KC Tembung', '001 (Konsumen)', 'Telah dijatuhkan Sanksi PHK', '2026-05-06 20:23:39', '2026-05-06 20:23:39', 1, 1, 12, 13),
(23, 14, 'internal', 'Hendra Wijaya', '1103130501820001', 'L', 'Huta V Pulo Sarana Kel/Desa Bahal Batu Kecamatan Huta Bayu Raja', 'Huta V Pulo Sarana Kel/Desa Bahal Batu Kecamatan Huta Bayu Raja', 'Lubuk CIna', '1982-05-01', 'Account Officer KCP Serbelawan', 'Account Officer KCP Serbelawan', '001 (Konsumen)', 'Kajian untuk dilaporkan ke APH', '2026-05-10 20:00:17', '2026-05-10 20:00:17', 1, 2, 13, 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pencegahan_fraud`
--

CREATE TABLE `pencegahan_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `pencegahan_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text NOT NULL,
  `target_waktu` date NOT NULL,
  `realisasi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pencegahan_fraud`
--

INSERT INTO `pencegahan_fraud` (`id`, `kasus_id`, `pencegahan_id`, `keterangan`, `target_waktu`, `realisasi`, `created_at`, `updated_at`) VALUES
(2, 4, 2, 'tesssssss', '2026-04-15', '2026-04-15', '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(3, 5, 6, 'tesssssss', '2026-04-16', '2026-04-30', '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(10, 7, 4, 'tes', '2026-04-16', '2026-04-16', '2026-04-27 01:30:10', '2026-04-27 01:30:10'),
(12, 6, 2, 'tes', '2026-04-15', '2026-04-15', '2026-04-27 03:09:05', '2026-04-27 03:09:05'),
(14, 8, 2, 'Melakukan perbaikan ketentuan dan penguatan pengendalian intern yaitu berupa evaluasi ketentuan Pelaksanaan Pengadaan Barang dan Jasa agar dapat mengatur perihal Flowchart pengadaan mulai dari usulan hingga proses dokumentasi pengadaan barang/jasa, dan Tools/register yang dapat digunakan dalam memonitoring persediaan barang souvenir yang masuk dan keluar sebagai kontrol dalam pengadaan barang/jasa', '2026-04-28', '2026-04-28', '2026-04-28 19:05:56', '2026-04-28 19:05:56'),
(17, 10, 2, 'Divisi Ritel agar memperkuat pengendalian intern berupa supervisi dan monitoring terhadap kredit yang menunggak dibawah 1 (satu) tahun serta pertumbuhan pemberian kredit di unit kantor yang dinilai tidak wajar baik secara keseluruhan ataupun berdasarkan skim kredit serta menghimbau kepada seluruh satuan kerja terkait realisasi KUR untuk melakukan supervisi dan monitoring terhadap kebenaran penggunaan dana realisasi kredit.', '2026-04-15', '2026-04-30', '2026-05-06 20:00:56', '2026-05-06 20:00:56'),
(18, 11, 4, 'Melakukan penguatan dengan pelaksanaan program pencegahan dan pendeteksian fraud', '2026-04-30', '2026-04-30', '2026-05-06 20:02:52', '2026-05-06 20:02:52'),
(19, 13, 3, 'Melakukan perbaikan ketentuan dan penguatan pengendalian intern yaitu berupa evaluasi ketentuan Pelaksanaan Pengadaan Barang dan Jasa agar dapat mengatur perihal Flowchart pengadaan mulai dari usulan hingga proses dokumentasi pengadaan barang/jasa, dan Tools/register yang dapat digunakan dalam memonitoring persediaan barang souvenir yang masuk dan keluar sebagai kontrol dalam pengadaan barang/jasa', '2026-05-07', '2026-05-07', '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(20, 14, 4, 'Melakukan penguatan dengan pelaksanaan program pencegahan fraud', '2026-05-11', '2026-05-11', '2026-05-10 20:00:17', '2026-05-10 20:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_aktivitas_terkait`
--

CREATE TABLE `ref_aktivitas_terkait` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_aktivitas_terkait`
--

INSERT INTO `ref_aktivitas_terkait` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '301', 'Pendanaan', NULL, NULL),
(2, '302', 'Perkreditan/pembiayaan', NULL, NULL),
(3, '303', 'Penggunaan identitas dan data orang, pihak lain, atau konsumen', NULL, NULL),
(4, '304', 'Pengelolaan aset /investasi', NULL, NULL),
(5, '305', 'Penggunaan siber', NULL, NULL),
(6, '306', 'Pembukuan dan penyajian laporan keuangan', NULL, NULL),
(7, '307', 'Anti pencucian uang (APU), pencegahan pendanaan terorisme (PPT) dan pencegahan pendanaan proliferasi senjata pemusnah massal (PPPSPM)', NULL, NULL),
(8, '308', 'Transaksi efek', NULL, NULL),
(9, '309', 'Pemasaran', NULL, NULL),
(10, '310', 'Kustodian', NULL, NULL),
(11, '311', 'Penjatahan efek', NULL, NULL),
(12, '312', 'Due diligence penjaminan emisi efek', NULL, NULL),
(13, '313', 'Riset investasi', NULL, NULL),
(14, '314', 'Proses underwriting', NULL, NULL),
(15, '315', 'Pengelolaan iuran/premi/kontribusi/imbalan jasa penjaminan/kafalah', NULL, NULL),
(16, '316', 'Pengurusan klaim/manfaat pensiun', NULL, NULL),
(17, '317', 'Penilaian kerugian asuransi', NULL, NULL),
(18, '318', 'Proses pemilihan asuransi/reasuransi', NULL, NULL),
(19, '319', 'Pengelolaan surplus underwriting', NULL, NULL),
(20, '320', 'Pengelolaan data kepesertaan', NULL, NULL),
(21, '321', 'Proses subrogasi', NULL, NULL),
(22, '322', 'Pemberian jasa manajemen', NULL, NULL),
(23, '323', 'Layanan pendanaan bersama berbasis teknologi informasi', NULL, NULL),
(24, '324', 'Bullion', NULL, NULL),
(25, '325', 'Sekuritisasi', NULL, NULL),
(26, '326', 'Pendukung Pasar', NULL, NULL),
(27, '327', 'Aktivitas terkait Aset Keuangan Digital, termasuk Aset Kripto', NULL, NULL),
(28, '399', 'Aktivitas lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_jabatan`
--

CREATE TABLE `ref_jabatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_jabatan`
--

INSERT INTO `ref_jabatan` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '001', 'Direktur Utama/Ketua Pengurus', NULL, NULL),
(2, '002', 'Direktur / Pengurus', NULL, NULL),
(3, '003', 'Direktur Kepatuhan/Pengurus bidang Kepatuhan', NULL, NULL),
(4, '004', 'Komisaris Utama/Ketua Dewan Pengawas', NULL, NULL),
(5, '005', 'Komisaris/Dewan Pengawas', NULL, NULL),
(6, '006', 'Dewan Pengawas Syariah', NULL, NULL),
(7, '007', 'Pejabat Eksekutif', NULL, NULL),
(8, '008', 'Pemegang Saham Pengendali', NULL, NULL),
(9, '009', 'Pemegang Saham', NULL, NULL),
(10, '010', 'Tenaga Ahli dan Konsultan', NULL, NULL),
(11, '011', 'Komisaris Independen/Dewan Pengawas Independen', NULL, NULL),
(12, '018', 'Pejabat non Pejabat Eksekutif', NULL, NULL),
(13, '019', 'Pegawai non Pejabat', NULL, NULL),
(14, '041', 'Pensiun Karir', NULL, NULL),
(15, '042', 'Pensiun Dini/Disabilitas', NULL, NULL),
(16, '043', 'Diberhentikan atas keinginan sendiri', NULL, NULL),
(17, '044', 'Berakhir masa kontrak/penugasan', NULL, NULL),
(18, '045', 'Meninggal dunia', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_jenis_fraud`
--

CREATE TABLE `ref_jenis_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_jenis_fraud`
--

INSERT INTO `ref_jenis_fraud` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '201', 'Korupsi (Pemerasan)', NULL, NULL),
(2, '202', 'Korupsi (Benturan kepentingan…)', NULL, NULL),
(3, '203', 'Korupsi (Penyuapan)', NULL, NULL),
(4, '204', 'Korupsi (Penerimaan tidak sah)', NULL, NULL),
(5, '301', 'Penyalahgunaan aset (uang tunai)', NULL, NULL),
(6, '302', 'Penyalahgunaan aset (persediaan)', NULL, NULL),
(7, '303', 'Penyalahgunaan aset (lainnya)', NULL, NULL),
(8, '401', 'Kecurangan laporan keuangan', NULL, NULL),
(9, '501', 'Penipuan', NULL, NULL),
(10, '601', 'Pembocoran informasi rahasia', NULL, NULL),
(11, '701', 'Tindakan lain yang dapat dipersamakan dengan fraud', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_jenis_identitas`
--

CREATE TABLE `ref_jenis_identitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_jenis_identitas`
--

INSERT INTO `ref_jenis_identitas` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '001', 'KTP (Nomor Induk Kependudukan)', NULL, NULL),
(2, '002', 'Paspor (Nomor Paspor)', NULL, NULL),
(3, '003', 'NPWP (Nomor Pokok Wajib Pajak)', NULL, NULL),
(4, '009', 'Tidak Diketahui', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_kejadian_fraud`
--

CREATE TABLE `ref_kejadian_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_kejadian_fraud`
--

INSERT INTO `ref_kejadian_fraud` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'A', 'internal', NULL, NULL),
(2, 'B', 'eksternal', NULL, NULL),
(3, 'C', 'internal dan eksternal', NULL, NULL),
(4, 'AS', 'internal (Berdampak Signifikan)', NULL, NULL),
(5, 'BS', 'eksternal (Berdampak Signifikan)', NULL, NULL),
(6, 'CS', 'internal dan eksternal (Berdampak Signifikan)', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_kelemahan_fraud`
--

CREATE TABLE `ref_kelemahan_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_kelemahan_fraud`
--

INSERT INTO `ref_kelemahan_fraud` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '101', 'Sumber Daya Manusia – Integritas', NULL, NULL),
(2, '102', 'Sumber Daya Manusia – Kompetensi', NULL, NULL),
(3, '201', 'Sistem Pengendalian internal – Pengendalian internal Pimpinan', NULL, NULL),
(4, '202', 'Sistem Pengendalian internal - Pada Kebijakan internal LJK', NULL, NULL),
(5, '203', 'Sistem Pengendalian internal - Ketidaksesuaian atas Tingkat dan Toleransi Risiko', NULL, NULL),
(6, '204', 'Sistem Pengendalian internal - Pelanggaran Standar dan Prosedur LJK', NULL, NULL),
(7, '205', 'Sistem Pengendalian internal - Tidak Berjalannya Pemisahan Fungsi (Four Eyes Principle)', NULL, NULL),
(8, '206', 'Sistem Pengendalian internal - Pelaporan Keuangan dan Kegiatan Operasional yang Tidak Akurat dan Tidak Tepat Waktu', NULL, NULL),
(9, '207', 'Sistem Pengendalian internal - Struktur Organisasi yang Belum Berjalan Efektif', NULL, NULL),
(10, '301', 'Teknologi Informasi', NULL, NULL),
(11, '401', 'Penerapan Strategi Anti Fraud Belum Berjalan Efektif', NULL, NULL),
(12, '501', 'Eksternal – Kelalaian Konsumen', NULL, NULL),
(13, '502', 'Eksternal – Pemahaman Konsumen menjaga Kerahasiaan Data Pribadi', NULL, NULL),
(14, '503', 'Eksternal – Kecurangan Konsumen', NULL, NULL),
(15, '504', 'Eksternal – Kecurangan Pihak Lain', NULL, NULL),
(16, '901', 'Kelemahan Lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_lokasi_fraud`
--

CREATE TABLE `ref_lokasi_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_lokasi_fraud`
--

INSERT INTO `ref_lokasi_fraud` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '01', 'Kantor Pusat Operasional', NULL, NULL),
(2, '02', 'Kantor Pusat Non Operasional', NULL, NULL),
(3, '03', 'Kantor Cabang LJK yang berkedudukan di Luar Negeri', NULL, NULL),
(4, '04', 'Kantor Wilayah', NULL, NULL),
(5, '05', 'Kantor Cabang (Dalam Negeri)', NULL, NULL),
(6, '06', 'Kantor cabang dari bank yang berada di Luar Negeri', NULL, NULL),
(7, '07', 'Kantor Cabang Pembantu LJK yang berkedudukan di Luar Negeri', NULL, NULL),
(8, '08', 'Kantor Cabang Pembantu (Dalam Negeri)', NULL, NULL),
(9, '09', 'Kantor Cabang Pembantu (Luar Negeri)', NULL, NULL),
(10, '10', 'Kantor Kas', NULL, NULL),
(11, '11', 'Kantor Fungsional/ Kantor Selain Kantor Cabang/ Kantor Pemasaran Reksadana/Gerai/Unit Layanan (Outlet)', NULL, NULL),
(12, '12', 'Payment Point', NULL, NULL),
(13, '13', 'Kas Keliling/Kas Mobil/Kas Terapung', NULL, NULL),
(14, '14', 'Kantor Perwakilan LJK yang berkedudukan di Luar Negeri', NULL, NULL),
(15, '15', 'Automatic Teller Machine/Cash Deposit Machine/Cash Recycling Machine', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_pencegahan_fraud`
--

CREATE TABLE `ref_pencegahan_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_pencegahan_fraud`
--

INSERT INTO `ref_pencegahan_fraud` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '100', 'Sumber daya manusia', NULL, NULL),
(2, '200', 'Sistem pengendalian internal', NULL, NULL),
(3, '300', 'Teknologi informasi', NULL, NULL),
(4, '400', 'Penerapan Strategi Anti Fraud', NULL, NULL),
(5, '500', 'Koordinasi dengan asosiasi/regulator/instansi', NULL, NULL),
(6, '900', 'Tindakan lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_pihak_dirugikan`
--

CREATE TABLE `ref_pihak_dirugikan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_pihak_dirugikan`
--

INSERT INTO `ref_pihak_dirugikan` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '001', 'LJK', NULL, NULL),
(2, '002', 'Konsumen', NULL, NULL),
(3, '003', 'Pihak Lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_status_pelaku`
--

CREATE TABLE `ref_status_pelaku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_status_pelaku`
--

INSERT INTO `ref_status_pelaku` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '001', 'Pelaku Utama', NULL, NULL),
(2, '002', 'Pihak Terlibat', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_tindakan_penanganan`
--

CREATE TABLE `ref_tindakan_penanganan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_tindakan_penanganan`
--

INSERT INTO `ref_tindakan_penanganan` (`id`, `kode`, `nama`, `created_at`, `updated_at`) VALUES
(1, '01', 'Pemberian surat peringatan', NULL, NULL),
(2, '02', 'Rotasi atau mutasi', NULL, NULL),
(3, '03', 'Penurunan jabatan', NULL, NULL),
(4, '04', 'Pengunduran diri sukarela', NULL, NULL),
(5, '05', 'Pemutusan hubungan kerja', NULL, NULL),
(6, '06', 'Pemblokiran kartu debit/kartu kredit', NULL, NULL),
(7, '07', 'Pemblokiran rekening', NULL, NULL),
(8, '08', 'Penggantian kartu debit/kartu kredit', NULL, NULL),
(9, '09', 'Pelaporan kepolisian atau tindakan hukum', NULL, NULL),
(10, '10', 'Ganti rugi', NULL, NULL),
(11, '11', 'Pembatalan polis/kontrak', NULL, NULL),
(12, '12', 'Pencatatan dalam track record', NULL, NULL),
(13, '13', 'Pelaporan kepada asosiasi/regulator/instansi', NULL, NULL),
(14, '19', 'Tindakan lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1gngmuSw2EiuxtnD1PbaK6CEhdNWDtbL1Oj2oKvx', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVVViMlNWSFRpSkFUTnlISHU1ZHlmVE1UY01wNUdhOWxEQkJpTlFndyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1779247502),
('P4BzDY1d2ETVLsqcHqZRlwb38ME9oWj0JoCjAUuU', 6, '172.20.10.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR1pXdHU1WG5CRWQ3TUdHM3FjU3lZYk9kRjgzbVY1R3FpQUQ5dHpuaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xNzIuMjAuMTAuMjo4MDAwL2thc3VzLWV4cG9ydCI7czo1OiJyb3V0ZSI7czoxMjoia2FzdXMuZXhwb3J0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1779251381),
('vCK0GHswgLzVkXbEfO7uko358N6n6gT1cqDD50DU', 6, '172.20.10.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSHp4bWJCRHBxZGE4MlFUQTdnQWFiakRWVDhyN1pRZzA2bTBhck5USCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xNzIuMjAuMTAuMjo4MDAwL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1779251488);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_photo_path`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', NULL, '2026-04-12 20:25:11', '$2y$12$8qx1f3D2bVdaK94ZjtHMAuY9rbikeYisvDKukshz7YLLUNuRulQB.', 'F3JMIWALcC', '2026-04-12 20:25:12', '2026-04-12 20:25:12'),
(2, 'audrey', 'audrey@gmail.com', NULL, NULL, '$2y$12$C49Ck1cEylhLRWYFr1qn0.ERCHzeLYzzFpdC2TXtdh2s8yBi5ZX42', NULL, '2026-04-12 20:26:40', '2026-04-12 20:26:40'),
(6, 'audrey', 'sitiaudreyfalisya8@gmail.com', NULL, '2026-04-29 19:06:47', '$2y$12$8KgWcsXcByO/JVfQtWqbpeAI/UslaPPjfsJZDcipAS70DrMIYXlvO', 'mhXeBKidHZkymeevkI7sn9JRib6jPb0lYFHGBFfeXoI1ff7RYVJHVggy7skB', '2026-04-29 19:05:55', '2026-05-19 21:29:17'),
(9, 'michel', 'zaizhamichella09@gmail.com', 'profile-photos/Fmm4mdES2bzwtcMTOrRxcrRWK6FsqQmKgmBr9MGr.jpg', '2026-05-10 20:37:25', '$2y$12$n9rg59bB0BgF0f5P9aC6celerWYaMJbvl0bVtfz8z7K9Prp7v3DOS', 'R3yVSGAGY3V8D9WypRc0nUiJRixIhKDWT602kybfd1LemmQyeE1d7Mg5PQXq', '2026-05-10 19:44:06', '2026-05-13 00:50:37'),
(11, 'chel', 'kakakche09@gmail.com', NULL, '2026-05-12 18:43:04', '$2y$12$aoFqpOKu0YsBvuXdpZHOwuGMjOPk6tPQnW6rr6jKPB1DHSOEhFge.', NULL, '2026-05-12 18:42:09', '2026-05-12 18:43:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `waktu_fraud`
--

CREATE TABLE `waktu_fraud` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kasus_id` bigint(20) UNSIGNED NOT NULL,
  `waktu_awal` datetime NOT NULL,
  `waktu_akhir` datetime NOT NULL,
  `waktu_diketahui` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `waktu_fraud`
--

INSERT INTO `waktu_fraud` (`id`, `kasus_id`, `waktu_awal`, `waktu_akhir`, `waktu_diketahui`, `created_at`, `updated_at`) VALUES
(2, 2, '2026-04-15 09:01:00', '2026-04-15 09:01:00', '2026-04-15 09:01:00', '2026-04-14 19:03:59', '2026-04-14 19:03:59'),
(3, 3, '2026-04-15 09:22:00', '2026-04-15 09:22:00', '2026-04-15 09:22:00', '2026-04-14 19:24:12', '2026-04-14 19:24:12'),
(4, 4, '2026-04-15 09:28:00', '2026-04-15 09:28:00', '2026-04-15 09:28:00', '2026-04-14 19:30:45', '2026-04-14 19:30:45'),
(5, 5, '2026-04-15 09:39:00', '2026-04-15 09:39:00', '2026-04-15 09:39:00', '2026-04-14 19:41:39', '2026-04-14 19:41:39'),
(6, 6, '2026-04-15 09:45:00', '2026-04-15 09:45:00', '2026-04-15 09:45:00', '2026-04-14 19:48:10', '2026-04-14 19:48:10'),
(7, 7, '2026-04-16 08:38:00', '2026-04-16 08:38:00', '2026-04-16 08:38:00', '2026-04-15 18:40:02', '2026-04-15 18:40:02'),
(8, 8, '2026-04-28 00:00:00', '2026-04-28 00:00:00', '2026-04-28 00:00:00', '2026-04-27 19:02:47', '2026-04-28 19:05:56'),
(9, 9, '2026-04-28 00:00:00', '2026-04-28 00:00:00', '2026-04-28 00:00:00', '2026-04-27 21:47:23', '2026-04-27 21:47:23'),
(10, 10, '2026-04-01 00:00:00', '2026-04-28 00:00:00', '2026-04-15 00:00:00', '2026-04-29 19:40:02', '2026-04-29 19:40:02'),
(11, 11, '2026-04-09 00:00:00', '2026-04-13 00:00:00', '2026-04-30 00:00:00', '2026-04-29 19:59:20', '2026-04-29 19:59:20'),
(12, 12, '2026-04-07 00:00:00', '2026-04-21 00:00:00', '2026-04-30 00:00:00', '2026-04-29 21:05:27', '2026-04-29 21:05:27'),
(13, 13, '2026-05-07 00:00:00', '2026-05-07 00:00:00', '2026-05-07 00:00:00', '2026-05-06 20:23:39', '2026-05-06 20:23:39'),
(14, 14, '2026-03-18 00:00:00', '2026-05-02 00:00:00', '2026-05-04 00:00:00', '2026-05-10 20:00:17', '2026-05-10 20:00:17');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `email_otps`
--
ALTER TABLE `email_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_otps_email_index` (`email`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kasus`
--
ALTER TABLE `kasus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kasus_aktivitas_terkait_id_foreign` (`aktivitas_terkait_id`),
  ADD KEY `kasus_pihak_dirugikan_id_foreign` (`pihak_dirugikan_id`),
  ADD KEY `kasus_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `kasus_jenis_fraud`
--
ALTER TABLE `kasus_jenis_fraud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kasus_jenis_fraud_kasus_id_foreign` (`kasus_id`),
  ADD KEY `kasus_jenis_fraud_jenis_fraud_id_foreign` (`jenis_fraud_id`);

--
-- Indeks untuk tabel `kasus_kejadian_fraud`
--
ALTER TABLE `kasus_kejadian_fraud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kasus_kejadian_fraud_kasus_id_foreign` (`kasus_id`),
  ADD KEY `kasus_kejadian_fraud_kejadian_id_foreign` (`kejadian_id`);

--
-- Indeks untuk tabel `kasus_kelemahan`
--
ALTER TABLE `kasus_kelemahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kasus_kelemahan_kasus_id_foreign` (`kasus_id`),
  ADD KEY `kasus_kelemahan_kelemahan_id_foreign` (`kelemahan_id`);

--
-- Indeks untuk tabel `kasus_lokasi`
--
ALTER TABLE `kasus_lokasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kasus_lokasi_kasus_id_foreign` (`kasus_id`),
  ADD KEY `kasus_lokasi_lokasi_id_foreign` (`lokasi_id`);

--
-- Indeks untuk tabel `kasus_penanganan`
--
ALTER TABLE `kasus_penanganan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kasus_penanganan_kasus_id_foreign` (`kasus_id`),
  ADD KEY `kasus_penanganan_tindakan_id_foreign` (`tindakan_id`);

--
-- Indeks untuk tabel `kerugian_fraud`
--
ALTER TABLE `kerugian_fraud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kerugian_fraud_kasus_id_foreign` (`kasus_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pelaku_fraud`
--
ALTER TABLE `pelaku_fraud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelaku_fraud_kasus_id_foreign` (`kasus_id`),
  ADD KEY `pelaku_fraud_jenis_identitas_id_foreign` (`jenis_identitas_id`),
  ADD KEY `pelaku_fraud_status_pelaku_id_foreign` (`status_pelaku_id`),
  ADD KEY `pelaku_fraud_jabatan_saat_kejadian_id_foreign` (`jabatan_saat_kejadian_id`),
  ADD KEY `pelaku_fraud_jabatan_saat_diketahui_id_foreign` (`jabatan_saat_diketahui_id`);

--
-- Indeks untuk tabel `pencegahan_fraud`
--
ALTER TABLE `pencegahan_fraud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pencegahan_fraud_kasus_id_foreign` (`kasus_id`),
  ADD KEY `pencegahan_fraud_pencegahan_id_foreign` (`pencegahan_id`);

--
-- Indeks untuk tabel `ref_aktivitas_terkait`
--
ALTER TABLE `ref_aktivitas_terkait`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_aktivitas_terkait_kode_unique` (`kode`);

--
-- Indeks untuk tabel `ref_jabatan`
--
ALTER TABLE `ref_jabatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_jabatan_kode_unique` (`kode`);

--
-- Indeks untuk tabel `ref_jenis_fraud`
--
ALTER TABLE `ref_jenis_fraud`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_jenis_identitas`
--
ALTER TABLE `ref_jenis_identitas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_jenis_identitas_kode_unique` (`kode`);

--
-- Indeks untuk tabel `ref_kejadian_fraud`
--
ALTER TABLE `ref_kejadian_fraud`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_kelemahan_fraud`
--
ALTER TABLE `ref_kelemahan_fraud`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_lokasi_fraud`
--
ALTER TABLE `ref_lokasi_fraud`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_pencegahan_fraud`
--
ALTER TABLE `ref_pencegahan_fraud`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_pihak_dirugikan`
--
ALTER TABLE `ref_pihak_dirugikan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_pihak_dirugikan_kode_unique` (`kode`);

--
-- Indeks untuk tabel `ref_status_pelaku`
--
ALTER TABLE `ref_status_pelaku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_status_pelaku_kode_unique` (`kode`);

--
-- Indeks untuk tabel `ref_tindakan_penanganan`
--
ALTER TABLE `ref_tindakan_penanganan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `waktu_fraud`
--
ALTER TABLE `waktu_fraud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `waktu_fraud_kasus_id_foreign` (`kasus_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `email_otps`
--
ALTER TABLE `email_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kasus`
--
ALTER TABLE `kasus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kasus_jenis_fraud`
--
ALTER TABLE `kasus_jenis_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `kasus_kejadian_fraud`
--
ALTER TABLE `kasus_kejadian_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `kasus_kelemahan`
--
ALTER TABLE `kasus_kelemahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `kasus_lokasi`
--
ALTER TABLE `kasus_lokasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `kasus_penanganan`
--
ALTER TABLE `kasus_penanganan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `kerugian_fraud`
--
ALTER TABLE `kerugian_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `pelaku_fraud`
--
ALTER TABLE `pelaku_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `pencegahan_fraud`
--
ALTER TABLE `pencegahan_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `ref_aktivitas_terkait`
--
ALTER TABLE `ref_aktivitas_terkait`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `ref_jabatan`
--
ALTER TABLE `ref_jabatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `ref_jenis_fraud`
--
ALTER TABLE `ref_jenis_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `ref_jenis_identitas`
--
ALTER TABLE `ref_jenis_identitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `ref_kejadian_fraud`
--
ALTER TABLE `ref_kejadian_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `ref_kelemahan_fraud`
--
ALTER TABLE `ref_kelemahan_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `ref_lokasi_fraud`
--
ALTER TABLE `ref_lokasi_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `ref_pencegahan_fraud`
--
ALTER TABLE `ref_pencegahan_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `ref_pihak_dirugikan`
--
ALTER TABLE `ref_pihak_dirugikan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `ref_status_pelaku`
--
ALTER TABLE `ref_status_pelaku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ref_tindakan_penanganan`
--
ALTER TABLE `ref_tindakan_penanganan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `waktu_fraud`
--
ALTER TABLE `waktu_fraud`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kasus`
--
ALTER TABLE `kasus`
  ADD CONSTRAINT `kasus_aktivitas_terkait_id_foreign` FOREIGN KEY (`aktivitas_terkait_id`) REFERENCES `ref_aktivitas_terkait` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_pihak_dirugikan_id_foreign` FOREIGN KEY (`pihak_dirugikan_id`) REFERENCES `ref_pihak_dirugikan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kasus_jenis_fraud`
--
ALTER TABLE `kasus_jenis_fraud`
  ADD CONSTRAINT `kasus_jenis_fraud_jenis_fraud_id_foreign` FOREIGN KEY (`jenis_fraud_id`) REFERENCES `ref_jenis_fraud` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_jenis_fraud_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kasus_kejadian_fraud`
--
ALTER TABLE `kasus_kejadian_fraud`
  ADD CONSTRAINT `kasus_kejadian_fraud_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_kejadian_fraud_kejadian_id_foreign` FOREIGN KEY (`kejadian_id`) REFERENCES `ref_kejadian_fraud` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kasus_kelemahan`
--
ALTER TABLE `kasus_kelemahan`
  ADD CONSTRAINT `kasus_kelemahan_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_kelemahan_kelemahan_id_foreign` FOREIGN KEY (`kelemahan_id`) REFERENCES `ref_kelemahan_fraud` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kasus_lokasi`
--
ALTER TABLE `kasus_lokasi`
  ADD CONSTRAINT `kasus_lokasi_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_lokasi_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `ref_lokasi_fraud` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kasus_penanganan`
--
ALTER TABLE `kasus_penanganan`
  ADD CONSTRAINT `kasus_penanganan_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kasus_penanganan_tindakan_id_foreign` FOREIGN KEY (`tindakan_id`) REFERENCES `ref_tindakan_penanganan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kerugian_fraud`
--
ALTER TABLE `kerugian_fraud`
  ADD CONSTRAINT `kerugian_fraud_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pelaku_fraud`
--
ALTER TABLE `pelaku_fraud`
  ADD CONSTRAINT `pelaku_fraud_jabatan_saat_diketahui_id_foreign` FOREIGN KEY (`jabatan_saat_diketahui_id`) REFERENCES `ref_jabatan` (`id`),
  ADD CONSTRAINT `pelaku_fraud_jabatan_saat_kejadian_id_foreign` FOREIGN KEY (`jabatan_saat_kejadian_id`) REFERENCES `ref_jabatan` (`id`),
  ADD CONSTRAINT `pelaku_fraud_jenis_identitas_id_foreign` FOREIGN KEY (`jenis_identitas_id`) REFERENCES `ref_jenis_identitas` (`id`),
  ADD CONSTRAINT `pelaku_fraud_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pelaku_fraud_status_pelaku_id_foreign` FOREIGN KEY (`status_pelaku_id`) REFERENCES `ref_status_pelaku` (`id`);

--
-- Ketidakleluasaan untuk tabel `pencegahan_fraud`
--
ALTER TABLE `pencegahan_fraud`
  ADD CONSTRAINT `pencegahan_fraud_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pencegahan_fraud_pencegahan_id_foreign` FOREIGN KEY (`pencegahan_id`) REFERENCES `ref_pencegahan_fraud` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `waktu_fraud`
--
ALTER TABLE `waktu_fraud`
  ADD CONSTRAINT `waktu_fraud_kasus_id_foreign` FOREIGN KEY (`kasus_id`) REFERENCES `kasus` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
