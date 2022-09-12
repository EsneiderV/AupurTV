<?php
session_start();
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 

if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] != 2) {
        echo '<script type="text/javascript">
                window.location.href="../../index.php";
                </script>';
    }
} else {
    echo '<script type="text/javascript">
                 window.location.href="../../index.php";
                </script>';
}

$personas = mostarInventarioAreaPersona($_SESSION['area'], $conexion);
$personasM = mostarInventarioAreaPersona($_SESSION['area'], $conexion);
$idPersonas = mostarUsuarioArea($_SESSION['area'], $conexion);
$articulos = mostarUsuarioArea($_SESSION['area'], $conexion);



if (isset($_POST['modificar'])) {
    $cod = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $area = $_SESSION['area'];
    $id_responsable = $_POST['responsable'];

    modificarInventarioAreaProducto($cod, $nombre, $estado, $id_responsable, $area, $conexion);
}
?>

<!DOCTYPE html>
<html lang="es" class="inventarioArea-html">

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
    <title>Inventario Área - Aupur Televisión</title>
</head>

<body class="inventarioArea-body">

    <!-- Navegador -->
    <div class="inventarioArea-div-nav">
        <a href="../supervisor/supervisor.php" class="inventarioArea-volver"> ᗕ Atrás</a>
        <h1 class="inventarioArea-titulo">Inventario área</h1>
    </div>

    <!-- Contenedor de contenedorcitos de cada persona y area -->
    <div class="inventarioAreaDirector-contenedor">

        <div class="inventarioAreaDirector-contenedor-empleados">
            <div class="inventarioAreaDirector-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre"> <?php 
                    if ($_SESSION['area'] == '2') {
                        echo 'Area Tecnica';
                    }elseif($_SESSION['area'] == '3'){
                        echo 'Area Canal';
                    }
                ?></h4>
            </div>

            <div class="inventarioAreaDirector-contenedor-abrirInventario">
                <a href="inventarioArea.php?identifierArea=<?php echo $_SESSION['area'] ?>" class="inventarioArea-abrirInventario">Inventario</a>
            </div>


        </div>

        <?php
        while ($persona = mysqli_fetch_array($personas)) {
        ?>
            <div class="inventarioAreaDirector-contenedor-empleados">

                <div class="inventarioAreaDirector-contenedor-img">
                    <img class="inventarioArea-img  rounded-circle " src="data:<?php echo $persona['tipo_imagen'] ?>;base64,<?php echo base64_encode($persona['imagen']) ?>" alt="foto de perfil">
                </div>

                <div class="inventarioArea-contenedor-nombre">
                    <h4 class="inventarioArea-nombre"> <?php
                     $nombre = explode(' ', $persona['nombre']);
                     $apellido = explode(' ', $persona['apellidos']);

                     echo $nombre[0] . ' ' . $apellido[0];
                    ?> </h4>
                </div>

                <div class="inventarioAreaDirector-contenedor-abrirInventario">
                    <a href="inventarioArea.php?identifier=<?php echo $persona['id'] ?>" class="inventarioArea-abrirInventario">Inventario</a>
                </div>
            </div>
        <?php
        }
        ?>

    </div>



    <!-- Inventario por cada persona -->
    <?php
    if (isset($_GET['identifier'])) {
        $usuario = mostarUsuarioCalificacionAreaEmergente($_GET['identifier'], $conexion);
        $personaNombre = mysqli_fetch_array($usuario);
        $items = mostarInventarioAreaProducto($_GET['identifier'], $conexion)
    ?>
        <div class="inventarioAreaGerente-emergente-contenedor">

            <div class="inventarioAreaGerente-emergente-indivudual">
                <div class="invetarioArea-emergente-navar">
                    <?php
                    $nombre = explode(' ', $personaNombre['nombre']);
                    $apellido = explode(' ', $personaNombre['apellidos']);


                    echo "<h2 class='invetarioArea-emergente-nombre'>" . $nombre[0] . ' ' . $apellido[0] . "</h2>"
                    ?>
                    <a href="inventarioArea.php" class="inventarioAreaGerente-emergente-x">X</a>
                </div>

                <div class="invetarioArea-emergente-main">
                    <hr>
                    <?php
                    while ($item = mysqli_fetch_array($items)) {
                    ?>

                        <div class="invetarioArea-emergente-main-contenedor-item">
                            <p class="invetarioArea-emergente-main-item">
                                <span>
                                    <?php echo $item['cod'] ?>
                                </span>

                                <span>
                                    <?php echo $item['nombre'] ?>
                                </span>

                                <span>
                                    <?php echo $item['estado'] ?>
                                </span>
                            </p>

                            <div class="invetarioArea-emergente-main-contenedor-btn">
                            <?php
                                $nombre1 = explode(' ', $item['nombre_responsable']);
                                $apellido1 = explode(' ', $item['apellido_responsable']);
                               $nombreC = $nombre1[0].' '.$apellido1[0];
                                
                                ?>
                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $nombreC ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL"></i>
                                </button>

                               
                            </div>


                        </div>
                        <hr>
                    <?php
                    }
                    ?>
                </div>

                <div class="invetarioArea-emergente-footer">
                    <a href="inventarioArea.php" class="btn btn-secondary invetarioArea-emergente-footer-btn">Cerrar</a>
                </div>
            </div>

        </div>
    <?php
    }
    ?>

    <!-- Inventario por cada area -->
    <?php
    if (isset($_GET['identifierArea'])) {
        $area = mostrarInventarioPorArea($_GET['identifierArea'], $conexion);
        $areaNombre = mostrarAreaPorCodigo($_GET['identifierArea'], $conexion);
        $areaTitulo = mysqli_fetch_array($areaNombre);
    ?>
        <style>
            .inventarioArea-html {
                overflow: hidden;
            }
        </style>
        <div class="inventarioAreaGerente-emergente-contenedor">

            <div class="inventarioAreaGerente-emergente">
                <div class="invetarioArea-emergente-navar">
                    <?php
                    echo "<h2 class='invetarioArea-emergente-nombre'>" . $areaTitulo['nombre'] . "</h2>"
                    ?>
                    <a href="inventarioArea.php" class="inventarioAreaGerente-emergente-x">X</a>
                </div>

                <div class="invetarioArea-emergente-main">
                    <hr>
                    <?php
                    while ($item = mysqli_fetch_array($area)) {
                    ?>

                        <div class="invetarioArea-emergente-main-contenedor-item">
                            <p class="invetarioArea-emergente-main-item">
                                <span class="span-items">
                                    <?php echo $item['cod'] ?>
                                </span>

                                <span class="span-items">
                                    <?php echo $item['nombre'] ?>
                                </span>

                                <span class="span-items">
                                    <?php echo $item['estado'] ?>
                                </span>

                                <span class="span-items">
                                    <?php echo $item['nombre_responsable'] . ' ' . $item['apellido_responsable'] ?>
                                </span>
                            </p>

                            <div class="invetarioArea-emergente-main-contenedor-btn">
                                <?php
                                $nombre1 = explode(' ', $item['nombre_responsable']);
                                $apellido1 = explode(' ', $item['apellido_responsable']);
                               $nombreC = $nombre1[0].' '.$apellido1[0];
                                
                                ?>
                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $nombreC  ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL"></i>
                                </button>

                            </div>


                        </div>
                        <hr>
                    <?php
                    }
                    $itemsG = mostrarInventarioPorAreaGeneral($_GET['identifierArea'], $conexion);
                    while ($itemG = mysqli_fetch_array($itemsG)) {
                    ?>

                        <div class="invetarioArea-emergente-main-contenedor-item">
                            <p class="invetarioArea-emergente-main-item">
                                <span class="span-items">
                                    <?php echo $itemG['cod'] ?>
                                </span>

                                <span class="span-items">
                                    <?php echo $itemG['nombre'] ?>
                                </span>

                                <span class="span-items">
                                    <?php echo $itemG['estado'] ?>
                                </span>

                                <span class="span-items">
                                    <?php if ($itemG['id_responsable'] == 'Noasignado') {
                                        echo 'No asignado';
                                    } else {
                                        echo $itemG['id_responsable'];
                                    }
                                    ?>
                                </span>
                            </p>

                            <div class="invetarioArea-emergente-main-contenedor-btn">

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $itemG['nombre_area'] ?>" data-areaid="<?php echo $itemG['area'] ?>" data-cod="<?php echo $itemG['cod'] ?>" data-nombre="<?php echo $itemG['nombre'] ?>" data-estado="<?php echo $itemG['estado'] ?>" data-responsableid="<?php echo $itemG['id_responsable'] ?>" data-responsablenom="No asignado">
                                    <i class="fa-solid fa-pen-to-square modificarL"></i>
                                </button>
                            </div>


                        </div>
                        <hr>
                    <?php
                    }
                    ?>
                </div>

                <div class="invetarioArea-emergente-footer">
                    <a href="../../pdf/pdf-inventario.php?area=<?php echo $_GET['identifierArea'] ?>" class="btn btn-secondary invetarioArea-emergente-footer-btn">Descargar</a>
                    <a href="inventarioArea.php" class="btn btn-secondary invetarioArea-emergente-footer-btn">Cerrar</a>
                </div>
            </div>

        </div>
    <?php
    }
    ?>




    <!-- Modal para el boton de modificar articulos -->
    <div class="modal fade" id="articulosModificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modf-Art" id="exampleModalLabel"> MODIFICAR ARTÍCULO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="inventarioArea-items-modificar" action="" method="POST">
                        <input autocomplete="off" readonly id="codigoM" value="2" class="inventarioArea-item-formulario-modf" type="text" name="codigo" placeholder="Código"> <br>

                        <input autocomplete="off" id="nombreM" class="inventarioArea-item-formulario-modf" type="text" name="nombre" placeholder="Nombre"> <br>

                        <select name="estado" id="estadoM" class="inventarioArea-item-formulario-modf">
                            <option value="" id="option-default-estado"></option>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                        </select> <br>


                        <select id="select-agregar-empleado-modi" class="inventarioArea-item-formulario-modf" name="responsable">
                            <option value="" id="option-default-encargado"></option>
                            <?php
                            while ($personaM = mysqli_fetch_array($personasM)) {
                                $nombre = explode(' ', $personaM['nombre']);
                                $apellido = explode(' ', $personaM['apellidos']);

                                echo "<option  value=" . $personaM['id'] . ">" . $nombre[0] . ' ' . $apellido[0] . "</option>";
                            }
                            ?>
                            <option value="Noasignado" id="option-default-encargado">No asignado</option>

                        </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="modificar" class="btn btn-primary">Modificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>

</html>

<script>


    const div = document.querySelector('.inventarioAreaGerente-emergente-contenedor')

    div.addEventListener("click", e => {
        if (e.target.classList.contains("modificar") || e.target.classList.contains("modificarL")) {

            const btn = (e.target.classList.contains("modificar")) ? e.target : e.target.parentNode;

            document.querySelector('#codigoM').value = btn.dataset.cod
            document.querySelector('#nombreM').value = btn.dataset.nombre
            document.querySelector('#option-default-estado').value = btn.dataset.estado
            document.querySelector('#option-default-estado').textContent = btn.dataset.estado
            document.querySelector('#option-default-encargado').value = btn.dataset.responsableid
            document.querySelector('#option-default-encargado').textContent = btn.dataset.responsablenom

        }
    })
</script>