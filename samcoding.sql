-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 02, 2025 at 05:29 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `samcoding`
--

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `problem_id` int NOT NULL,
  `language` varchar(50) NOT NULL,
  `code` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `runTime` varchar(45) NOT NULL,
  `memory` varchar(45) NOT NULL,
  `tests_passed` int NOT NULL,
  `total_tests` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attempts`
--

INSERT INTO `attempts` (`id`, `user_id`, `problem_id`, `language`, `code`, `status`, `runTime`, `memory`, `tests_passed`, `total_tests`, `created_at`) VALUES
(1, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '40', '59980', 10, 53, '2025-09-28 19:49:55'),
(2, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '34', '60404', 53, 53, '2025-09-29 15:54:00'),
(3, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '34', '60376', 53, 53, '2025-09-29 15:54:18'),
(4, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '40', '64588', 53, 53, '2025-09-29 15:54:22'),
(5, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '39', '62312', 53, 53, '2025-09-29 15:56:22'),
(6, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '40', '62268', 53, 53, '2025-09-29 16:09:48'),
(7, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '39', '63300', 53, 53, '2025-09-29 16:15:50'),
(8, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '40', '62200', 53, 53, '2025-09-29 16:26:06'),
(9, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a - b)', 'Wrong Answer', '40', '61492', 1, 53, '2025-09-29 16:26:23'),
(10, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a -b)', 'Wrong Answer', '40', '61780', 1, 53, '2025-09-29 16:34:58'),
(11, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a -b)', 'Wrong Answer', '42', '61968', 1, 53, '2025-09-29 16:35:25'),
(12, 1, 1, 'python3', '.then(data => {\r\n    const overlay = document.getElementById(\'loadingOverlay\');\r\n    if (overlay) overlay.remove();\r\n\r\n    const loadRow = document.getElementById(\'loadingRow\');\r\n    if (loadRow) loadRow.remove();\r\n\r\n    submitBtn.disabled = false;\r\n    submitBtn.textContent = \'Yechimni yuborish\';\r\n\r\n    if (data.success) {\r\n        // muvaffaqiyatli bo‘lsa — hech narsa chiqmasin\r\n        setTimeout(() => {\r\n            location.reload();\r\n        }, 1500);\r\n    } else {\r\n        // faqat xatolik bo‘lsa toast chiqaramiz\r\n        if (typeof Swal !== \'undefined\') {\r\n            const Toast = Swal.mixin({\r\n                toast: true,\r\n                position: \'top-end\',\r\n                showConfirmButton: false,\r\n                timer: 3000,\r\n                timerProgressBar: true\r\n            });\r\n            Toast.fire({\r\n                icon: \'error\',\r\n                title: data.message\r\n            });\r\n        } else {\r\n            // fallback oddiy console yoki alert\r\n            console.error(\"❌ \" + data.message);\r\n        }\r\n    }\r\n})', 'Runtime Error', '42', '61420', 0, 53, '2025-09-29 16:35:45'),
(13, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '40', '62160', 53, 53, '2025-09-29 16:39:22'),
(14, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '38', '62056', 53, 53, '2025-09-29 16:40:51'),
(15, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '40', '61732', 53, 53, '2025-09-29 16:41:41'),
(16, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '39', '61864', 53, 53, '2025-09-29 16:43:31'),
(17, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '42', '62380', 53, 53, '2025-09-29 17:01:34'),
(18, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a - b)', 'Wrong Answer', '40', '63028', 1, 53, '2025-09-29 17:02:30'),
(19, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a - b)', 'Wrong Answer', '40', '63240', 1, 53, '2025-09-29 17:03:24'),
(20, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a - )', 'Runtime Error', '40', '63392', 0, 53, '2025-09-29 17:03:57'),
(21, 1, 1, 'python3', 'a, b = map(int, input().split())\r\nprint(a + b)', 'Accept', '42', '63856', 53, 53, '2025-09-29 18:44:49'),
(22, 1, 8, 'python3', '#include<iostream> \r\nusing namespace std; \r\nint main(){\r\n   int a, b;\r\n   cin>>a>>b;\r\ncout<<a+b;\r\nreturn 0;\r\n}\r\n', 'Runtime Error', '40', '56444', 0, 53, '2025-09-29 23:37:43'),
(23, 1, 8, 'cpp', '#include<iostream> \r\nusing namespace std; \r\nint main(){\r\n   int a, b;\r\n   cin>>a>>b;\r\ncout<<a+b;\r\nreturn 0;\r\n}\r\n', 'Accept', '15', '58888', 53, 53, '2025-09-29 23:38:33'),
(24, 1, 8, 'cpp', '#include<iostream> \r\nusing namespace std; \r\nint main(){\r\n   int a, b;\r\n   cin>>a>>b;\r\ncout<<a+b;\r\nreturn 0;\r\n}\r\n', 'Accept', '17', '62028', 53, 53, '2025-09-30 00:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `attempt_results`
--

CREATE TABLE `attempt_results` (
  `id` int NOT NULL,
  `attempt_id` int NOT NULL,
  `input` text,
  `code_output` text,
  `correct_output` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `descript` text NOT NULL,
  `input_format` text,
  `output_format` text,
  `constraints` text,
  `difficulty` int DEFAULT '0',
  `category` varchar(100) DEFAULT NULL,
  `izoh` varchar(255) NOT NULL DEFAULT 'Yo''q',
  `time_limit` int DEFAULT '1000',
  `memory_limit` int DEFAULT '16384',
  `author_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`id`, `title`, `descript`, `input_format`, `output_format`, `constraints`, `difficulty`, `category`, `izoh`, `time_limit`, `memory_limit`, `author_id`, `created_at`, `update_at`) VALUES
