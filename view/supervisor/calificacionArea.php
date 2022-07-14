<?php
session_start();
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] != 2 && $_SESSION['rol'] != 3) {
        echo '<script type="text/javascript">
                  window.location.href="../../index.php";
                </script>';
    }
} else {
    echo '<script type="text/javascript">
                  window.location.href="../../index.php";
                </script>';
}


$preguntas = mostrarPreguntas(0,$conexion);
$preguntasA = mostrarPreguntas(0,$conexion);
$usuarios = mostarUsuarioCalificacionArea($_SESSION['area'],$conexion);
$mes = date('m');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="../../controllers/bootstrap/bootstrap.min.css">
    <script src="../../controllers/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../controllers/css/style.css">


    <title>Calificación área - Aupur Televisión</title>
</head>

<body class="calificacionArea-body">


    <div class="inventarioArea-div-nav">
        <a href="supervisor.php" class="inventarioArea-volver"> ᗕ Volver atrás</a>
        <h1 class="inventarioArea-titulo">Calificación área</h1>
    </div>

    <div class="inventarioArea-contenedor-por-persona">
        <?php
        $j = 0;
        $aregloNotaEquipo = [];
        while ($pregunta = mysqli_fetch_array($preguntas)) {
            $aregloNotaEquipo[$j] = 0;
            $j++;
        }

        $i = 0;
        while ($usuario = mysqli_fetch_array($usuarios)) {

            echo '<div class="inventarioArea-por-persona">';
            echo $usuario['nombre'].'<br>';

            if($usuario['rol'] == 1){
                $sesenta = calificacionPersonaPorcentage($mes,1,$usuario['id'],$conexion);
                $veinte = calificacionPersonaPorcentage($mes,3,$usuario['id'],$conexion);
                $diez = calificacionPersonaPorcentage($mes,2,$usuario['id'],$conexion);
                $auto = autoCalificacionPersonaPorcentage($mes,$usuario['id'],$conexion);
                $total = 0;
                $contador =0;
                foreach ($sesenta as $key => $value) {
                    $notafinal = ($sesenta[$key]['1'] * 0.6) + ($veinte[$key]['1'] * 0.2) + ($diez[$key]['1'] * 0.1) + ($auto[$key]['1'] * 0.1) ;
                    echo $sesenta[$key]['0'].'  =  '.$notafinal.'<br>';
                    $contador = $contador + 1;
                    $total = $total + $notafinal;
                    $aregloNotaEquipo[$contador - 1] = $aregloNotaEquipo[$contador - 1] +  $notafinal;
                }
                if($total > 0 && $contador > 0){
                    echo 'Nota final = '.$total/$contador;
                }
            }else if ($usuario['rol'] == 2){
                $sesenta = calificacionPersonaPorcentage($mes,1,$usuario['id'],$conexion);
                $treinta = calificacionPersonaPorcentage($mes,3,$usuario['id'],$conexion);
                $auto = autoCalificacionPersonaPorcentage($mes,$usuario['id'],$conexion);
                $total = 0;
                $contador =0;
                foreach ($sesenta as $key => $value) {
                    $notafinal = ($sesenta[$key]['1'] * 0.7) + ($treinta[$key]['1'] * 0.2)  + ($auto[$key]['1'] * 0.1) ;
    
                    echo $sesenta[$key]['0'].'  =  '.$notafinal.'<br>';
                    $contador = $contador + 1;
                    $total = $total + $notafinal;
                    $aregloNotaEquipo[$contador - 1] = $aregloNotaEquipo[$contador - 1] +  $notafinal;
                }
                if($total > 0 && $contador > 0){
                    echo 'Nota final = '.$total/$contador;
                }
            }else if ($usuario['rol'] == 3){
                $sesenta = calificacionPersonaPorcentage($mes,1,$usuario['id'],$conexion);
                $treinta = calificacionPersonaPorcentage($mes,2,$usuario['id'],$conexion);
                $auto = autoCalificacionPersonaPorcentage($mes,$usuario['id'],$conexion);
                $total = 0;
                $contador =0;
                foreach ($sesenta as $key => $value) {
                    $notafinal = ($sesenta[$key]['1'] * 0.7) + ($treinta[$key]['1'] * 0.2)  + ($auto[$key]['1'] * 0.1) ;
                    echo $sesenta[$key]['0'].'  =  '.$notafinal.'<br>';
                    $contador = $contador + 1;
                    $total = $total + $notafinal;
                    $aregloNotaEquipo[$contador - 1] = $aregloNotaEquipo[$contador - 1] +  $notafinal;
                }
                if($total > 0 && $contador > 0){
                    echo 'Nota final = '.$total/$contador;
                }
            }
            echo '</div>';
            $i = $i+1;
        }
        ?>
    </div>

    <div class="inventarioArea-contenedor-por-persona">
        <?php 
        $k = 0;
        $finalArea = 0;
            while ($preguntas = mysqli_fetch_array($preguntasA)) {
                echo $preguntas['pregunta'].' = '.($aregloNotaEquipo[$k] / $i).'<br>';
                 $finalArea = $finalArea + ($aregloNotaEquipo[$k] / $i);
                $k++;
            }

            echo 'Nota Final = '.$finalArea /($k + 1);

        ?>
    </div>
</body>

</html>