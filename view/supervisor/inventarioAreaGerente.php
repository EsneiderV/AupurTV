<?php
session_start();
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 

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

$personas = mostarInventarioAreaPersona($_SESSION['area'], $conexion);
$idPersonas = mostarUsuarioArea($_SESSION['area'], $conexion);
$articulos = mostarUsuarioArea($_SESSION['area'], $conexion);

// eliminar articulo
if (isset($_GET['eliminar'])) {
    $cod = $_GET['eliminar'];
    eliminarProducto($cod, $conexion);
}

if (isset($_POST['agregar'])) {
    $cod = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    if (isset($_POST['responsable'])) {
        $id_responsable = $_POST['responsable'];
    } else {
        $id_responsable = 'Noasignado';
    }

    $area = $_POST['area'];
    $consultaProducto = consultarProducto($cod, $conexion);
    if ($consultaProducto->num_rows > 0) {
        echo '<script type="text/javascript">
        alert("El codigo del articulo ya esta registrado");
        window.location.href="inventarioArea.php";
        </script>';
    } else {
        insertarInventarioAreaProducto($cod, $nombre, $estado, $id_responsable, $area, $conexion);
        echo '<script type="text/javascript">
            window.location.href="inventarioAreaGerente.php";
            </script>';
    }
}

if (isset($_POST['modificar'])) {
    $cod = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $id_responsable = $_POST['responsable'];

    modificarInventarioAreaProducto($cod, $nombre, $estado, $id_responsable, $conexion);
    echo '<script type="text/javascript">
    window.location.href="inventarioAreaGerente.php";
    </script>';
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

    <!-- Agregar inventario -->
    <div class="inventarioArea-agregar-articulo">
        <h3 class="text-center titulo-agregar">AGREGAR</h3>
        <form action="" class="inventarioArea-formulario" method="POST" id="formulario">
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="codigo" placeholder="Código artículo" required>
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="nombre" placeholder="Nombre artículo" required>
            <select class="inventarioArea-item-formulario" id="select-agregar-estado" name="estado" required>
                <option class="option-select" value="0" default>Estado...</option>
                <option class="option-select" value="Bueno">Bueno</option>
                <option class="option-select" value="Regular">Regular</option>
                <option class="option-select" value="Malo">Malo</option>
            </select>
            <select class="inventarioArea-item-formulario" id="select-agregar-area" name="area" required>
                <option class="option-select" value="0">Areas</option>
                <?php
                $areasS = mostrarAreaDirectorio($conexion);
                while ($areaS = mysqli_fetch_array($areasS)) {
                    echo "<option class='option-select' value=" . $areaS['codigo'] . ">" . $areaS['nombre'] . "</option>";
                }
                ?>
                <option class="option-select" value="Noasignado">No asignado</option>
            </select>

            <select class="inventarioArea-item-formulario" id="select-agregar-empleado" name="responsable" required>
                <option class="option-select" id="option-empleado" value="0">Empleados</option>
            </select>
            <button class="inventarioArea-item-formulario-agregar" type="submit" name="agregar">Agregar</button>
        </form>
    </div>

    <!-- Navegador -->
    <div class="inventarioArea-div-nav">
        <a href="../supervisor/supervisor.php" class="inventarioArea-volver"> ᗕ Atrás</a>
        <h1 class="inventarioArea-titulo">Inventario área</h1>
    </div>

    <!-- Contenedor de contenedorcitos de cada persona y area -->
    <div class="inventarioArea-contenedor">

        <!-- Inventario general de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre"> General </h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <a href="inventarioAreaGerente.php?identifierGeneral" class="inventarioArea-abrirInventario">Inventario</a>
            </div>
        </div>

        <!-- Inventario del area de administracion de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre"> Area Administrativa </h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <a href="inventarioAreaGerente.php?identifierArea=1" class="inventarioArea-abrirInventario">Inventario</a>
            </div>
        </div>

        <!-- Inventario del area de tecnica de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre"> Area Tecnica </h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <a href="inventarioAreaGerente.php?identifierArea=2" class="inventarioArea-abrirInventario">Inventario</a>
            </div>
        </div>

        <!-- Inventario del area de canal de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre"> Area Canal </h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <a href="inventarioAreaGerente.php?identifierArea=3" class="inventarioArea-abrirInventario">Inventario</a>
            </div>
        </div>

        <?php
        while ($persona = mysqli_fetch_array($personas)) {
        ?>
            <div class="inventarioArea-contenedor-empleados">
                <div class="inventarioArea-contenedor-img">
                    <img class="inventarioArea-img  rounded-circle " src="data:<?php echo $persona['tipo_imagen'] ?>;base64,<?php echo base64_encode($persona['imagen']) ?>" alt="foto de perfil">
                </div>

                <div class="inventarioArea-contenedor-nombre">
                    <h4 class="inventarioArea-nombre"> <?php echo $persona['nombre'] ?> </h4>
                </div>

                <div class="inventarioArea-contenedor-abrirInventario">
                    <a href="inventarioAreaGerente.php?identifier=<?php echo $persona['id'] ?>" class="inventarioArea-abrirInventario">Inventario</a>
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
                    echo "<h2 class='invetarioArea-emergente-nombre'>" . $personaNombre['nombre'] . "</h2>"
                    ?>
                    <a href="inventarioAreaGerente.php" class="inventarioAreaGerente-emergente-x">X</a>
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

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL"></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>&identifier=<?php echo $_GET['identifier'] ?>">
                                </a>
                            </div>


                        </div>
                        <hr>
                    <?php
                    }
                    ?>
                </div>

                <div class="invetarioArea-emergente-footer">
                    <a href="inventarioAreaGerente.php" class="btn btn-secondary invetarioArea-emergente-footer-btn">Cerrar</a>
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
                    <a href="inventarioAreaGerente.php" class="inventarioAreaGerente-emergente-x">X</a>
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
                                    <?php echo $item['nombre_responsable'] ?>
                                </span>
                            </p>

                            <div class="invetarioArea-emergente-main-contenedor-btn">

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL"></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>&identifierArea=<?php echo $_GET['identifierArea']?>">
                                </a>
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

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL"></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $itemG['cod'] ?>&identifierArea=<?php echo $_GET['identifierArea']?>">
                                </a>
                            </div>


                        </div>
                        <hr>
                    <?php
                    }
                    ?>
                </div>

                <div class="invetarioArea-emergente-footer">
                    <a href="inventarioAreaGerente.php" class="btn btn-secondary invetarioArea-emergente-footer-btn">Cerrar</a>
                </div>
            </div>

        </div>
    <?php
    }
    ?>

    <!-- Inventario general -->
    <?php
    if (isset($_GET['identifierGeneral'])) {
        $areas = mostrarAreaDirectorio($conexion);

    ?>
        <div class="inventarioAreaGerente-emergente-contenedor">

            <div class="inventarioAreaGerente-emergente">
                <div class="invetarioArea-emergente-navar">
                    <?php
                    echo "<h1 class='invetarioArea-emergente-nombre'> General </h1>"
                    ?>
                    <a href="inventarioAreaGerente.php" class="inventarioAreaGerente-emergente-x">X</a>
                </div>

                <div class="invetarioArea-emergente-main">
                    <?php
                    $sinAsignar = mostrarInventarioPorAreaGeneralNoA($conexion);
                    if ($sinAsignar->num_rows > 0) {
                        echo "<hr>";
                        echo "<h2 class='inventarioAreaGerenteTituloArea'>No asignados</h2>";
                        echo "<hr>";
                        while ($sinAsignarItem = mysqli_fetch_array($sinAsignar)) {
                    ?>
                            <div class="invetarioArea-emergente-main-contenedor-item">
                                <p class="invetarioArea-emergente-main-item">
                                    <span class="span-items">
                                        <?php echo $sinAsignarItem['cod'] ?>
                                    </span>

                                    <span class="span-items">
                                        <?php echo $sinAsignarItem['nombre'] ?>
                                    </span>

                                    <span class="span-items">
                                        <?php echo $sinAsignarItem['estado'] ?>
                                    </span>

                                    <span class="span-items">
                                        <?php if ($sinAsignarItem['area'] == 'Noasignado') {
                                            echo 'No asignado';
                                        } else {
                                            echo $sinAsignarItem['area'];
                                        }
                                        ?>
                                    </span>
                                </p>

                                <div class="invetarioArea-emergente-main-contenedor-btn">

                                    <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                        <i class="fa-solid fa-pen-to-square modificarL"></i>
                                    </button>

                                    <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $sinAsignarItem['cod'] ?>&identifierGeneral=<?php echo $_GET['identifierGeneral'] ?>">
                                    </a>
                                </div>


                            </div>
                            <hr>

                    <?php
                        }
                    }
                    ?>

                    <hr>
                    <?php
                    while ($area = mysqli_fetch_array($areas)) {
                        echo "<h2 class='inventarioAreaGerenteTituloArea'>" . $area['nombre'] . "</h2>";
                        echo "<hr>";
                        $items = mostrarInventarioPorArea($area['codigo'], $conexion);
                        while ($item = mysqli_fetch_array($items)) {
                    ?>
                            <style>
                                .inventarioArea-html {
                                    overflow: hidden;
                                }
                            </style>
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
                                        <?php echo $item['nombre_responsable'] ?>
                                    </span>
                                </p>

                                <div class="invetarioArea-emergente-main-contenedor-btn">

                                    <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                        <i class="fa-solid fa-pen-to-square modificarL"></i>
                                    </button>

                                    <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>&identifierGeneral=<?php echo $_GET['identifierGeneral']?>">
                                    </a>
                                </div>


                            </div>
                            <hr>
                        <?php
                        }
                        $itemsG = mostrarInventarioPorAreaGeneral($area['codigo'], $conexion);
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

                                    <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                        <i class="fa-solid fa-pen-to-square modificarL"></i>
                                    </button>

                                    <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $itemG['cod'] ?>&identifierGeneral=<?php echo $_GET['identifierGeneral']?>">
                                    </a>
                                </div>


                            </div>
                            <hr>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="invetarioArea-emergente-footer">
                    <a href="inventarioAreaGerente.php" class="btn btn-secondary invetarioArea-emergente-footer-btn">Cerrar</a>
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
                        <input autocomplete="off" readonly id="codigo" class="inventarioArea-item-formulario-modf" type="number" name="codigo" placeholder="Código" readonly> <br>

                        <input autocomplete="off" id="nombre" class="inventarioArea-item-formulario-modf" type="text" name="nombre" placeholder="Nombre"> <br>
                        
                        <input autocomplete="off" id="estado" class="inventarioArea-item-formulario-modf" type="text" name="estado" placeholder="Estado"> <br>
                        
                        <select class="inventarioArea-item-formulario-modf modf" name="responsable">
                            <option id="optionRes">Areas</option>
                            <?php
                            while ($articulo = mysqli_fetch_array($articulos)) {
                                echo "<option value=" . $articulo['id_responsable'] . ">" . $articulo['nombre_responsable'] . "</option>";
                            }
                            ?>
                        </select>

                        <select class="inventarioArea-item-formulario-modf" name="responsable">
                            <option id="optionRes">Empleados</option>
                            <?php
                            while ($articulo = mysqli_fetch_array($articulos)) {
                                echo "<option value=" . $articulo['id_responsable'] . ">" . $articulo['nombre_responsable'] . "</option>";
                            }
                            ?>
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


    <script src="../../controllers/js/jquery-1.10.2.min.js"></script>
    <script>
        const selectArea = document.querySelector('#select-agregar-area');
        const selectEmpleado = document.querySelector('#select-agregar-empleado')
        const optionEmpleado = document.querySelector('#option-empleado')
        selectArea.addEventListener('input', e => {
            if (selectArea.value == 'Noasignado') {
                selectEmpleado.disabled = true
                selectEmpleado.classList.add('inventario-empleado-desable');
            } else {
                selectEmpleado.disabled = false
                selectEmpleado.classList.remove('inventario-empleado-desable');
                $(document).ready(function() {

                    var empleados = $('#select-agregar-empleado');

                    $('#select-agregar-area').change(function() {
                        var id_area = $(this).val();

                        $.ajax({
                            data: {
                                id_area: id_area
                            },
                            dataType: 'html',
                            type: 'POST',
                            url: 'get_empleados.php',
                        }).done(function(data) {
                            empleados.html(data);
                        });

                    });

                });
            }


        })
    </script>

</body>

</html>

<script>
    function eliminar() {
        let respuesta = confirm('¿Está seguro de que desea eliminar este articulo de forma permanente?');

        if (respuesta) {
            return true
        } else {
            return false
        }
    }

    const div = document.querySelector('.inventarioArea-contenedor')
    div.addEventListener("click", e => {
        if (e.target.classList.contains("modificar")) {
            const btn = e.target;
            document.querySelector('#codigo').value = btn.dataset.cod
            document.querySelector('#nombre').value = btn.dataset.nombre
            document.querySelector('#estado').value = btn.dataset.estado
            document.querySelector('#optionRes').value = `${btn.dataset.responsableid}`;
            document.querySelector('#optionRes').textContent = `${btn.dataset.responsablenom}`;

        }

        if (e.target.classList.contains("modificarL")) {
            const btn = e.target.parentNode;
            document.querySelector('#codigo').value = btn.dataset.cod
            document.querySelector('#nombre').value = btn.dataset.nombre
            document.querySelector('#estado').value = btn.dataset.estado
            document.querySelector('#optionRes').value = `${btn.dataset.responsableid}`
            document.querySelector('#optionRes').textContent = `${btn.dataset.responsablenom}`
        }

    })

    const form = document.querySelector('#formulario')
    const btn = document.querySelector('.inventarioArea-item-formulario-agregar')


    btn.addEventListener('click', e => {

        const selectES = document.querySelector('#select-agregar-estado').value
        if (selectES == 0) {
            e.preventDefault();
            alert('Por favor seleccione estado')
        }


        const select = document.querySelector('#select-agregar-area').value
        if (select == 0) {
            e.preventDefault();
            alert('Por favor seleccione area')
        }

        const selectA = document.querySelector('#select-agregar-area').value
        const selectE = document.querySelector('#select-agregar-empleado').value
        if (selectA != 'Noasignado' && selectE == 0) {
            e.preventDefault();
            alert('Por favor seleccione empleado')
        }

    })
</script>