(1, 'A+B\r\n', 'Sizga ikkita natural son beriladi. ularning yig‘indisini hisoblash kerak.\r\n\r\n', 'Kirish oqimida ikkita butun son, A va B beriladi. Har ikkala son ham 10^9 dan kichik.\r\n\r\n', 'Berilgan ikkita sonning yig‘indisini ekranga chiqaring.\r\n\r\n', '-10^9 < A, B < 10^9', 1, 'Oson', 'Python dasturlash tilida ushbu masalani yechish uchun e\'tibor bering: ikkita son bitta qatorda kiritiladi. Shu sababli, int(input()) buyrug‘idan foydalanish noto‘g‘ri bo‘lishi mumkin.', 1000, 16384, 1, '2025-09-29 16:59:35', '2025-09-29 16:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int NOT NULL,
  `problem_id` int NOT NULL,
  `input` text NOT NULL,
  `output` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `problem_id`, `input`, `output`) VALUES
(1, 5, '373 179', '552'),
(2, 5, '456 252', '708'),
(3, 5, '869 873', '1742'),
(4, 5, '122 290', '412'),
(5, 5, '993 874', '1867'),
(6, 5, '481 536', '1017'),
(7, 5, '922 607', '1529'),
(8, 5, '972 549', '1521'),
(9, 5, '242 245', '487'),
(10, 5, '388 156', '544'),
(11, 5, '259 547', '806'),
(12, 5, '827 120', '947'),
(13, 5, '825 622', '1447'),
(14, 5, '455 190', '645'),
(15, 5, '793 31', '824'),
(16, 5, '188 648', '836'),
(17, 5, '668 302', '970'),
(18, 5, '270 600', '870'),
(19, 5, '430 455', '885'),
(20, 5, '846 94', '940'),
(21, 5, '22 14', '36'),
(22, 5, '92 387', '479'),
(23, 5, '294 416', '710'),
(24, 5, '768 382', '1150'),
(25, 5, '686 239', '925'),
(26, 5, '400 559', '959'),
(27, 5, '494 283', '777'),
(28, 5, '677 581', '1258'),
(29, 5, '168 8', '176'),
(30, 5, '240 753', '993'),
(31, 5, '728 565', '1293'),
(32, 5, '0 180', '180'),
(33, 5, '138 569', '707'),
(34, 5, '417 914', '1331'),
(35, 5, '152 614', '766'),
(36, 5, '880 422', '1302'),
(37, 5, '813 613', '1426'),
(38, 5, '990 982', '1972'),
(39, 5, '606 210', '816'),
(40, 5, '399 803', '1202'),
(41, 5, '754 791', '1545'),
(42, 5, '897 976', '1873'),
(43, 5, '597 231', '828'),
(44, 5, '171 336', '507'),
(45, 5, '953 854', '1807'),
(46, 5, '419 566', '985'),
(47, 5, '6 849', '855'),
(48, 5, '848 512', '1360'),
(49, 5, '916 692', '1608'),
(50, 5, '109 70', '179'),
(51, 5, '1 2', '3'),
(52, 5, '4 5', '9'),
(53, 5, '0 0', '0'),
(54, 6, '373 179', '552'),
(55, 6, '456 252', '708'),
(56, 6, '869 873', '1742'),
(57, 6, '122 290', '412'),
(58, 6, '993 874', '1867'),
(59, 6, '481 536', '1017'),
(60, 6, '922 607', '1529'),
(61, 6, '972 549', '1521'),
(62, 6, '242 245', '487'),
(63, 6, '388 156', '544'),
(64, 6, '259 547', '806'),
(65, 6, '827 120', '947'),
(66, 6, '825 622', '1447'),
(67, 6, '455 190', '645'),
(68, 6, '793 31', '824'),
(69, 6, '188 648', '836'),
(70, 6, '668 302', '970'),
(71, 6, '270 600', '870'),
(72, 6, '430 455', '885'),
(73, 6, '846 94', '940'),
(74, 6, '22 14', '36'),
(75, 6, '92 387', '479'),
(76, 6, '294 416', '710'),
(77, 6, '768 382', '1150'),
(78, 6, '686 239', '925'),
(79, 6, '400 559', '959'),
(80, 6, '494 283', '777'),
(81, 6, '677 581', '1258'),
(82, 6, '168 8', '176'),
(83, 6, '240 753', '993'),
(84, 6, '728 565', '1293'),
(85, 6, '0 180', '180'),
(86, 6, '138 569', '707'),
(87, 6, '417 914', '1331'),
(88, 6, '152 614', '766'),
(89, 6, '880 422', '1302'),
(90, 6, '813 613', '1426'),
(91, 6, '990 982', '1972'),
(92, 6, '606 210', '816'),
(93, 6, '399 803', '1202'),
(94, 6, '754 791', '1545'),
(95, 6, '897 976', '1873'),
(96, 6, '597 231', '828'),
(97, 6, '171 336', '507'),
(98, 6, '953 854', '1807'),
(99, 6, '419 566', '985'),
(100, 6, '6 849', '855'),
(101, 6, '848 512', '1360'),
(102, 6, '916 692', '1608'),
(103, 6, '109 70', '179'),
(104, 6, '1 2', '3'),
(105, 6, '4 5', '9'),
(106, 6, '0 0', '0'),
(107, 1, '373 179', '552'),
(108, 1, '456 252', '708'),
(109, 1, '869 873', '1742'),
(110, 1, '122 290', '412'),
(111, 1, '993 874', '1867'),
(112, 1, '481 536', '1017'),
(113, 1, '922 607', '1529'),
(114, 1, '972 549', '1521'),
(115, 1, '242 245', '487'),
(116, 1, '388 156', '544'),
(117, 1, '259 547', '806'),
(118, 1, '827 120', '947'),
(119, 1, '825 622', '1447'),
(120, 1, '455 190', '645'),
(121, 1, '793 31', '824'),
(122, 1, '188 648', '836'),
(123, 1, '668 302', '970'),
(124, 1, '270 600', '870'),
(125, 1, '430 455', '885'),
(126, 1, '846 94', '940'),
(127, 1, '22 14', '36'),
(128, 1, '92 387', '479'),
(129, 1, '294 416', '710'),
(130, 1, '768 382', '1150'),
(131, 1, '686 239', '925'),
(132, 1, '400 559', '959'),
(133, 1, '494 283', '777'),
(134, 1, '677 581', '1258'),
(135, 1, '168 8', '176'),
(136, 1, '240 753', '993'),
(137, 1, '728 565', '1293'),
(138, 1, '0 180', '180'),
(139, 1, '138 569', '707'),
(140, 1, '417 914', '1331'),
(141, 1, '152 614', '766'),
(142, 1, '880 422', '1302'),
(143, 1, '813 613', '1426'),
(144, 1, '990 982', '1972'),
(145, 1, '606 210', '816'),
(146, 1, '399 803', '1202'),
(147, 1, '754 791', '1545'),
(148, 1, '897 976', '1873'),
(149, 1, '597 231', '828'),
(150, 1, '171 336', '507'),
(151, 1, '953 854', '1807'),
(152, 1, '419 566', '985'),
(153, 1, '6 849', '855'),
(154, 1, '848 512', '1360'),
(155, 1, '916 692', '1608'),
(156, 1, '109 70', '179'),
(157, 1, '1 2', '3'),
(158, 1, '4 5', '9'),
(159, 1, '0 0', '0'),
(160, 7, '373 179', '552'),
(161, 7, '456 252', '708'),
(162, 7, '869 873', '1742'),
(163, 7, '122 290', '412'),
(164, 7, '993 874', '1867'),
(165, 7, '481 536', '1017'),
(166, 7, '922 607', '1529'),
(167, 7, '972 549', '1521'),
(168, 7, '242 245', '487'),
(169, 7, '388 156', '544'),
(170, 7, '259 547', '806'),
(171, 7, '827 120', '947'),
(172, 7, '825 622', '1447'),
(173, 7, '455 190', '645'),
(174, 7, '793 31', '824'),
(175, 7, '188 648', '836'),
(176, 7, '668 302', '970'),
(177, 7, '270 600', '870'),
(178, 7, '430 455', '885'),
(179, 7, '846 94', '940'),
(180, 7, '22 14', '36'),
(181, 7, '92 387', '479'),
(182, 7, '294 416', '710'),
(183, 7, '768 382', '1150'),
(184, 7, '686 239', '925'),
(185, 7, '400 559', '959'),
(186, 7, '494 283', '777'),
(187, 7, '677 581', '1258'),
(188, 7, '168 8', '176'),
(189, 7, '240 753', '993'),
(190, 7, '728 565', '1293'),
(191, 7, '0 180', '180'),
(192, 7, '138 569', '707'),
(193, 7, '417 914', '1331'),
(194, 7, '152 614', '766'),
(195, 7, '880 422', '1302'),
(196, 7, '813 613', '1426'),
(197, 7, '990 982', '1972'),
(198, 7, '606 210', '816'),
(199, 7, '399 803', '1202'),
(200, 7, '754 791', '1545'),
(201, 7, '897 976', '1873'),
(202, 7, '597 231', '828'),
(203, 7, '171 336', '507'),
(204, 7, '953 854', '1807'),
(205, 7, '419 566', '985'),
(206, 7, '6 849', '855'),
(207, 7, '848 512', '1360'),
(208, 7, '916 692', '1608'),
(209, 7, '109 70', '179'),
(210, 7, '1 2', '3'),
(211, 7, '4 5', '9'),
(212, 7, '0 0', '0'),
(213, 8, '373 179', '552'),
(214, 8, '456 252', '708'),
(215, 8, '869 873', '1742'),
(216, 8, '122 290', '412'),
(217, 8, '993 874', '1867'),
(218, 8, '481 536', '1017'),
(219, 8, '922 607', '1529'),
(220, 8, '972 549', '1521'),
(221, 8, '242 245', '487'),
(222, 8, '388 156', '544'),
(223, 8, '259 547', '806'),
(224, 8, '827 120', '947'),
(225, 8, '825 622', '1447'),
(226, 8, '455 190', '645'),
(227, 8, '793 31', '824'),
(228, 8, '188 648', '836'),
(229, 8, '668 302', '970'),
(230, 8, '270 600', '870'),
(231, 8, '430 455', '885'),
(232, 8, '846 94', '940'),
(233, 8, '22 14', '36'),
(234, 8, '92 387', '479'),
(235, 8, '294 416', '710'),
(236, 8, '768 382', '1150'),
(237, 8, '686 239', '925'),
(238, 8, '400 559', '959'),
(239, 8, '494 283', '777'),
(240, 8, '677 581', '1258'),
(241, 8, '168 8', '176'),
(242, 8, '240 753', '993'),
(243, 8, '728 565', '1293'),
(244, 8, '0 180', '180'),
(245, 8, '138 569', '707'),
(246, 8, '417 914', '1331'),
(247, 8, '152 614', '766'),
(248, 8, '880 422', '1302'),
(249, 8, '813 613', '1426'),
(250, 8, '990 982', '1972'),
(251, 8, '606 210', '816'),
(252, 8, '399 803', '1202'),
(253, 8, '754 791', '1545'),
(254, 8, '897 976', '1873'),
(255, 8, '597 231', '828'),
(256, 8, '171 336', '507'),
(257, 8, '953 854', '1807'),
(258, 8, '419 566', '985'),
(259, 8, '6 849', '855'),
(260, 8, '848 512', '1360'),
(261, 8, '916 692', '1608'),
(262, 8, '109 70', '179'),
(263, 8, '1 2', '3'),
(264, 8, '4 5', '9'),
(265, 8, '0 0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(64) NOT NULL,
  `otm` varchar(100) NOT NULL,
  `course` int NOT NULL,
  `phone` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(12) NOT NULL DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `otm`, `course`, `phone`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'Xakimov Allamurod', 'samcoding', 'SamDU', 4, '998938554640', 'xakimov@gmail.com', 'admin', 'dfc84f3df8212534a9ecc0dfce41ea15', '2025-09-28 15:11:20'),
(2, 'Dilnoza Karimova', 'dilnoza_k', 'TATU', 2, '998902223344', 'dilnoza@example.com', 'user', '202cb962ac59075b964b07152d234b70', '2025-09-28 15:11:20'),
(3, 'Javohir Ismoilov', 'javohir99', 'NUU', 3, '998903334455', 'javohir@example.com', 'user', 'e10adc3949ba59abbe56e057f20f883e', '2025-09-28 15:11:20'),
(4, 'Malika Rustamova', 'malika_r', 'SamDU', 4, '998904445566', 'malika@example.com', 'user', 'e10adc3949ba59abbe56e057f20f883e', '2025-09-28 15:11:20'),
(5, 'Sardor Abdullayev', 'sardor_a', 'TATU', 2, '998905556677', 'sardor@example.com', 'user', 'e10adc3949ba59abbe56e057f20f883e', '2025-09-28 15:11:20'),
(6, 'Allamurod Xakimov', 'admin2', 'samdu', 4, '938554640', 'ozodbekmardonov@gmail.com', 'user', 'c4ca4238a0b923820dcc509a6f75849b', '2025-09-29 17:28:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attempt_results`
--
ALTER TABLE `attempt_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `attempt_results`
--
ALTER TABLE `attempt_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
