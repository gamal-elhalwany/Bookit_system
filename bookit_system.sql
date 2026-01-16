-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 10:30 PM
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
-- Database: `bookit_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_13_154244_create_personal_access_tokens_table', 2),
(6, '2026_01_13_184349_add_verification_code_to_users_table', 3),
(8, '2026_01_15_145359_create_restaurants_table', 4),
(9, '2026_01_15_204108_create_subscriptions_table', 5),
(10, '2026_01_16_203806_add_nar_column_to_restaurantus', 6),
(11, '2026_01_15_210647_create_password_otps_table', 7),
(12, '2026_01_15_212805_add_business_type_to_restaurants_table', 8),
(13, '2026_01_15_215133_add_foreign_key_to_restaurants_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_otps`
--

CREATE TABLE `password_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_otps`
--

INSERT INTO `password_otps` (`id`, `email`, `otp`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'mostafafara04@gmail.com', '650083', '2026-01-15 19:28:45', '2026-01-15 19:18:46', '2026-01-15 19:18:46'),
(2, 'mostafafara04@gmail.com', '585570', '2026-01-15 19:29:26', '2026-01-15 19:19:26', '2026-01-15 19:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'e39aaa62a1dc789b2ac33393078141d29ce7e6d54766455c11d7006b67b3208d', '[\"*\"]', NULL, NULL, '2026-01-13 15:23:44', '2026-01-13 15:23:44'),
(2, 'App\\Models\\User', 1, 'auth_token', '3f0b7682699df652fa6e20698389200d43887af321d21be8b35aa20740fbc419', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:30', '2026-01-13 15:25:30'),
(3, 'App\\Models\\User', 1, 'auth_token', '959e05fd3953c951f430b30e6007bcb808c08074d548bd58f2470dd225c0bbdb', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:31', '2026-01-13 15:25:31'),
(4, 'App\\Models\\User', 1, 'auth_token', '5fb019f6da15b789507b5474aabab42a27ccf78baf08732be6f6474c3bc9cc17', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:32', '2026-01-13 15:25:32'),
(5, 'App\\Models\\User', 1, 'auth_token', 'db32c0085327d8a2afabbcc63ce84a04ce11802da661a0fadff1d866c01901f9', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:33', '2026-01-13 15:25:33'),
(6, 'App\\Models\\User', 1, 'auth_token', '7a3efa8a488127bfe606ed0e3a9a13df118208ef5784b0f9008f2481543d7898', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:33', '2026-01-13 15:25:33'),
(7, 'App\\Models\\User', 1, 'auth_token', 'c4b4217a1635bf165621a6eba7c4deb13f0be251794f565512d6df9a68cb4135', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:34', '2026-01-13 15:25:34'),
(8, 'App\\Models\\User', 1, 'auth_token', '428c237365f318f54d642d558ab0e3498c561668f020ea7c8789779e59536eaf', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:35', '2026-01-13 15:25:35'),
(9, 'App\\Models\\User', 1, 'auth_token', 'de488cabd2d5186daf2547b10505d289fee740063179b1960cc56137b631e35b', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:36', '2026-01-13 15:25:36'),
(10, 'App\\Models\\User', 1, 'auth_token', '927aabaf8648450ed67129c6f0cc84feb9bde22d1f60b88d1146b33dbcdb11ea', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:37', '2026-01-13 15:25:37'),
(11, 'App\\Models\\User', 1, 'auth_token', '6c93a04ee3116837257c116be8a51f37a233340cdcab13eb73e65f7a49efcbac', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:38', '2026-01-13 15:25:38'),
(12, 'App\\Models\\User', 1, 'auth_token', 'dc6f41a221be88e3bd2225d7c4fbbc853e67ad818b181eb6b559ff6502f6b416', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:38', '2026-01-13 15:25:38'),
(13, 'App\\Models\\User', 1, 'auth_token', '2eae76da56472160d50f05f5102a33de73c739f176ca686662f53d39ff657985', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:39', '2026-01-13 15:25:39'),
(14, 'App\\Models\\User', 1, 'auth_token', '5972e7dbacedee22d9b4454d679e75c476b651739b451bdb15b3b125ab58263d', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:40', '2026-01-13 15:25:40'),
(15, 'App\\Models\\User', 1, 'auth_token', '664e88b6d073239b903b751e30aaec97af4f777e6ea8c3b912152a52fb07f7f5', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:40', '2026-01-13 15:25:40'),
(16, 'App\\Models\\User', 1, 'auth_token', '9731311f6fa7a3b2b80bdb13756a0a6bce9a5a64245123195e34adb09bdf448e', '[\"*\"]', NULL, NULL, '2026-01-13 15:25:41', '2026-01-13 15:25:41'),
(17, 'App\\Models\\User', 1, 'auth_token', '3c5e90c83575a61caa70f823e24de1c91e2ad603f641cd3db46b2a43f292fb4a', '[\"*\"]', NULL, NULL, '2026-01-13 16:19:53', '2026-01-13 16:19:53'),
(18, 'App\\Models\\User', 1, 'auth_token', 'a729c63cdaac10f47556c8377ce5b2db4c356f0d587caf3b5e95457137b183ff', '[\"*\"]', NULL, NULL, '2026-01-13 16:19:55', '2026-01-13 16:19:55'),
(19, 'App\\Models\\User', 1, 'auth_token', '2818cfc27a300c2a8f190923da5cc8643265269509aeccbe2abb3d78261d90f7', '[\"*\"]', NULL, NULL, '2026-01-13 16:19:56', '2026-01-13 16:19:56'),
(20, 'App\\Models\\User', 1, 'auth_token', 'db45c9bc1729c6af2148ea754e6c30195fa75a100ad1f596c435134479cb77bc', '[\"*\"]', NULL, NULL, '2026-01-13 16:19:57', '2026-01-13 16:19:57'),
(21, 'App\\Models\\User', 3, 'authToken', 'ae55f7641d4822802d2cca9b326ef7f0e02091673abe0f3bed8785893987e63a', '[\"*\"]', NULL, NULL, '2026-01-13 16:37:33', '2026-01-13 16:37:33'),
(22, 'App\\Models\\User', 3, 'auth_token', 'a98dbecb078cc33a699a03af656eb5d0fb5c393a001d7be69cc1a6d7c30ae244', '[\"*\"]', NULL, NULL, '2026-01-13 16:38:38', '2026-01-13 16:38:38'),
(23, 'App\\Models\\User', 3, 'auth_token', '3b78e1d5f3e332392300c54d889593668b744592997e3f05d6a3f10479864f6a', '[\"*\"]', NULL, NULL, '2026-01-13 16:38:39', '2026-01-13 16:38:39'),
(24, 'App\\Models\\User', 8, 'authToken', 'bdcc1a8b8579d8469aa26a6057fa95bf2a3a46dc83a73eb76299a8526072f1e2', '[\"*\"]', NULL, NULL, '2026-01-13 18:16:33', '2026-01-13 18:16:33'),
(25, 'App\\Models\\User', 9, 'authToken', 'aad0ee3d76be82d345cdc3bf6ea5a1b7c5958b304d6b06018e9883e7ede5982a', '[\"*\"]', NULL, NULL, '2026-01-14 11:41:08', '2026-01-14 11:41:08'),
(26, 'App\\Models\\User', 10, 'authToken', '3cb0c203301e0c0782f749bbf49c6367afbcc4558c4ad07b5fcc43d076a19895', '[\"*\"]', NULL, NULL, '2026-01-14 12:14:22', '2026-01-14 12:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rate` decimal(3,2) NOT NULL DEFAULT 0.00,
  `subscription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `business_type` enum('restaurant','cafe') NOT NULL DEFAULT 'restaurant',
  `subscription_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `user_id`, `address`, `phone`, `email`, `image`, `opening_time`, `closing_time`, `created_at`, `updated_at`, `rate`, `subscription_id`, `business_type`, `subscription_end_date`) VALUES
(1, 'مطعم البرنس', 1, 'شارع التحرير', '01012345678', 'elprince@example.com', 'uploads/restaurants/elprince.jpg', '09:00:00', '23:00:00', '2026-01-15 20:21:45', '2026-01-15 20:21:45', 4.50, 2, 'restaurant', '2026-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1UgNCIPsuIqEGq4OT9XfRDNYSIxOaQUdMGcZlk4V', NULL, '127.0.0.1', 'PostmanRuntime/7.51.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVpidkltOXlIRTlGclJVdnZaV2pkNjV2TlVHSW85VURIVmZ2dzBpNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpEY05PWjA0dzhXemxScUxlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768515534),
('2VobEyu3hgSq0zdD42KdN6fVT6a77NKV8cdtT8DL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2pIVHN2dE01TFhKZ203OGlITG5BbDRKcTdFZ2hyUEVhNkdGV0xYTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpRb3JhMW5qOW12OEx0d3ZwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768399186),
('7H6cxlfmHxO1QUpZSkFH5ULbnL5Oe8PFxYa89n1z', NULL, '127.0.0.1', 'PostmanRuntime/7.51.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidmltSWJaNWpnbTIwcUZtZGVmMkV4ZmdkS2h1eExQdW85eUVLZUNQQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvcmVzdGF1cmFudHMiO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6anVUSFdCZ2pWUmZZdHZDYyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768323934),
('8IP42H2C5mJiell2xAEnl0xwZBMBgZw3n65MRXBR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibDRYVHJUbUdvZnhPOXZwcGZhdmd1SGJkNjFNaGx1TTN5VjJWMGZYTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjozZEpuOTF0RTNRaTJIYldvIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768494210),
('fCFfvrYybEPazXhaUH4EDRklrZKLTp213WYsxl0a', NULL, '127.0.0.1', 'PostmanRuntime/7.51.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHppY01pUzVKTXVlRTlUOWdxT05YVDg5bElWVlB6TloyTzJQRUZjZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjozZEpuOTF0RTNRaTJIYldvIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768492844),
('h3yC3Fi3OhgNJbze2e12JNWNDijrwxjTNbwHpFp1', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibnhjVk9ldDB4NHJzZ0dxbjNRTmpOcDBHOUFXakpFRkxtbnYxb2xGRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1768324701),
('mWdTYng6vwypWuKiHZYd6kfKvATzXFiRaWiW4Mrq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWY4WDFoODM4a2RZZmxVM1Vsa0FFSFVoNkV6YWJNZmhMa3Nka05kUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjp2cGdYcUE2Y2d6bjh3dVRKIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768324696);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `name`, `description`, `price`, `duration_days`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'الباقة الأساسية', 'باقة لمدة شهر واحد', 100.00, 30, 1, '2026-01-15 19:34:35', '2026-01-15 19:34:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `verification_code`) VALUES
(1, 'Mostafa', 'mostafa@22', NULL, '$2y$12$vaH4isaglfxIkWTDZ5DZf.UHsdA3dLCwaQ808ZjorS9FaviQShgP6', NULL, '2026-01-11 12:31:35', '2026-01-11 12:31:35', NULL),
(2, 'mostafa@22', 'mostafa@2025', NULL, '$2y$12$zfplgyUwH.jFc4yp/tlIKeF94xOMPiav3qZiOpwm4GVDSiXBHLe62', NULL, '2026-01-13 15:09:16', '2026-01-13 15:09:16', NULL),
(7, 'Mostafa', 'mostafafara2@gmail.com', NULL, '$2y$12$nwlDib1Wx2TWH.aveZ/PMeUkeyLq3piEghetnbQCFrMM/96XYbpiq', NULL, '2026-01-13 18:14:17', '2026-01-13 18:14:17', '245106'),
(10, 'MO', 'mostafafara04@gmail.com', '2026-01-14 12:14:22', '$2y$12$SW0Ltvr7hsCvdtLjFsQz7uNfYpNDD/XHepj5AqHVdvYJn6cXggpL6', NULL, '2026-01-14 12:13:44', '2026-01-14 12:14:22', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_otps`
--
ALTER TABLE `password_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurants_user_id_foreign` (`user_id`),
  ADD KEY `restaurants_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `password_otps`
--
ALTER TABLE `password_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `restaurants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
