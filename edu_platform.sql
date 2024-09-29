-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 29-09-2024 a las 22:01:33
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
-- Base de datos: `edu_platform`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answer`
--

CREATE TABLE `answer` (
  `qid` text NOT NULL,
  `ansid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `answer`
--

INSERT INTO `answer` (`qid`, `ansid`) VALUES
('66c92b9d09d35', '66c92b9d0a5da');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `leccion_id` int(11) NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id`, `usuario_id`, `leccion_id`, `nota`, `fecha`) VALUES
(1, 1, 1, 0.00, '2024-09-29 19:29:58'),
(2, 1, 1, 0.00, '2024-09-29 19:30:12'),
(3, 1, 1, 0.00, '2024-09-29 19:30:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `foro_id` int(11) NOT NULL,
  `leccion_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` bigint(20) NOT NULL,
  `tipo_usuario` enum('docente','estudiante') NOT NULL,
  `respuesta_a` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `foro_id`, `leccion_id`, `contenido`, `fecha_creacion`, `usuario_id`, `tipo_usuario`, `respuesta_a`) VALUES
(1, 2, 0, 'test', '2024-09-22 21:23:49', 1, 'docente', NULL),
(2, 2, 0, 'ss', '2024-09-22 21:24:19', 1, 'docente', NULL),
(3, 2, 0, 'dd', '2024-09-22 21:32:43', 1, 'docente', NULL),
(4, 2, 0, 'no', '2024-09-22 21:47:26', 1, 'docente', 1),
(5, 2, 0, 's', '2024-09-22 22:02:41', 1, 'docente', 4),
(6, 2, 0, 'si', '2024-09-22 22:16:35', 1, 'docente', 1),
(7, 2, 0, 'noooo', '2024-09-22 22:58:57', 1, 'docente', 1),
(8, 1, 0, 's', '2024-09-22 23:39:59', 1, 'docente', NULL),
(9, 1, 0, 'n', '2024-09-22 23:40:30', 1, 'docente', 8),
(10, 2, 0, 'nn', '2024-09-22 23:49:34', 1, 'docente', 3),
(11, 2, 0, 'hola', '2024-09-22 23:49:48', 1, 'docente', 3),
(12, 3, 0, 'si', '2024-09-23 00:21:11', 1, 'docente', NULL),
(13, 3, 0, 'di', '2024-09-23 19:38:42', 1, 'docente', 12),
(14, 2, 0, 'no', '2024-09-23 19:39:03', 1, 'docente', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `titulo`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Nasayuwe', '', '2024-09-25 01:35:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `documento_identidad` bigint(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellido` varchar(25) NOT NULL,
  `tipo_documento` varchar(25) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `titulo` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fecha_nacimiento` varchar(25) NOT NULL,
  `contrasena` mediumtext NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`documento_identidad`, `nombre`, `apellido`, `tipo_documento`, `telefono`, `titulo`, `email`, `fecha_nacimiento`, `contrasena`, `estado`) VALUES
(1, 'Alexisssss', 'Galeano', 'cedula', '123456789', 'ingeniero de sistemas', 'user1@gmail.com', '1998-04-08', '$2y$10$1K.5mWkc6KrhS3VBTEXFz.OlFedU5Jdo/avYxL1pAFr7S2qPdjmk6', 'activo'),
(222, 'Andres', 'Bolaños', 'otro', '3214744820', '2sd', 'estefi@gmail.com', '2024-08-22', '$2y$12$Ef72RtU8rdGA6f46ctKvNuIgpSfHv1stwMbWuaP10a2HwA9SvuXSu', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `documento_identidad` bigint(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellido` varchar(25) NOT NULL,
  `genero` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `fecha_nacimiento` varchar(25) NOT NULL,
  `contrasena` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`documento_identidad`, `nombre`, `apellido`, `genero`, `email`, `telefono`, `fecha_nacimiento`, `contrasena`) VALUES
