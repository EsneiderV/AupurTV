<?php
session_start();
include_once '../controllers/php/funciones.php';
include_once '../models/Conexion.php';
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] != 3) {
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <title>Empleado</title>
</head>

<body class="empleado-body">

    <div class="div-imagen">
        <img class="empleado-foto" src="../IMG-20190806-WA0020.jpg" alt="foto de perfil">
    </div>


    <div class="jefe-opciones">
        <h1> <?php echo strtoupper($_SESSION['nombre']) ?>   Gefe</h1>
        <div class="jefe-items">
            <button class="jefe-item" data-bs-toggle="modal" data-bs-target="#datosPersonales">Datos personales</button>
            <button class="jefe-item">
                <a href="calificar.php" class="empleado-enlace a-f-r">Calificar</a>
            </button>
            <button class="jefe-item" data-bs-toggle="modal" data-bs-target="#inventario">Mi inventario</button>
            <button class="jefe-item" data-bs-toggle="modal" data-bs-target="#directorio">Mi directorio</button>
            <button class="jefe-item">
                <a class="jefe-enlace a-f-r" target="_blank" href="https://mail.google.com/mail/u/0/">
                Inventario area
                </a>
            </button>
            <button class="jefe-item" data-bs-toggle="modal" data-bs-target="#directorio">Calificaciones area</button>
            <button class="jefe-item">
                <a class="jefe-enlace a-f-r" target="_blank" href="https://mail.google.com/mail/u/0/">
                    Mi correo
                </a>
            </button>
        </div>
        <a href="../models/Cerrar.php" class="jefe-enlace a-f-r">Cerrar sesion</a>
    </div>



    <!-- Modal Datos personales-->
    <div class="modal fade" id="datosPersonales" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title a-ms-25 fs-2 a-f-t-r" id="exampleModalLabel">DATOS PERSONALES</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $datos = datosPersonales($_SESSION['id'], $conexion);
                    $datos = mysqli_fetch_array($datos)
                    ?>
                    <p class="empleados-p-directorio"> <span> <b>DOCUMENTO :</b> <?php echo $datos['id'] ?></span> </p>
                    <p class="empleados-p-directorio"> <span> <b>NOMBRES :</b> <?php echo $datos['nombre'] ?></span> </p>
                    <p class="empleados-p-directorio"> <span> <b> ROL: </b> <?php echo $datos['rol'] ?></span> </p>
                    <p class="empleados-p-directorio"> <span> <b>ÁREA :</b> <?php echo $datos['area'] ?></span> </p>

                    <?php
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Inventario -->
     <div class="modal fade" id="inventario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title a-ms-30 fs-2 a-f-t-r" id="exampleModalLabel">INVENTARIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $inventarios = mostrarInventario($_SESSION['id'],$conexion);
                    while ($inventario = mysqli_fetch_array($inventarios)) {
                    ?>
                            <hr>
                            <p class="empleados-p-directorio">
                                <span> <b>COD :</b> <?php echo $inventario['cod'] ?> </span>
                                <span> <b>NOM :</b> <?php echo $inventario['nombre'] ?> </span>
                                <span> <b>EST :</b> <?php echo $inventario['estado'] ?></span>
                            </p>

                    <?php
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Directorio-->
    <div class="modal fade" id="directorio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title a-ms-40 fs-2 a-f-t-r" id="exampleModalLabel">DIRECTORIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $areas = mostrarArea($conexion);
                    while ($area = mysqli_fetch_array($areas)) {
                        echo "<details>";
                        echo "<summary class='pt-4 h5'>" . $area['nombre'] . "</summary>";
                        $directorios = mostrarDirectorio($area['codigo'], $conexion);
                        while ($directorio = mysqli_fetch_array($directorios)) {


                    ?>
                            <hr>
                            <p class="empleados-p-directorio">
                                <span> <b> <?php echo $directorio['nombre'] ?> </b> </span>
                                <span> <b>EM :</b>  <?php echo $directorio['correo'] ?> </span>
                                <span> <b>CEL :</b> <?php echo $directorio['telefono'] ?></span>
                            </p>

                    <?php
                        }
                        echo "<hr/>";
                        echo "</details>";
                    }
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