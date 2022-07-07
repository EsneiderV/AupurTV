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
$idPersonas = mostarInventarioAreaPersona($_SESSION['area'], $conexion);

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
    <script src="https://kit.fontawesome.com/510f22fb48.js" crossorigin="anonymous"></script>
    <title>Inventario Area</title>
</head>

<body>
    <div class="inventarioArea-agregar-articulo">
        <h3 class="text-center">AGREGAR</h3>
        <form action="" class="inventarioArea-formulario">
            <input class="inventarioArea-item-formulario" type="number" name="codigo" placeholder="Codigo articulo">
            <input class="inventarioArea-item-formulario" type="text" name="nombre" placeholder="Nombre articulo">
            <input class="inventarioArea-item-formulario" type="text" name="estado" placeholder="Estado articulo">
            <select class="inventarioArea-item-formulario" name="responsable">
                <option>Seleccione...</option>
            <?php
                while ($idPersona = mysqli_fetch_array($idPersonas)) {
                    echo "<option value=".$idPersona['id_responsable'].">".$idPersona['nombre_responsable']."</option>";
                }
             ?>
                <option>Otro</option>    
            </select>
            <button class="inventarioArea-item-formulario-agregar" type="submit">Agregar</button>
        </form>
    </div>

    <h1 class="text-center inventarioArea-titulo">Inventario Area</h1>

    <div class="inventarioArea-contenedor">
    <?php
    while ($persona = mysqli_fetch_array($personas)) {
    ?>
        <div class="inventarioArea-contenedor-producto">
            <h4 class="text-center"> <?php echo $persona['nombre_responsable'] ?> </h4>
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
                        <span> <i class="fa-solid fa-pen-to-square"></i> </span>
                        <span> <i class="fa-solid fa-trash-can"></i> </span>
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