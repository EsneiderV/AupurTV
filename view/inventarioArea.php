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

$personas = mostarInventarioAreaPersona($_SESSION['area'], $conexion);

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
    <title>Inventario Area</title>
</head>

<body>
    <h1>Inventario Area</h1>

    <div class="inventarioArea-contenedor">
    <?php
    while ($persona = mysqli_fetch_array($personas)) {
    ?>
        <div class="inventarioArea-contenedor-producto">
            <h4> <?php echo $persona['nombre_responsable'] ?> </h4>
                <?php
               $productos = mostarInventarioAreaProducto($persona['id_responsable'],$conexion);
                while ($producto = mysqli_fetch_array($productos)) {
                ?>
                <div class="inventarioArea-div-producto">
                    <p class="inventarioArea-p-producto">
                    <span>
                    <?php echo $producto['cod']?>
                    </span>
                    <span>
                    <?php echo $producto['nombre']?>
                    </span>
                    <span>
                    <?php echo $producto['estado']?>
                    </span>
                    </p> 

                    <div>
                        <span>Boton</span>
                        <span>Boton</span>
                    </div>

                    </div>
                <?php
                }
                ?>
                </div>
                <?php
    }
    
    ?>
    </div>


</body>

</html>