-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2025 a las 16:23:49
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
-- Base de datos: `registro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `cod_ciudad` varchar(2) NOT NULL,
  `descripcion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`cod_ciudad`, `descripcion`) VALUES
('BA', 'Barranquilla'),
('BO', 'Bogotá'),
('CA', 'Cali'),
('CU', 'Cúcuta'),
('ME', 'Medellín');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada_institucion`
--

CREATE TABLE `entrada_institucion` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `jornada` enum('mañana','tarde') NOT NULL,
  `tipo_entrada` enum('normal','tardanza','ausente') DEFAULT 'normal',
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrada_institucion`
--

INSERT INTO `entrada_institucion` (`id`, `id_user`, `fecha_entrada`, `hora_entrada`, `jornada`, `tipo_entrada`, `observaciones`, `fecha_registro`) VALUES
(4, 2, '2025-07-27', '19:22:56', 'tarde', 'normal', NULL, '2025-07-28 00:22:56'),
(5, 1, '2025-07-27', '19:23:15', 'tarde', 'normal', NULL, '2025-07-28 00:23:15'),
(6, 3, '2025-07-28', '08:28:09', 'tarde', 'tardanza', NULL, '2025-07-28 13:28:09'),
(7, 1, '2025-07-28', '08:28:30', 'tarde', 'tardanza', NULL, '2025-07-28 13:28:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `cod_evento` varchar(6) NOT NULL,
  `foto_evento` varchar(150) DEFAULT NULL,
  `nom_evento` varchar(50) DEFAULT NULL,
  `descripcion` varchar(70) DEFAULT NULL,
  `duracion` varchar(20) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `hora` time(4) DEFAULT NULL,
  `aforo_max` int(3) DEFAULT NULL,
  `ubicacion` varchar(30) DEFAULT NULL,
  `qr` varchar(100) DEFAULT NULL,
  `tipo_jornada` varchar(6) DEFAULT NULL,
  `materia` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`cod_evento`, `foto_evento`, `nom_evento`, `descripcion`, `duracion`, `fecha_inicio`, `fecha_final`, `hora`, `aforo_max`, `ubicacion`, `qr`, `tipo_jornada`, `materia`) VALUES
