-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-07-2022 a las 18:43:18
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aupurtv`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`codigo`, `nombre`) VALUES
(1, 'Secretaria'),
(2, 'Tecnico'),
(3, 'Canal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `idP` int(11) NOT NULL,
  `idCalificante` int(11) NOT NULL,
  `idCalificador` int(11) NOT NULL,
  `nota` varchar(15) NOT NULL,
  `mes` varchar(15) NOT NULL,
  `area` int(11) NOT NULL,
  `general` tinyint(1) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int(5) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `imagen` longblob NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `tamano` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventariogeneral`
--

CREATE TABLE `inventariogeneral` (
  `cod` int(11) NOT NULL,
  `nombre` varchar(225) NOT NULL,
  `estado` varchar(225) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `inventariogeneral`
--

INSERT INTO `inventariogeneral` (`cod`, `nombre`, `estado`, `id_responsable`, `area`) VALUES
(1, 'Computador', 'Nuevo', 23, 2),
(2, 'Libro', 'Nuevo', 24, 2),
(21, 'Teclado', 'Viejo', 28, 2),
(34, 'telefono', '098', 29, 2),
(65, 'Polvo', 'Poco', 1234, 3),
(123, 'Televisor', 'Nuevo', 24, 2),
(210, 'Teclado', 'Viejo', 23, 2),
(342, 'telefono', '098', 28, 2),
(653, 'Polvo', 'Poco', 21, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(200) NOT NULL,
  `general` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `pregunta`, `general`) VALUES
(1, 'Ambiente laboral', 1),
(2, 'Presentacion personal', 1),
(3, 'Colaboracion', 1),
(4, 'Cumplimiento', 0),
(5, 'Puntualidad', 0),
(6, 'Responsabilidad', 0),
(7, 'Proactividad', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`codigo`, `nombre`) VALUES
(1, 'Empleado'),
(2, 'Administrador'),
(3, 'Jefe'),
(4, 'Gerente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `clave` varchar(250) NOT NULL,
  `rol` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `correo` varchar(225) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`, `rol`, `area`, `correo`, `telefono`) VALUES
(11, 'Ana elena franco', '123', 1, 1, 'ana@gmail.com', '32133'),
(12, 'Isabel ', '123', 1, 1, 'isabel@gmail.com', '321332'),
(13, 'Alejandro restrepo', '123', 1, 1, 'alejandro@gmail.com', '43211'),
(14, 'Johan Fernandez', '123', 3, 1, 'johan@gmail.com', '76543'),
(21, 'William cossio', '123', 1, 2, 'william@gmail.com', '4321'),
(22, 'Hernan', '123', 1, 2, 'hernan@gmail.com', '4321'),
(23, 'Luis', '123', 1, 2, 'luis@gmail.com', '432'),
(24, 'Mario', '123', 1, 2, 'mario@gmail.com', '6543'),
(25, 'Alejandro arias', '123', 1, 2, 'alejandro@gmail.com', '432'),
(26, 'Jhonatan', '123', 1, 2, 'jhonatan@gmail.com', '1234'),
(27, 'Juan david ibarra', '123', 1, 2, 'juan@gmail.com', '5432'),
(28, 'Yuliana jimenez', '123', 1, 2, 'yuliana@gmail.com', '76543'),
(29, 'Camilo Gutierrez', '123', 3, 2, 'camilo@gmail.com', '54321`'),
(31, 'Victor alfonso pulgarin', '123', 1, 3, 'victor@gmail.com', '43671'),
(32, 'Diana maria franco', '123', 1, 3, 'diana@gmail.com', '63782'),
(33, 'Wilfer andres serna', '123', 1, 3, 'wilfer@gmail.com', '537281'),
(34, 'Stevens Vargas', '123', 1, 3, 'stevens@gmail.com', '76478289'),
(35, 'Astrid Herrera', '123', 1, 3, 'astrid@gmail.com', '388292'),
(36, 'Brayan roqueme', '123', 1, 3, 'brayan@gmail.com', '37282'),
(37, 'Faudier sepulveda', '123', 1, 3, 'faudier@gmail.com', '66372812'),
(38, 'Juan pablo salazar', '123', 1, 3, 'juanp@gmail.com', '6272818'),
(39, 'Esneider Velasquez', '123', 3, 3, 'esneider@gmail.com', '543');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`idP`,`idCalificante`,`idCalificador`,`mes`),
  ADD KEY `idP` (`idP`),
  ADD KEY `idCalificante` (`idCalificante`),
  ADD KEY `idCalificador` (`idCalificador`),
  ADD KEY `area` (`area`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventariogeneral`
--
ALTER TABLE `inventariogeneral`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `area` (`area`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`rol`),
  ADD KEY `area` (`area`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`idP`) REFERENCES `preguntas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`idCalificante`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`idCalificador`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `calificaciones_ibfk_4` FOREIGN KEY (`area`) REFERENCES `area` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inventariogeneral`
--
ALTER TABLE `inventariogeneral`
  ADD CONSTRAINT `inventariogeneral_ibfk_1` FOREIGN KEY (`area`) REFERENCES `area` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`area`) REFERENCES `area` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol`) REFERENCES `rol` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
