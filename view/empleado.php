<?php
session_start();
include_once '../controllers/php/funciones.php';
include_once '../models/Conexion.php';
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] != 1) {
        echo '<script type="text/javascript">
                window.location.href="usuario.php";
                </script>';
    }
} else {
    echo '<script type="text/javascript">
                window.location.href="usuario.php";
                </script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../controllers/bootstrap/bootstrap.min.css">
    <script src="../controllers/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../controllers/css/style.css">
    <title>Empleado</title>
</head>

<body class="empleado-body">

<div class="div-imagen">
    <img  class="empleado-foto" src="https://dam.muyinteresante.com.mx/wp-content/uploads/2018/05/extranos-pueden-elegir-mejores-fotos-de-perfil.jpg" alt="foto de perfil">
</div>


    <div class="empleado-opciones empleado-div">
        <h1>Empleado</h1>
        <div>
            <button data-bs-toggle="modal" data-bs-target="#datosPersonales">Datos personales</button> <br>
            <button data-bs-toggle="modal" data-bs-target="#promedio">Promedio</button> <br>
            <a href="calificar.php">Calificar</a> <br>
            <button data-bs-toggle="modal" data-bs-target="#empleadoDelMes">Empleado del mes</button> <br>
            <a href="../models/Cerrar.php">Cerrar SECION</a> <br>
        </div>
    </div>



    <!-- Modal Datos personales-->
    <div class="modal fade" id="datosPersonales" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos personales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $datos = datosPersonales($_SESSION['id'], $conexion);
                    $datos = mysqli_fetch_array($datos)
                    ?>
                    <p><span>Documento : <?php echo $datos['id'] ?></span></p>
                    <p><span>Nombres : <?php echo $datos['nombre'] ?></span></p>
                    <p><span>Rol : <?php echo $datos['rol'] ?></span></p>
                    <p><span>Área : <?php echo $datos['area'] ?></span></p>

                    <?php
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Promedio-->
    <div class="modal fade" id="promedio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">PROMEDIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $mes = date('m');
                    $id = $_SESSION['id'];
                    $promedioTotal = 0;
                    $cont = 0;
                    $resultado = promedioPorPregunta($id, $mes, $conexion);
                    while ($usuario = mysqli_fetch_array($resultado)) {
                        $cont++;
                        $promedioTotal += $usuario['promedio'];
                    ?>
                        <p>
                            <?php echo $usuario['pregunta'] ?> <span>: <?php echo $usuario['promedio'] ?></span>
                        </p>
                    <?php
                    }
                    if ($cont > 0) {
                        echo "<p>Promedio total : " . $promedioTotal / $cont . "</p>";
                    } else {
                        echo "<p>Todavia No tienes calificaciones</p>";
                    }
                    ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Empleado del mes -->
    <div class="modal fade" id="empleadoDelMes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">EMPLEADO DEL MES</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $empleadoM = empeladoDelMes($mes, $conexion);
                    $empleadoM = mysqli_fetch_array($empleadoM)
                    ?>
                    <p><span>Documento : <?php echo $empleadoM['idCalificador'] ?></span></p>
                    <p><span>Nombres : <?php echo $empleadoM['nombre'] ?></span></p>
                    <p><span>Área : <?php echo $empleadoM['area'] ?></span></p>
                    <p><span>Promedio : <?php echo $empleadoM['promedio'] ?></span></p>
                    <p><span>Mes : <?php echo $empleadoM['mes'] ?></span></p>


                    <?php
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>