('EVT001', '', 'Olimpiadas Matemáticas', 'Competencia de resolución de problemas', '4 horas', '2023-11-15', '2023-11-15', '08:00:00.0000', 100, 'Auditorio Principal', 'uploads/qr/eventos/evento_EVT001_20250728015230.png', 'MAÑANA', 'MA'),
('EVT002', NULL, 'Feria Científica', 'Exposición de proyectos científicos', '6 horas', '2023-11-20', '2023-11-20', '10:00:00.0000', 150, 'Cancha Deportiva', 'uploads/qr/eventos/evento_EVT002_20250728015229.png', 'TARDE', 'CI'),
('EVT003', NULL, 'Taller de Historia', 'Charla sobre historia colonial', '2 horas', '2023-11-25', '2023-11-25', '15:00:00.0000', 50, 'Sala de Conferencias', 'uploads/qr/eventos/evento_EVT003_20250728015228.png', 'TARDE', 'HI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado`
--

CREATE TABLE `grado` (
  `cod_grado` int(2) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`cod_grado`, `descripcion`) VALUES
(61, 'Sexto 1'),
(62, 'Sexto 2'),
(63, 'Sexto 3'),
(64, 'Sexto 4'),
(65, 'Sexto 5'),
(71, 'Séptimo 1'),
(72, 'Séptimo 2'),
(73, 'Séptimo 3'),
(74, 'Séptimo 4'),
(75, 'Séptimo 5'),
(81, 'Octavo 1'),
(82, 'Octavo 2'),
(83, 'Octavo 3'),
(84, 'Octavo 4'),
(85, 'Octavo 5'),
(91, 'Noveno 1'),
(92, 'Noveno 2'),
(93, 'Noveno 3'),
(94, 'Noveno 4'),
(95, 'Noveno 5'),
(101, 'Décimo 1'),
(102, 'Décimo 2'),
(103, 'Décimo 3'),
(104, 'Décimo 4'),
(105, 'Décimo 5'),
(111, 'Undécimo 1'),
(112, 'Undécimo 2'),
(113, 'Undécimo 3'),
(114, 'Undécimo 4'),
(115, 'Undécimo 5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `cod_categoria` varchar(2) NOT NULL,
  `descripcion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`cod_categoria`, `descripcion`) VALUES
('AR', 'arte'),
('CI', 'Ciencias'),
('ES', 'Español'),
('HI', 'Historia'),
('IN', 'Inglés'),
('MA', 'Matemáticas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('nuevo','leido','respondido','archivado') DEFAULT 'nuevo',
  `respuesta` text DEFAULT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `respondido_por` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respuesta`, `fecha_respuesta`, `respondido_por`) VALUES
(1, 'Juan Pérez', 'juan@email.com', '3001234567', 'Información General', 'Hola, me gustaría obtener información sobre los eventos disponibles para estudiantes. ¿Podrían enviarme más detalles?', '2025-07-28 00:46:13', 'respondido', NULL, NULL, NULL),
(2, 'María Rodríguez', 'maria@email.com', '3009876543', 'Soporte Técnico', 'Tengo problemas para acceder al sistema. Mi usuario no funciona correctamente. Necesito ayuda urgente.', '2025-07-28 00:46:13', 'nuevo', NULL, NULL, NULL),
(3, 'Carlos Gómez', 'carlos@email.com', '3005551234', 'Eventos', '¿Cuándo será el próximo evento de matemáticas? Mi hijo está muy interesado en participar.', '2025-07-28 00:46:13', 'leido', NULL, NULL, NULL),
(4, 'Ana López', 'ana@email.com', '3007778888', 'Inscripciones', 'Quisiera saber cómo inscribir a mi hija en el taller de programación. ¿Cuáles son los requisitos?', '2025-07-28 00:46:13', 'nuevo', NULL, NULL, NULL),
(5, 'Luis Martínez', 'luis@email.com', '3001112222', 'Sugerencias', 'Excelente sistema. Sugiero agregar más eventos de ciencias y tecnología. Sería muy beneficioso para los estudiantes.', '2025-07-28 00:46:13', 'archivado', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante`
--

CREATE TABLE `participante` (
  `id_user` int(10) NOT NULL,
  `cod_evento` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participante`
--

INSERT INTO `participante` (`id_user`, `cod_evento`) VALUES
(1, 'EVT001'),
(2, 'EVT001'),
(3, 'EVT002'),
(4, 'EVT003'),
(5, 'EVT002');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_user` int(10) NOT NULL,
  `foto_persona` varchar(150) DEFAULT NULL,
  `nom_user` varchar(150) DEFAULT NULL,
  `ciudad` varchar(2) DEFAULT NULL,
  `telef_user` int(9) DEFAULT NULL,
  `correo_user` varchar(30) DEFAULT NULL,
  `contrasena_user` varchar(10) DEFAULT NULL,
  `cod_grado` int(2) DEFAULT NULL,
  `grupo` int(1) DEFAULT NULL,
  `cc_acudiente` int(10) DEFAULT NULL,
  `nom_acudiente` varchar(50) DEFAULT NULL,
  `telef_acudiente` int(10) DEFAULT NULL,
  `telef_acudiente_dos` int(10) DEFAULT NULL,
  `correo_acudiente` varchar(50) DEFAULT NULL,
  `tipo_persona` varchar(3) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL,
  `codigo_qr` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_user`, `foto_persona`, `nom_user`, `ciudad`, `telef_user`, `correo_user`, `contrasena_user`, `cod_grado`, `grupo`, `cc_acudiente`, `nom_acudiente`, `telef_acudiente`, `telef_acudiente_dos`, `correo_acudiente`, `tipo_persona`, `fecha_creacion`, `fecha_edicion`, `codigo_qr`) VALUES
(0, NULL, 'carlos', NULL, NULL, 'carlitos@mail.com', 'carlos12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'EST', NULL, NULL, NULL),
(1, 'http://localhost/img/juan.jpg', 'Juan Pérez', 'BO', 311111111, 'juan@mail.com', 'est123', 61, 1, 100, 'María Rodríguez', 312345678, 300111111, 'maria@mail.com', 'EST', '2025-07-28 08:30:33', '2025-07-27 18:24:18', 'uploads/qr/personas/sexto1/persona_1_20250728015157.png'),
(2, NULL, 'Sofía Gómez', 'ME', 322222222, 'sofia@mail.com', 'est456', 62, 2, 101, 'Carlos Gómez', 315555555, 300222222, 'carlos@mail.com', 'EST', NULL, NULL, 'uploads/qr/personas/sexto2/persona_2_20250728015158.png'),
(3, NULL, 'Miguel Ruiz', 'CA', 333333333, 'miguel@mail.com', 'est789', 63, 3, 102, 'Laura Pérez', 318888888, 300333333, 'laura@mail.com', 'EST', NULL, NULL, 'uploads/qr/personas/sexto3/persona_3_20250728015159.png'),
(4, NULL, 'Valentina Castro', 'BA', 344444444, 'valentina@mail.com', 'est012', 64, 4, 103, 'Pedro Martínez', 320111111, 300444444, 'pedro@mail.com', 'EST', NULL, NULL, 'uploads/qr/personas/sexto4/persona_4_20250728015200.png'),
(5, NULL, 'Andrés Morales', 'CU', 355555555, 'andres@mail.com', 'est345', 65, 5, 104, 'Ana López', 322222222, 300555555, 'ana@mail.com', 'EST', NULL, NULL, 'uploads/qr/personas/sexto5/persona_5_20250728015201.png'),
(100, NULL, 'María Rodríguez', 'BO', 312345678, 'pass123', 'pass123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACU', '2025-07-27 17:49:38', NULL, NULL),
(101, NULL, 'Carlos Gómez', 'ME', 315555555, NULL, 'pass456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACU', NULL, NULL, NULL),
(102, NULL, 'Laura Pérez', 'CA', 318888888, NULL, 'pass789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACU', NULL, NULL, NULL),
(103, NULL, 'Pedro Martínez', 'BA', 320111111, NULL, 'pass000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACU', NULL, NULL, NULL),
(104, NULL, 'Ana López', 'CU', 322222222, NULL, 'pass111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACU', NULL, NULL, NULL),
(200, NULL, 'Profesor Carlos', 'BO', 366666666, 'carlos@escuela.com', 'doc123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DOC', '2025-07-27 17:42:04', NULL, NULL),
(201, NULL, 'Profesora Ana', 'ME', 377777777, 'ana@escuela.com', 'doc456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DOC', NULL, NULL, NULL),
(202, NULL, 'Profesor Luis', 'CA', 388888888, 'luis@escuela.com', 'doc789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DOC', '2025-07-27 18:55:54', NULL, NULL),
(300, NULL, 'Admin Pedro', 'BO', 399999999, 'pedro@escuela.com', 'adm123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADM', '2025-07-28 08:27:38', NULL, NULL),
(301, NULL, 'Admin Laura', 'ME', 310000000, 'laura@escuela.com', 'adm456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADM', NULL, NULL, NULL),
(1000, NULL, 'Administrador Test', NULL, NULL, 'admin@test.com', '123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADM', NULL, NULL, NULL),
(1001, NULL, 'Docente Test', NULL, NULL, 'docente@test.com', '123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DOC', NULL, NULL, NULL),
(1002, NULL, 'Estudiante Test', NULL, NULL, 'estudiante@test.com', '123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'EST', NULL, NULL, NULL),
(1003, NULL, 'Acudiente Test', NULL, NULL, 'acudiente@test.com', '123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACU', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_evento`
--

CREATE TABLE `personal_evento` (
  `cod_evento` varchar(6) NOT NULL,
  `id_user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal_evento`
--

INSERT INTO `personal_evento` (`cod_evento`, `id_user`) VALUES
('EVT001', 200),
('EVT001', 300),
('EVT002', 201),
('EVT003', 202);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_participante`
--

CREATE TABLE `registro_participante` (
  `cod_evento` varchar(6) NOT NULL,
  `id_user` int(10) NOT NULL,
  `fecha_llegada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hora_salida` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_participante`
--

INSERT INTO `registro_participante` (`cod_evento`, `id_user`, `fecha_llegada`, `hora_salida`) VALUES
('EVT001', 1, '2023-11-15 12:55:00', NULL),
('EVT001', 2, '2023-11-15 12:58:00', NULL),
('EVT002', 3, '2023-11-20 14:50:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_jornada`
--

CREATE TABLE `tipo_jornada` (
  `cod_jornada` varchar(6) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_jornada`
--

INSERT INTO `tipo_jornada` (`cod_jornada`, `descripcion`) VALUES
('COMPL', 'Jornada Completa'),
('MAÑANA', 'Jornada Mañana'),
('NOCHE', 'Jornada Nocturna'),
('TARDE', 'Jornada Tarde');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_persona`
--

CREATE TABLE `tipo_persona` (
  `cod_tipo` varchar(3) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_persona`
--

INSERT INTO `tipo_persona` (`cod_tipo`, `descripcion`) VALUES
('ACU', 'Acudiente'),
('ADM', 'Administrativo'),
('DOC', 'Docente'),
('EST', 'Estudiante');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_asuntos_populares`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_asuntos_populares` (
`asunto` varchar(100)
,`cantidad` bigint(21)
,`porcentaje` decimal(26,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_estadisticas_contacto`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_estadisticas_contacto` (
`fecha` date
,`total_mensajes` bigint(21)
,`mensajes_nuevos` decimal(22,0)
,`mensajes_respondidos` decimal(22,0)
,`mensajes_leidos` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_estadisticas_entrada`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_estadisticas_entrada` (
`fecha` date
,`total_entradas` bigint(21)
,`entradas_normales` decimal(22,0)
,`tardanzas` decimal(22,0)
,`ausentes` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_asuntos_populares`
--
DROP TABLE IF EXISTS `v_asuntos_populares`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_asuntos_populares`  AS SELECT `mensajes_contacto`.`asunto` AS `asunto`, count(0) AS `cantidad`, round(count(0) * 100.0 / (select count(0) from `mensajes_contacto`),2) AS `porcentaje` FROM `mensajes_contacto` GROUP BY `mensajes_contacto`.`asunto` ORDER BY count(0) DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_estadisticas_contacto`
--
DROP TABLE IF EXISTS `v_estadisticas_contacto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_estadisticas_contacto`  AS SELECT cast(`mensajes_contacto`.`fecha_envio` as date) AS `fecha`, count(0) AS `total_mensajes`, sum(case when `mensajes_contacto`.`estado` = 'nuevo' then 1 else 0 end) AS `mensajes_nuevos`, sum(case when `mensajes_contacto`.`estado` = 'respondido' then 1 else 0 end) AS `mensajes_respondidos`, sum(case when `mensajes_contacto`.`estado` = 'leido' then 1 else 0 end) AS `mensajes_leidos` FROM `mensajes_contacto` GROUP BY cast(`mensajes_contacto`.`fecha_envio` as date) ORDER BY cast(`mensajes_contacto`.`fecha_envio` as date) DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_estadisticas_entrada`
--
DROP TABLE IF EXISTS `v_estadisticas_entrada`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_estadisticas_entrada`  AS SELECT cast(`entrada_institucion`.`fecha_entrada` as date) AS `fecha`, count(0) AS `total_entradas`, sum(case when `entrada_institucion`.`tipo_entrada` = 'normal' then 1 else 0 end) AS `entradas_normales`, sum(case when `entrada_institucion`.`tipo_entrada` = 'tardanza' then 1 else 0 end) AS `tardanzas`, sum(case when `entrada_institucion`.`tipo_entrada` = 'ausente' then 1 else 0 end) AS `ausentes` FROM `entrada_institucion` GROUP BY cast(`entrada_institucion`.`fecha_entrada` as date) ORDER BY cast(`entrada_institucion`.`fecha_entrada` as date) DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`cod_ciudad`);

--
-- Indices de la tabla `entrada_institucion`
--
ALTER TABLE `entrada_institucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_user` (`id_user`),
  ADD KEY `idx_fecha_entrada` (`fecha_entrada`),
  ADD KEY `idx_jornada` (`jornada`),
  ADD KEY `idx_tipo_entrada` (`tipo_entrada`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`cod_evento`),
  ADD KEY `tipo_jornada` (`tipo_jornada`),
  ADD KEY `materia` (`materia`);

--
-- Indices de la tabla `grado`
--
ALTER TABLE `grado`
  ADD PRIMARY KEY (`cod_grado`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`cod_categoria`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_fecha_envio` (`fecha_envio`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_asunto` (`asunto`);

--
-- Indices de la tabla `participante`
--
ALTER TABLE `participante`
  ADD PRIMARY KEY (`id_user`,`cod_evento`),
  ADD KEY `cod_evento` (`cod_evento`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `ciudad` (`ciudad`),
  ADD KEY `tipo_persona` (`tipo_persona`),
  ADD KEY `cod_grado` (`cod_grado`);

--
-- Indices de la tabla `personal_evento`
--
ALTER TABLE `personal_evento`
  ADD PRIMARY KEY (`cod_evento`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `registro_participante`
--
ALTER TABLE `registro_participante`
  ADD PRIMARY KEY (`cod_evento`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `tipo_jornada`
--
ALTER TABLE `tipo_jornada`
  ADD PRIMARY KEY (`cod_jornada`);

--
-- Indices de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD PRIMARY KEY (`cod_tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `entrada_institucion`
--
ALTER TABLE `entrada_institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entrada_institucion`
--
ALTER TABLE `entrada_institucion`
  ADD CONSTRAINT `entrada_institucion_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `persona` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`tipo_jornada`) REFERENCES `tipo_jornada` (`cod_jornada`),
  ADD CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`materia`) REFERENCES `materias` (`cod_categoria`);

--
-- Filtros para la tabla `participante`
--
ALTER TABLE `participante`
  ADD CONSTRAINT `participante_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `persona` (`id_user`),
  ADD CONSTRAINT `participante_ibfk_2` FOREIGN KEY (`cod_evento`) REFERENCES `evento` (`cod_evento`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`ciudad`) REFERENCES `ciudad` (`cod_ciudad`),
  ADD CONSTRAINT `persona_ibfk_2` FOREIGN KEY (`tipo_persona`) REFERENCES `tipo_persona` (`cod_tipo`),
  ADD CONSTRAINT `persona_ibfk_3` FOREIGN KEY (`cod_grado`) REFERENCES `grado` (`cod_grado`);

--
-- Filtros para la tabla `personal_evento`
--
ALTER TABLE `personal_evento`
  ADD CONSTRAINT `personal_evento_ibfk_1` FOREIGN KEY (`cod_evento`) REFERENCES `evento` (`cod_evento`),
  ADD CONSTRAINT `personal_evento_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `persona` (`id_user`);

--
-- Filtros para la tabla `registro_participante`
--
ALTER TABLE `registro_participante`
  ADD CONSTRAINT `registro_participante_ibfk_1` FOREIGN KEY (`cod_evento`) REFERENCES `evento` (`cod_evento`),
  ADD CONSTRAINT `registro_participante_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `persona` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
