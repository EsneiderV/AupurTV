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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <title>Empleado</title>
</head>

<body class="empleado-body">

    <div class="div-imagen">
        <img class="empleado-foto" src="../IMG-20190806-WA0020.jpg" alt="foto de perfil">
    </div>


    <div class="empleado-opciones">
        <h1>    <?php echo $_SESSION['nombre'] ?></h1>
        <div class="empleado-items">
            <button class="empleado-item" data-bs-toggle="modal" data-bs-target="#datosPersonales">Datos personales</button>
            <button class="empleado-item">
                <a href="calificar.php" class="empleado-enlace">Calificar</a>
            </button>
            <button class="empleado-item">Mi inventario</button>
            <button class="empleado-item">Mi directorio</button>
            <button class="empleado-item">Mi correo</button>
            <a href="../models/Cerrar.php" class="empleado-enlace">Cerrar sesion</a>
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




</body>

</html>