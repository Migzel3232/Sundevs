-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-07-2024 a las 20:24:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sundevs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `priority_level`
--

CREATE TABLE `priority_level` (
  `ID_Level` int(11) NOT NULL,
  `Level` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `priority_level`
--

INSERT INTO `priority_level` (`ID_Level`, `Level`) VALUES
(1, 'Lowest'),
(2, 'Very Low'),
(3, 'Low'),
(4, 'Below Average'),
(5, 'Average'),
(6, 'Above Average'),
(7, 'High'),
(8, 'Very high'),
(9, 'Highest'),
(10, 'Critical');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE `status` (
  `ID_Status` int(11) NOT NULL,
  `Status` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `status`
--

INSERT INTO `status` (`ID_Status`, `Status`) VALUES
(1, 'To_Do'),
(2, 'Inprocess'),
(3, 'Finished');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `Task_Name` varchar(20) NOT NULL,
  `Start_Date` datetime DEFAULT NULL,
  `End_Date` datetime DEFAULT NULL,
  `FK_Status` int(11) NOT NULL,
  `Priority_Level` int(60) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `ID_Task` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tasks`
--

INSERT INTO `tasks` (`Task_Name`, `Start_Date`, `End_Date`, `FK_Status`, `Priority_Level`, `Description`, `ID_Task`) VALUES
('hola', '2024-07-26 19:25:00', '2024-07-26 20:25:00', 1, 1, 's', 11),
('Tarea sena ', '2024-07-26 20:16:00', '2024-08-08 21:16:00', 3, 10, 'Esta tarea es importante  para mi desarrollo ', 18);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `priority_level`
--
ALTER TABLE `priority_level`
  ADD PRIMARY KEY (`ID_Level`);

--
-- Indices de la tabla `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`ID_Status`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`ID_Task`),
  ADD KEY `FK_Status` (`FK_Status`),
  ADD KEY `FK_Priority` (`Priority_Level`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `priority_level`
--
ALTER TABLE `priority_level`
  MODIFY `ID_Level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `status`
--
ALTER TABLE `status`
  MODIFY `ID_Status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `ID_Task` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `FK_Priority` FOREIGN KEY (`Priority_Level`) REFERENCES `priority_level` (`ID_Level`),
  ADD CONSTRAINT `FK_Status` FOREIGN KEY (`FK_Status`) REFERENCES `status` (`ID_Status`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
