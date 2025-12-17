-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 09 2025 г., 23:10
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `minigry`
--

-- --------------------------------------------------------

--
-- Структура таблицы `games_results`
--

CREATE TABLE `games_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `time` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `games_results`
--

INSERT INTO `games_results` (`id`, `user_id`, `game`, `score`, `time`, `date`) VALUES
(2, 2, 'tictactoe', 10, NULL, '2025-12-09 22:02:33'),
(3, 2, 'target', 441, NULL, '2025-12-09 22:04:09'),
(4, 3, 'tictactoe', 10, NULL, '2025-12-09 22:04:55'),
(5, 3, 'tictactoe', 10, NULL, '2025-12-09 22:04:59'),
(6, 3, 'target', 360, NULL, '2025-12-09 22:05:33'),
(7, 3, 'snake', 18, NULL, '2025-12-09 22:06:06'),
(8, 3, 'clicker', 116, NULL, '2025-12-09 22:06:23');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(100) DEFAULT 'default.png',
  `score` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `avatar`, `score`, `created_at`) VALUES
(1, 'TestPlayer', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default.png', 150, '2025-12-09 21:35:40'),
(2, '123', '$2y$10$KIk9McVz47N.vhAARg1cQupjYn.eKE5gHUTp40GpvUzdBKp6Jj6/i', 'default.png', 451, '2025-12-09 21:41:09'),
(3, 'ArtemSemenko', '$2y$10$NUbRJDUL18cebkzDc55QUO65cq61a3Rk6FgUTZIOuQQmlblXRflve', 'default.png', 514, '2025-12-09 22:04:37');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `games_results`
--
ALTER TABLE `games_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_game` (`user_id`,`game`),
  ADD KEY `idx_score` (`score`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `games_results`
--
ALTER TABLE `games_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `games_results`
--
ALTER TABLE `games_results`
  ADD CONSTRAINT `games_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
