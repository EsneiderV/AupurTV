<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stilos.css">
    <title>Incio</title>
</head>

<body>
    <div class="second">
        <a href="view/usuario.php">Login</a>
    </div>

    <?php
    include_once 'controllers/php/funciones.php';
    include_once 'models/Conexion.php';
    $mes = date('m');
    $usuarios = mostrarUsuarioAdmin($conexion);
    while ($usuario = mysqli_fetch_array($usuarios)) {
        $preguntas = mostrarPreguntas($conexion);
        echo "<h1>".$usuario['nombre']."</h1>";
        while ($pregunta = mysqli_fetch_array($preguntas)) {
             $calificaciones = mostrarCalificacionAdmin($usuario['id'], $pregunta['id'], $mes, $conexion);
             echo "<div>";
             echo $pregunta['pregunta'].": ";
             while ($calificacion =  mysqli_fetch_array($calificaciones)) {
                echo $calificacion['nota']." | ";
             }
             
             echo "</div>";
        }
    }
    ?>

    <h1>Nota por grupo</h1>

    <?php 
    $areas = mostrarArea($conexion);
    while ($area = mysqli_fetch_array($areas)){
        echo "<h1>".$area['nombre']."</h1>";
        $notas = mostrarNotaArea($mes,$area['codigo'],$conexion);
        while ($nota = mysqli_fetch_array($notas)) {
            echo $nota['pregunta'].":".$nota['promedio']."<br>";
        }
    }
    ?>

</body>

</html>