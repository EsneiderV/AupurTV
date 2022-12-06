<?php
session_start();
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';
if (isset($_SESSION['rol'])) {
    if ($_SESSION['admi'] != 1) {
        echo '<script type="text/javascript">
                window.location.href="../../index.php";
                </script>';
    }
} else {
    echo '<script type="text/javascript">
                window.location.href="../../index.php";
                </script>';
}
?>


<!DOCTYPE html>
<html lang="es" class="admi-html">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="../../controllers/bootstrap/bootstrap.min.css">
    <script src="../../controllers/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../controllers/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/510f22fb48.js" crossorigin="anonymous"></script>
    <title> <?php echo $_SESSION['nombre'] ?> - Aupur Televisión </title>
</head>

<body class="admi-body">
    <main class="admi-main">
        <div class="admi-contenedor-izquierdo">

            <div class="admi-div-volver-atras">
                <?php
                if ($_SESSION['rol'] == 1) {
                ?>
                    <a href="../empleado/empleado.php" class="admi-volver-atras"> ᗕ Atrás</a>
                <?php
                } else {
                ?>
                    <a href="../supervisor/supervisor.php" class="admi-volver-atras"> ᗕ Atrás</a>
                <?php
                }
                ?>
            </div>


            <div class="admi-div-opciones">
                <a href="administrador.php?option=1" class="admi-enlaces-opciones">Empleados</a>
                <a href="administrador.php?option=2" class="admi-enlaces-opciones">Crear usuario</a>
                <a href="administrador.php?option=3" class="admi-enlaces-opciones">Empleado del mes</a>
            </div>
        </div>

        <div class="admi-contenedor-principal">
            <?php
            if (isset($_GET['option'])) {
                $redireccionar = $_GET['option'];
                switch ($redireccionar) {
                    case '1':
                        include_once 'admiEmpleados.php';
                        break;
                    case '2':
                        include_once 'admiCrearEmpleado.php';
                        break;
                    case '3':
                        include_once 'admiEmpleadoDelMes.php';
                        break;

                    default:
                        include_once 'admiEmpleados.php';
                        break;
                }
            } else {
                include_once 'admiEmpleados.php';
            }
            ?>
        </div>
    </main>
</body>

</html>