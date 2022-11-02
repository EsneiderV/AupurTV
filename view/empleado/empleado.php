<?php
session_start();
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 

// verificamos que el usuario que entra si este registrado y sea de rol empleado
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] != 1) {
        echo '<script type="text/javascript">
                window.location.href="../../index.php";
                </script>';
    }
} else {
    echo '<script type="text/javascript">
                window.location.href="../../index.php";
                </script>';
}
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
registroCalificacionArea($_SESSION['area'], $mes, $anio, $conexion);
registroCalificacionpersonaGeneral($mes,$anio,$area,$conexion)
?>

<!DOCTYPE html>
<html lang="es" class="empleado-html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="../../controllers/bootstrap/bootstrap.min.css">
    <script src="../../controllers/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../controllers/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title> <?php echo $_SESSION['nombre'] ?> - Aupur Televisión </title>
</head>

<body class="empleado-body">

    <div class="empleado-contenedor-principal">

        <div class="empleado-contenedor-izquierdo">
            <div class="empleado-contenedor-logo">
                <img class="empleado-logo" src="../../image/logoNaranja.png" alt="logo de la empresa">
            </div>

            <!-- Mostramos la imagen de secion del usuario -->
            <div class="empleado-contenedor-imagen">
                <img class="empleado-imagen" src="data:<?php echo $_SESSION['tipo_imagen'] ?>;base64,<?php echo base64_encode($_SESSION['imagen']) ?>" alt="foto de perfil">
            </div>
        </div>

        <div class="empleado-contenedor-derecho">
            <div class="empleado-opciones">
            <h1 class="empleado-nombre"> <?php
                 $apellido = explode(' ',$_SESSION['apellidos']) ;
                 $letraApellido = substr($_SESSION['apellidos'],0, 1);
                 $letraApellido = strtoupper($letraApellido);

                 echo strtoupper($_SESSION['nombre']. ' ' .$apellido[0].' '.$letraApellido.'.') ;
                 ?></h1>
                <div class="empleado-items">

                    <a href="../general/calificar.php" class="empleado-enlace empleado-enlace-calificar">Calificar</a>
                    <button class="empleado-item" data-bs-toggle="modal" data-bs-target="#inventario">Inventario</button>
                    <button class="empleado-item" data-bs-toggle="modal" data-bs-target="#directorio">Directorio</button>
                    <a class="empleado-enlace" target="_blank" href="https://mail.google.com/mail/u/0/">Correo</a>
                    <a href="../../models/Cerrar.php" class="empleado-enlace-salir">Salir</a>
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
                    // mostramos los articulos que estan a nombre del usuario
                    $inventarios = mostrarInventario($_SESSION['id'], $conexion);
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
                    // mostramos los datos de contacto de todos los usuarios
                    $areas = mostrarAreaDirectorio($conexion);
                    while ($area = mysqli_fetch_array($areas)) {
                        echo "<details>";
                        echo "<summary class='pt-4 h5'>" . $area['nombre'] . "</summary>";
                        $directorios = mostrarDirectorio($area['codigo'], $conexion);
                        while ($directorio = mysqli_fetch_array($directorios)) {
                            $apellido = explode(' ',$directorio['apellidos']) ;
                            $letraApellido = substr($directorio['apellidos'],0, 1);
                            $letraApellido = strtoupper($letraApellido);
                            $nombreCompleto = strtoupper($directorio['nombre']. ' ' .$apellido[0].' '.$letraApellido.'.') ;

                    ?>
                            <hr>
                            <p class="empleados-p-directorio">
                                <span> <b> <?php echo $nombreCompleto ?> </b> </span>
                                <span> <b>EM :</b> <?php echo $directorio['correo'] ?> </span>
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