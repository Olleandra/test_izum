-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Авг 22 2016 г., 06:32
-- Версия сервера: 5.6.17
-- Версия PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `test_izum`
--

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE IF NOT EXISTS `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `sort_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `sort_name`) VALUES
(1, 'Яблоки', 'Голден'),
(2, 'Яблоки', 'Свежий урожай'),
(3, 'Груши', 'Калифорнийские'),
(4, 'Яблоки', 'Золотце'),
(5, 'Апельсины', 'Сладкие'),
(6, 'Груши', 'Русские, компотные');

-- --------------------------------------------------------

--
-- Структура таблицы `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица профилей пользователей' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `name`) VALUES
(1, 1, 'Широкий профиль. Для Админа специально!'),
(2, 2, 'Профиль чуть более узкий, но не менее примечательный'),
(3, 2, 'Ещё один обычный профиль');

-- --------------------------------------------------------

--
-- Структура таблицы `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT 'Какой товар на складе',
  `count` int(11) NOT NULL COMMENT 'Сколько',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Статус',
  `login` varchar(60) NOT NULL COMMENT 'Логин',
  `name` varchar(100) NOT NULL COMMENT 'ФИО',
  `email` varchar(60) NOT NULL COMMENT 'E-mail',
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL COMMENT 'Роль',
  `created_at` int(11) NOT NULL COMMENT 'Добавлен',
  `updated_at` int(11) NOT NULL COMMENT 'Обновлён',
  `last_visit` int(11) NOT NULL COMMENT 'Последний вход',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица пользователей' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `status`, `login`, `name`, `email`, `password_hash`, `auth_key`, `role`, `created_at`, `updated_at`, `last_visit`) VALUES
(1, 1, 'admin', 'Администратор', 'admin@admin.ru', '$2y$13$oGK2tHFBm/IDCmEuf8bZEe27UEnaxLDxlw.q.OAVA4I2EZzRDIJsW', 'LBEB7H9pzx5SBv_MlrVyFGpEY1JQmbaq', 'root', 1471611982, 1471611982, 0),
(2, 1, 'user', 'Обычный Пользователь О''бычинский', 'user@yandex.ru', '$2y$13$nci19kRjens2l.lus8E9oOrJzJ8te4ifI0tHcCE0SE0eQfuNUBGmy', '1mBOK-y24JEOMdFyhlD0N67wGM84Pt-m', 'user', 1471612191, 1471612206, 0),
(3, 0, 'user2', 'Второй пользователь', 'user2@gmail.com', '$2y$13$ueV3rPK3FlMLeVVOqeoN.uEV5CrWcjSRgwaAI/VQHfxE6Qhamn6LW', 'Oyct7G-hzDEdo4azc4pxsaBYhzpUzLFJ', 'user', 1471613608, 1471613608, 0);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
