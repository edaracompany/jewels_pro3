-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 17 نوفمبر 2025 الساعة 22:07
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jawaherest`
--

-- --------------------------------------------------------

--
-- بنية الجدول `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `descrip` varchar(400) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `about`
--

INSERT INTO `about` (`id`, `title`, `descrip`, `image`) VALUES
(1, 'ارث من ابداعنا', 'تأسس مصنع جواهر في عام 1985 بمهمة واحدة: تقديم أجود أنواع المجوهرات والطلاء بلمسة عصرية تجمع بين الأصالة والحداثة. نحن نؤمن بأن الجمال الحقيقي يكمن في التفاصيل الدقيقة والتصميم المبتكر.\r\n\r\nمنذ تأسيسنا، حرصنا على استخدام أفضل المواد وأحدث تقنيات التصنيع، مع الالتزام بأعلى معايير الجودة والدقة. فريق التصميم لدينا يتكون من مصممين مبدعين يجمعون بين الخبرة التقليدية والإبداع المعاصر.', 'uploads/691a17041b4fe_1763317508.png'),
(2, 'ارث من ابداعنا', 'تأسس مصنع جواهر في عام 1985 بمهمة واحدة: تقديم أجود أنواع المجوهرات والطلاء بلمسة عصرية تجمع بين الأصالة والحداثة. نحن نؤمن بأن الجمال الحقيقي يكمن في التفاصيل الدقيقة والتصميم المبتكر.\r\n\r\nمنذ تأسيسنا، حرصنا على استخدام أفضل المواد وأحدث تقنيات التصنيع، مع الالتزام بأعلى معايير الجودة والدقة. فريق التصميم لدينا يتكون من مصممين مبدعين يجمعون بين الخبرة التقليدية والإبداع المعاصر.', 'uploads/691a17041b4fe_1763317508.png');

-- --------------------------------------------------------

--
-- بنية الجدول `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `email`, `created_at`) VALUES
(2, 'admin', '$2y$10$T0rPWRPB.WSL6sZ2/kyn1OQ0AJPxpvYSWbvx7LjMvG7P8Rj2XL30K', 'مدير النظام', 'admin@jawaher.com', '2025-11-14 19:46:35'),
(3, 'هندي', '$2y$10$ZjIpa2CjV6qb25cbi1lUVu6iFoUNql0AmdmPcjgFm0klzpFeQXB.W', 'مدير النظام', 'admin@jawaher.com', '2025-11-14 20:43:00');

-- --------------------------------------------------------

--
-- بنية الجدول `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `opinions` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `customer`
--

INSERT INTO `customer` (`id`, `name`, `opinions`) VALUES
(1, 'omar', 'جيد جدا');

-- --------------------------------------------------------

--
-- بنية الجدول `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gmail` varchar(255) NOT NULL,
  `phon` int(10) NOT NULL,
  `jop` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `file` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `job`
--

INSERT INTO `job` (`id`, `name`, `gmail`, `phon`, `jop`, `message`, `cv_file`, `file`) VALUES
(2, 'omar ahmad', 'omardeveloper2007@gmail.com', 106891731, 'عامل', 'اود التوظيف', 'uploads/cv/6918be4e3478c_1763229262.pdf', ''),
(3, 'omar ahmad', 'omardeveloper2007@gmail.com', 106891731, 'عامل', 'يييي', 'uploads/cv/691a1755d29d6_1763317589.pdf', '');

-- --------------------------------------------------------

--
-- بنية الجدول `manufacturing`
--

CREATE TABLE `manufacturing` (
  `id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `descrip` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `manufacturing`
--

INSERT INTO `manufacturing` (`id`, `image`, `title`, `descrip`) VALUES
(2, 'uploads/6918b065c7cc2_1763225701.jpg', 'التصميم الرقمي', 'نبدأ برسم التصاميم الأولية باستخدام أحدث برامج التصميم ثلاثية الأبعاد، مع مراعاة أحدث صيحات الموضة ومتطلبات العملاء.'),
(3, 'uploads/6918b086d2b70_1763225734.jpg', 'نبدأ برسم التصاميم ', 'نبدأ برسم التصاميم الأولية باستخدام أحدث برامج التصميم ثلاثية الأبعاد، مع مراعاة أحدث صيحات الموضة ومتطلبات العملاء.');

-- --------------------------------------------------------

--
-- بنية الجدول `partnerships`
--

CREATE TABLE `partnerships` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `partnerships`
--

INSERT INTO `partnerships` (`id`, `title`, `image`) VALUES
(5, 'SAB', 'uploads/6918a9f83a04b_1763224056.jpg'),
(6, 'برج بالطيور', 'uploads/6918ab02cf93a_1763224322.jpg'),
(7, 'crowne', 'uploads/6918ac88865c0_1763224712.jpg'),
(8, 'دله', 'uploads/6918ac9f2a363_1763224735.jpg'),
(9, 'ارامكو', 'uploads/6918acb11a7d1_1763224753.jpg');

-- --------------------------------------------------------

--
-- بنية الجدول `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `descrip` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `services`
--

INSERT INTO `services` (`id`, `image`, `title`, `descrip`) VALUES
(2, 'uploads/6918afe072ebd_1763225568.jpg', 'القص بالليزر والبلازما', 'القص بالليزر والبلازما هما تقنيتان متطورتان تستخدمان في قطع المواد المعدنية وغير المعدنية بدقة عالية وسرعة فائقة.'),
(3, 'uploads/6918b0042e34f_1763225604.jpg', 'القص بليزر', 'القص بالليزر والبلازما هما تقنيتان متطورتان تستخدمان في قطع المواد المعدنية وغير المعدنية بدقة عالية وسرعة فائقة.');

-- --------------------------------------------------------

--
-- بنية الجدول `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(255) NOT NULL,
  `site_description` text NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_title`, `site_description`, `contact_email`, `contact_phone`, `updated_at`) VALUES
(1, 'جواهر', 'شركة رائدة في مجال التصنيع', 'info@jawaher.com', '+966500000000', '2025-11-14 19:50:08');

-- --------------------------------------------------------

--
-- بنية الجدول `tech`
--

CREATE TABLE `tech` (
  `id` int(11) NOT NULL,
  `descrip` varchar(200) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `works`
--

CREATE TABLE `works` (
  `id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `descrip` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturing`
--
ALTER TABLE `manufacturing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partnerships`
--
ALTER TABLE `partnerships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tech`
--
ALTER TABLE `tech`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `manufacturing`
--
ALTER TABLE `manufacturing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `partnerships`
--
ALTER TABLE `partnerships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tech`
--
ALTER TABLE `tech`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
