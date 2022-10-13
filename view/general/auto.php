<?php 
session_start();
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');

    $idCalificante = $_SESSION['id'];
    $idCalificador = $_SESSION['id'];
    $area = $_SESSION['area'];
    $nota = $_POST['valor'];
    $tipo = 0;
    $rol = $_SESSION['rol'];
    $mes = date('m');
    $preguntas = mostrarPreguntasid($tipo, $conexion);
    foreach ($nota as $key => $value) {
      guardarCalificaciones($preguntas[$key][0], $idCalificante, $idCalificador, $value, $mes, $area, $preguntas[$key][1], $rol, $_SESSION['area'], $anio, $conexion);
    }
 ?>