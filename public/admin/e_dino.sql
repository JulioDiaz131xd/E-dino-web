-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2024 a las 07:14:50
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `e_dino`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `codigo` varchar(8) NOT NULL,
  `creador_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id`, `nombre`, `descripcion`, `codigo`, `creador_id`, `fecha_creacion`) VALUES
(1, 'Matematicas', 'dfdf', '440adfc9', 7, '2024-10-10 04:49:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases_usuarios`
--

CREATE TABLE `clases_usuarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `clase_id` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases_usuarios`
--

INSERT INTO `clases_usuarios` (`id`, `usuario_id`, `clase_id`, `fecha_inscripcion`) VALUES
(1, 1, 33, '2024-09-07 19:11:52'),
(2, 1, 34, '2024-09-07 19:12:57'),
(3, 1, 35, '2024-09-07 19:48:35'),
(4, 1, 36, '2024-09-07 23:51:38'),
(5, 2, 36, '2024-09-07 23:53:02'),
(6, 2, 34, '2024-09-08 16:55:36'),
(7, 3, 37, '2024-09-08 21:43:14'),
(8, 1, 37, '2024-09-08 21:43:47'),
(9, 4, 38, '2024-09-09 00:13:31'),
(10, 1, 38, '2024-09-09 00:15:53'),
(11, 5, 36, '2024-09-13 01:46:04'),
(12, 7, 39, '2024-10-10 01:45:50'),
(13, 7, 1, '2024-10-10 04:49:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `clase_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales_clase`
--

CREATE TABLE `materiales_clase` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `clase_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `valor` decimal(10,2) NOT NULL,
  `fecha_limite` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso_clases`
--

CREATE TABLE `progreso_clases` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `clase_id` int(11) NOT NULL,
  `progreso` int(11) DEFAULT 0,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `respuesta` text NOT NULL,
  `es_correcta` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'maestro'),
(2, 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `numero_control` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol_id`, `numero_control`, `fecha_registro`) VALUES
(1, 'Ramon Julio', 'prepajulio131@gmail.com', '$2y$10$41WcNvZwxw43NazndRGFLOXmQOc9Pg7BU0kJkiN5NCtAEdDgytH3O', 2, NULL, '2024-09-07 19:06:42'),
(2, 'Jessi', 'jessi@gmail.com', '$2y$10$fV4P1TDUeMtPDVNZml2oYeCEt4wRmHb9qt3ZBFfKIo4jp2jiLCm2G', 2, NULL, '2024-09-07 23:52:56'),
(3, 'admin', 'admin@gmail.com', '$2y$10$rKGmRgOp47EMUYvSxc3cV.2OO66r9wrcP1a/XK88g7zhoIfRE0Qqa', 1, NULL, '2024-09-08 21:42:58'),
(4, 'Dani', 'dani@gmail.com', '$2y$10$WkiE8dONI2OQVtOIzwm4B.hDejPu0TrbvUtuIu.pH09St5HqMi57G', 1, NULL, '2024-09-09 00:12:52'),
(5, 'Panecillo', 'panecillo@gmail.com', '$2y$10$nTFgm.sAui68RT4huCAnUOOSIN9Mcj4kXC9TE893Sl1P09rtEBcSq', 2, NULL, '2024-09-13 01:45:58'),
(6, 'test', 'test@gmail.com', '$2y$10$ielOlThtdVLlzIXn7/QtG./uZi2bb3NvVPmKm3F5dm3YCGq/GJfG6', 2, NULL, '2024-10-09 03:04:03'),
(7, 'adm', 'adm@gmail.com', '$2y$10$faTyy1HXATuM8wRNQSZjOe5RENOnGeH5k9kdjdzKMn6StvSiXOIuC', 1, NULL, '2024-10-10 01:44:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clases_usuarios`
--
ALTER TABLE `clases_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `clase_id` (`clase_id`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clase_id` (`clase_id`);

--
-- Indices de la tabla `materiales_clase`
--
ALTER TABLE `materiales_clase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clase_id` (`clase_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`);

--
-- Indices de la tabla `progreso_clases`
--
ALTER TABLE `progreso_clases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `clase_id` (`clase_id`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `numero_control` (`numero_control`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clases_usuarios`
--
ALTER TABLE `clases_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `materiales_clase`
--
ALTER TABLE `materiales_clase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `progreso_clases`
--
ALTER TABLE `progreso_clases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clases_usuarios`
--
ALTER TABLE `clases_usuarios`
  ADD CONSTRAINT `clases_usuarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `clases_usuarios_ibfk_2` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `materiales_clase`
--
ALTER TABLE `materiales_clase`
  ADD CONSTRAINT `materiales_clase_ibfk_1` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `progreso_clases`
--
ALTER TABLE `progreso_clases`
  ADD CONSTRAINT `progreso_clases_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `progreso_clases_ibfk_2` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;