(1, 'Alexissss', 'G', '', 'Prueba1@gmail.com', '1234567899', '2023-11-02', '$2y$12$Ef72RtU8rdGA6f46ctKvNuIgpSfHv1stwMbWuaP10a2HwA9SvuXSu'),
(98, 'Andres', 'Bolaños', 'Femenino', 'user1@gmail.com', '3214744820', '2024-08-22', '$2y$12$Hp6NeYSc56m/c//MmntU5uam3TuhfxCKOjalpATbMy5.oVDBIb1Uy'),
(10604190599, 'Yorman', 'R', 'Masculino', 'Prueba2@hotmail.es', '9876543211', '1996-12-16', '$2y$12$mz6IJVQQ9W.qWmn4/QdGX.Ya4U084ihtyoI82viZa4H44cGzJ55E2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feedback`
--

CREATE TABLE `feedback` (
  `id` text NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `feedback` varchar(500) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `subject`, `feedback`, `date`, `time`) VALUES
('5f87ca01c593b', 'Usuario', 'configuroweb@gmail.com', 'problemas con la pregunta 3', 'no estoy de acuerdo con la pregunta 3, no me parece una opción exacta, sería mejor una pregunta abierta.', '2020-10-15', '06:03:13am'),
('5f88c9bea1954', 'Juan Ramón', 'jramon@cweb.com', 'tengo problemas para registrarme', 'Realizo el proceso, pero no puedo acceder tal vez estoy haciendo algo mal, saludos.', '2020-10-16', '12:14:22am');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `documento_identidad` bigint(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `files`
--

INSERT INTO `files` (`id`, `vid`, `documento_identidad`, `title`, `description`, `url`, `type`) VALUES
(12, 1, 1, 'asdas', 'dasd', 'uploads/pdf_66cb9b467340b7.03684687.pdf', 'pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files_alf`
--

CREATE TABLE `files_alf` (
  `id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `documento_identidad` bigint(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `files_alf`
--

INSERT INTO `files_alf` (`id`, `vid`, `documento_identidad`, `title`, `description`, `url`, `type`) VALUES
(10, 1, 1, '1SSS', 'KHJH', 'uploads/pdf_66cb72fb283c99.14950883.pdf', 'pdf'),
(11, 1, 1, '1SSS', 'KHJH', 'uploads/pdf_66cb755b762950.18908401.pdf', 'pdf'),
(12, 2, 1, '1SSS', 'sss', 'uploads/pdf_66cb830a9df352.09019072.pdf', 'pdf'),
(13, 11, 1, 'te', 'ss', 'uploads/pdf_66cbf046a9c4a0.86202292.pdf', 'pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `creador_id` bigint(20) NOT NULL,
  `tipo_creador` enum('docente','estudiante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `foro`
--

INSERT INTO `foro` (`id`, `titulo`, `descripcion`, `fecha_creacion`, `creador_id`, `tipo_creador`) VALUES
(1, 's', 's', '2024-09-22 20:20:53', 1, 'docente'),
(2, 's', 'jjj', '2024-09-22 20:53:38', 1, 'docente'),
(3, 'Trucos para aprender Nasayuwe', 'Vamo a aprender...................', '2024-09-22 23:42:56', 1, 'docente'),
(4, 'ASDASD', 'test', '2024-09-29 19:00:25', 1, 'docente'),
(5, 'ASDASD', 'tst', '2024-09-29 19:00:42', 1, 'docente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `history`
--

CREATE TABLE `history` (
  `email` varchar(50) NOT NULL,
  `eid` text NOT NULL,
  `score` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `history`
--

INSERT INTO `history` (`email`, `eid`, `score`, `level`, `sahi`, `wrong`, `date`) VALUES
('Prueba1@gmail.com', '66c92b90a76f5', 1, 1, 1, 0, '2024-08-26 03:03:10'),
('user1@gmail.com', '66c92b90a76f5', 1, 1, 1, 0, '2024-09-22 16:33:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lecciones`
--

CREATE TABLE `lecciones` (
  `id` int(11) NOT NULL,
  `nivel_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen_url` varchar(500) DEFAULT NULL,
  `audio_url` varchar(500) DEFAULT NULL,
  `duracion` varchar(50) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lecciones`
--

INSERT INTO `lecciones` (`id`, `nivel_id`, `titulo`, `descripcion`, `imagen_url`, `audio_url`, `duracion`, `video_url`) VALUES
(1, 1, 'Numeros', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. At exercitationem, tempore odio voluptatibus eveniet alias aliquid nostrum cum neque non ullam, excepturi mollitia ab voluptatem. Sapiente illo officia culpa deleniti animi est eum et optio maxime, debitis quia? Illum qui ipsum impedit. Ipsum voluptas blanditiis autem eveniet explicabo totam doloremque magnam odit animi? Ratione voluptatum sapiente modi optio repudiandae corrupti accusamus ab obcaecati dolore nisi quibusdam ullam ipsam, earum quos iste molestiae aliquid ducimus magnam, quam aut quisquam quo non harum? Nisi incidunt dolor numquam voluptas, ad assumenda nihil exercitationem, iusto, sit fuga laborum accusamus? Fugit praesentium ducimus quod saepe.', 'uploads/images/maxresdefault.jpg', 'uploads/audios/1.mp3', '1', NULL),
(2, 1, 'Vocales', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt impedit reprehenderit ullam natus exercitationem voluptate optio dolor harum laudantium dolore laborum accusamus, ipsam dicta possimus, iure commodi? Vero, beatae quo?', NULL, NULL, '1', 'uploads/videos/dede.mp4'),
(3, 2, 'Personas', 'Tes', 'uploads/images/nasa_1.png', 'uploads/audios/27_9_2024, 9_18_17 p. m. - Audio - andresd.webm', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `curso_id`, `titulo`, `descripcion`) VALUES
(1, 1, 'Parte 1', NULL),
(2, 1, 'Parte 2', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `options`
--

CREATE TABLE `options` (
  `qid` varchar(50) NOT NULL,
  `option` varchar(5000) NOT NULL,
  `optionid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `options`
--

INSERT INTO `options` (`qid`, `option`, `optionid`) VALUES
('66c92b9d09d35', 'A', '66c92b9d0a5da'),
('66c92b9d09d35', 'B', '66c92b9d0a5e1'),
('66c92b9d09d35', 'C', '66c92b9d0a5e2'),
('66c92b9d09d35', 'D', '66c92b9d0a5e3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_leccion`
--

CREATE TABLE `preguntas_leccion` (
  `id` int(11) NOT NULL,
  `leccion_id` int(11) DEFAULT NULL,
  `pregunta` text NOT NULL,
  `opcion1` text NOT NULL,
  `opcion2` text NOT NULL,
  `opcion3` text NOT NULL,
  `opcion4` text NOT NULL,
  `correcta` enum('1','2','3','4') NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_leccion`
--

INSERT INTO `preguntas_leccion` (`id`, `leccion_id`, `pregunta`, `opcion1`, `opcion2`, `opcion3`, `opcion4`, `correcta`, `porcentaje`) VALUES
(1, 1, 'Selecciona la pregunta correcta', 'Op 1', 'Op 2', 'Op 3', 'Op 4', '4', 0.00),
(2, 2, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt impedit reprehenderit ullam natus exercitationem voluptate optio dolor harum laudantium dolore laborum accusamus, ipsam dicta possimus, iure commodi? Vero, beatae quo?', 'Lorem ipsum?', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt impedit reprehenderit ullam natus exercitationem voluptate optio dolor harum laudantium dolore laborum ', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt impedit reprehenderit ullam natus exercitationem voluptate optio dolor harum laudantium dolore laborum accusamus, ipsam dicta possimus, iure commodi? Vero, beatae quo?', 'Lorem ipsum dolor sit', '3', 0.00),
(3, 3, 'Pregunta personas', '1', '2', '3', '4', '1', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_respuestas`
--

CREATE TABLE `preguntas_respuestas` (
  `id` int(11) NOT NULL,
  `leccion_id` int(11) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `tipo_usuario` enum('docente','estudiante') NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `respuesta_a` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE `questions` (
  `eid` text NOT NULL,
  `qid` text NOT NULL,
  `qns` text NOT NULL,
  `choice` int(10) NOT NULL,
  `sn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`eid`, `qid`, `qns`, `choice`, `sn`) VALUES
('66c92b90a76f5', '66c92b9d09d35', 'test 1?', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz`
--

CREATE TABLE `quiz` (
  `eid` text NOT NULL,
  `title` varchar(100) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `intro` text NOT NULL,
  `tag` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `quiz`
--

INSERT INTO `quiz` (`eid`, `title`, `sahi`, `wrong`, `total`, `time`, `intro`, `tag`, `date`) VALUES
('66c92b90a76f5', 'Test', 1, 1, 1, 0, '1', '111', '2024-08-24 00:38:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rank`
--

CREATE TABLE `rank` (
  `email` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rating` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `rank`
--

INSERT INTO `rank` (`email`, `score`, `time`, `rating`) VALUES
('Prueba2@hotmail.es', -4, '2024-03-20 00:19:45', 0),
('user1@gmail.com', 0, '2024-09-22 16:33:38', 5),
('Prueba1@gmail.com', 1, '2024-08-26 03:03:10', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` bigint(10) NOT NULL,
  `name` varchar(250) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contrasena` mediumtext NOT NULL,
  `id_rol` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `name`, `last_name`, `telefono`, `email`, `contrasena`, `id_rol`) VALUES
(1060419059, 'Yorman Elkins', 'Rebolledo', '9876543211', 'Yelkinrebolledo@hotmail.es', '$2y$10$krOzKCdEO5iUu0ZcZdIxdOAkUZ5V56LZwmCLUxu.qX8yQLJ1VCWmG', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `video_name` varchar(100) NOT NULL,
  `location` varchar(10000) NOT NULL,
  `descripcion` text NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `video`
--

INSERT INTO `video` (`video_id`, `video_name`, `location`, `descripcion`, `category`) VALUES
(1, 'VISUAL STUDIO CODE: Tutorial para principiantes', 'https://www.youtube.com/watch?v=CxF3ykWP1H4&ab_channel=MoureDevbyBraisMoure', 'VISUAL STUDIO CODsE: Tutorial para principiantes', 'Educación'),
(2, '202403131710310391', 'https://www.youtube.com/watch?v=8jsa43q3iq4&ab_channel', 'can i call you tonight', ''),
(3, '202403131710311008', 'https://www.youtube.com/watch?v=WUO4XVvu868&ab_channel', 'a', ''),
(6, 'Video', 'https://www.youtube.com/watch?v=37IkMXAuEAc', 'Videoss', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `video_alf`
--

CREATE TABLE `video_alf` (
  `video_id` int(11) NOT NULL,
  `video_name` varchar(100) NOT NULL,
  `location` varchar(10000) NOT NULL,
  `descripcion` text NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `video_alf`
--

INSERT INTO `video_alf` (`video_id`, `video_name`, `location`, `descripcion`, `category`) VALUES
(1, '', 'https://www.youtube.com/watch?v=xvf23ZCPgFQ&ab_channel', 'test', 'Educación'),
(2, 'test', 'https://www.youtube.com/watch?v=Z8TF8rRwWIw', 'Z8TF8rRwWIw', 'Educación'),
(3, 'video1', 'https://www.youtube.com/watch?v=gUQKFpJV3g8', '', 'Tecnología'),
(4, 'https://www.youtube.com/watch?v=XE_l9Bc72Io', 'https://www.youtube.com/watch?v=XE_l9Bc72Io', '', 'Educación'),
(5, 'https://www.youtube.com/watch?v=XE_l9Bc72Io', 'https://www.youtube.com/watch?v=XE_l9Bc72Io', '', 'Educación'),
(11, 'https://www.youtube.com/watch?v=gUQKFpJV3g8', 'https://www.youtube.com/watch?v=gUQKFpJV3g8', '', 'Educación');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leccion_id` (`leccion_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foro_id` (`foro_id`),
  ADD KEY `respuesta_a` (`respuesta_a`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`documento_identidad`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`documento_identidad`);

--
-- Indices de la tabla `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento_identidad` (`documento_identidad`);

--
-- Indices de la tabla `files_alf`
--
ALTER TABLE `files_alf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento_identidad` (`documento_identidad`);

--
-- Indices de la tabla `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lecciones`
--
ALTER TABLE `lecciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nivel_id` (`nivel_id`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `preguntas_leccion`
--
ALTER TABLE `preguntas_leccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leccion_id` (`leccion_id`);

--
-- Indices de la tabla `preguntas_respuestas`
--
ALTER TABLE `preguntas_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leccion_id` (`leccion_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `respuesta_a` (`respuesta_a`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`);

--
-- Indices de la tabla `video_alf`
--
ALTER TABLE `video_alf`
  ADD PRIMARY KEY (`video_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `files_alf`
--
ALTER TABLE `files_alf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `lecciones`
--
ALTER TABLE `lecciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `preguntas_leccion`
--
ALTER TABLE `preguntas_leccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `preguntas_respuestas`
--
ALTER TABLE `preguntas_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `video_alf`
--
ALTER TABLE `video_alf`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`leccion_id`) REFERENCES `lecciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `estudiantes` (`documento_identidad`);

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`foro_id`) REFERENCES `foro` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`respuesta_a`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`documento_identidad`) REFERENCES `estudiantes` (`documento_identidad`);

--
-- Filtros para la tabla `files_alf`
--
ALTER TABLE `files_alf`
  ADD CONSTRAINT `files_alf_ibfk_1` FOREIGN KEY (`documento_identidad`) REFERENCES `estudiantes` (`documento_identidad`);

--
-- Filtros para la tabla `lecciones`
--
ALTER TABLE `lecciones`
  ADD CONSTRAINT `lecciones_ibfk_1` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD CONSTRAINT `niveles_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas_leccion`
--
ALTER TABLE `preguntas_leccion`
  ADD CONSTRAINT `preguntas_leccion_ibfk_1` FOREIGN KEY (`leccion_id`) REFERENCES `lecciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas_respuestas`
--
ALTER TABLE `preguntas_respuestas`
  ADD CONSTRAINT `preguntas_respuestas_ibfk_1` FOREIGN KEY (`leccion_id`) REFERENCES `lecciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `preguntas_respuestas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `preguntas_respuestas_ibfk_3` FOREIGN KEY (`respuesta_a`) REFERENCES `preguntas_respuestas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
