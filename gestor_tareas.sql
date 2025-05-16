-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2025 a las 22:48:15
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
-- Base de datos: `gestor_tareas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaboradores`
--

CREATE TABLE `colaboradores` (
  `id` int(11) NOT NULL,
  `id_tarea` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tarea` int(11) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subtareas`
--

CREATE TABLE `subtareas` (
  `id` int(11) NOT NULL,
  `id_tarea` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('Definido','En proceso','Completada') DEFAULT 'Definido',
  `prioridad` enum('Baja','Normal','Alta') DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `asignado_es_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` enum('Baja','Normal','Alta') DEFAULT 'Normal',
  `estado` enum('Definido','En proceso','Completada') DEFAULT 'Definido',
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_recordatorio` date DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `archivada` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `id_usuario`, `asunto`, `descripcion`, `prioridad`, `estado`, `fecha_vencimiento`, `fecha_recordatorio`, `color`, `archivada`, `created_at`, `updated_at`) VALUES
(10, 4, 'sdasdasd', 'asda', 'Baja', '', '2025-05-15', '2025-05-14', NULL, 0, '2025-05-09 02:16:49', '2025-05-09 02:16:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `username` varchar(255) NOT NULL,
  `es_admin` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `username`, `es_admin`) VALUES
(4, 'Enzo Rodriguez', 'enzorodriguez.078@gmail.com', '$2y$10$a.Fb4Ekq8TD0PulI5Ntvnuhas874XkvpAnijcB0DCs8k5wiSyDj0W', 'usuario', 'enchoto', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_tarea` (`id_tarea`);

--
-- Indices de la tabla `subtareas`
--
ALTER TABLE `subtareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`),
  ADD KEY `id_responsable` (`id_responsable`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subtareas`
--
ALTER TABLE `subtareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD CONSTRAINT `colaboradores_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `colaboradores_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificaciones_ibfk_2` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `subtareas`
--
ALTER TABLE `subtareas`
  ADD CONSTRAINT `subtareas_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subtareas_ibfk_2` FOREIGN KEY (`id_responsable`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
