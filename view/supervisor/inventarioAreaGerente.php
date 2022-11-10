<?php
session_start();
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 

if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] != 3) {
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
        window.location.href="inventarioAreaGerente.php";
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
    $area = $_POST['area'];
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
    <div class="calificar-nav">
        <a href="../supervisor/supervisor.php" class="calificar-volver-atras"> ᗕ Atrás</a>
        <h1 class="calificar-titulo">Inventario Área</h1>
    </div>


    <!-- Agregar inventario -->
    <div class="inventarioArea-agregar-articulo">
        <h3 class="text-center titulo-agregar">AGREGAR</h3>
        <form action="" class="inventarioArea-formulario" method="POST" id="formulario">
            <div class="agregar-items-nombres-resultado">
                <label class="agregar-items-nombres" for="codigoLabel">Codigo : </label>
                <input id="codigoLabel" autocomplete="off" class="inventarioArea-item-formulario" type="text" name="codigo" placeholder="Código artículo" required>
            </div>
            <div class="agregar-items-nombres-resultado">
                <label class="agregar-items-nombres">Nombre : </label>    
                <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="nombre" placeholder="Nombre artículo" required>
            </div>

            <div class="agregar-items-nombres-resultado">
                <label class="agregar-items-nombres" for="">Estado : </label>
                <select class="inventarioArea-item-formulario" id="select-agregar-estado" name="estado" required>
                    <option class="option-select" value="Bueno">Bueno</option>
                    <option class="option-select" value="Regular">Regular</option>
                    <option class="option-select" value="Malo">Malo</option>
                </select>
            </div>

            <div class="agregar-items-nombres-resultado">
                <label class="agregar-items-nombres" for="">Area : </label>
            <select class="inventarioArea-item-formulario" id="select-agregar-area" name="area" required>
                <option class="option-select" value="Noasignado">No asignado</option>
                <?php
                $areasS = mostrarAreaDirectorio($conexion);
                while ($areaS = mysqli_fetch_array($areasS)) {
                    echo "<option class='option-select' value=" . $areaS['codigo'] . ">" . $areaS['nombre'] . "</option>";
                }
                ?>
            </select>
            </div>
           
            <div class="agregar-items-nombres-resultado">
                <label class="agregar-items-nombres" for="">Empleado : </label>
                <select class="inventarioArea-item-formulario" id="select-agregar-empleado" name="responsable" required>
                <option class='option-select' value="Noasignado"> No asignado </option>
                </select>
            </div>
            <button class="inventarioArea-item-formulario-agregar" type="submit" name="agregar">Agregar</button>
        </form>
    </div>

    <!-- Contenedor de contenedorcitos de cada persona y area -->
    <div id="inventarioAreaContenedorPrincipal" class="inventarioArea-contenedor">

        <!-- Inventario general de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre">General</h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">

                <span id="inventarioArea-abrirInventario-general" class="inventarioArea-abrirInventario">Inventario</span>
            </div>
        </div>

        <!-- Inventario del area de administracion de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre">Administración</h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <span id="inventarioArea-abrirInventario-administracio" class="inventarioArea-abrirInventario">Inventario</span>
            </div>
        </div>

        <!-- Inventario del area de tecnica de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre">Tecnicos</h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <span id="inventarioArea-abrirInventario-Tecnico" class="inventarioArea-abrirInventario">Inventario</span>
            </div>

        </div>

        <!-- Inventario del area de canal de la empresa -->
        <div class="inventarioArea-contenedor-empleados">
            <div class="inventarioArea-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre">Canal</h4>
            </div>

            <div class="inventarioArea-contenedor-abrirInventario">
                <span id="inventarioArea-abrirInventario-Canal" class="inventarioArea-abrirInventario">Inventario</span>
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
                    <h4 class="inventarioArea-nombre">
                        <?php
                        $apellido = explode(' ',$persona['apellidos']) ;
                        $nombreCompleto = $persona['nombre']. ' ' .$apellido[0];
                        $nombreCompleto = ucwords($nombreCompleto);

                        echo $nombreCompleto;
                        ?>
                    </h4>
                </div>

                <div class="inventarioArea-contenedor-abrirInventario">
                    <span id="<?php echo $persona['id'] ?>" class="inventarioArea-abrirInventario inventarioArea-abrirInventario-persona">Inventario</span>
                </div>
            </div>
        <?php
        }
        ?>

    </div>



    <!-- Inventario por cada persona -->
    <?php
    $personasInventarios = mostarInventarioAreaPersona($_SESSION['area'], $conexion);
    while ($personaInventario = mysqli_fetch_array($personasInventarios)) {
        $usuario = mostarUsuarioCalificacionAreaEmergente($personaInventario['id'], $conexion);
        $personaNombre = mysqli_fetch_array($usuario);
        $items = mostarInventarioAreaProducto($personaInventario['id'], $conexion);
    ?>
        <div id="inventarioAreaDirector-abrir-persona-<?php echo $personaInventario['id'] ?>" class="inventarioAreaGerente-emergente-contenedor inventarioAreaDirector-desactivarContenedor inventarioAreaGerente-emergente-contenedor-persona">

            <div class="inventarioAreaGerente-emergente-indivudual">
                <div class="invetarioArea-emergente-navar">
                    <?php
                    $nombre = explode(' ', $personaNombre['nombre']);
                    $apellido = explode(' ', $personaNombre['apellidos']);


                    echo "<h2 class='invetarioArea-emergente-nombre'>" . $nombre[0] . ' ' . $apellido[0] . "</h2>"
                    ?>
                    <span class="inventarioAreaGerente-emergente-x cerrar-persona">X</span>
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

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL etiqueta-i "></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>">
                                </a>
                            </div>


                        </div>
                        <hr>
                    <?php
                    }
                    ?>
                </div>

                <div class="invetarioArea-emergente-footer">
                    <button class="btn btn-secondary invetarioArea-emergente-footer-btn cerrar-persona"> Cerrar</button>
                </div>
            </div>

        </div>
    <?php
    }
    ?>




    <!-- Inventario Administracion -->
    <?php
    $area = mostrarInventarioPorArea(1, $conexion);
    $areaNombre = mostrarAreaPorCodigo(1, $conexion);
    $areaTitulo = mysqli_fetch_array($areaNombre);
    ?>
    <div id="inventarioGerenteContenedorAdministracion" class="inventarioAreaGerente-emergente-contenedor inventarioAreaDirector-desactivarContenedor">

        <div class="inventarioAreaGerente-emergente">
            <div class="invetarioArea-emergente-navar">
                <?php
                echo "<h2 class='invetarioArea-emergente-nombre'>" . $areaTitulo['nombre'] . "</h2>"
                ?>
                <span id="cerrarAdministracionx" class="inventarioAreaGerente-emergente-x">X</span>
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

                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>">
                            </a>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                $itemsG = mostrarInventarioPorAreaGeneral(1, $conexion);
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
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $itemG['cod'] ?>">
                            </a>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                ?>
            </div>

            <div class="invetarioArea-emergente-footer">
                <a href="../../pdf/pdf-inventario.php?area=1" class="btn btn-secondary invetarioArea-emergente-footer-btn">Descargar</a>
                <button id="cerrarAdministracionBtn" class="btn btn-secondary invetarioArea-emergente-footer-btn"> Cerrar</button>
            </div>
        </div>

    </div>


    <!-- Mostrar inventario Tecnicos -->

    <?php
    $area = mostrarInventarioPorArea(2, $conexion);
    $areaNombre = mostrarAreaPorCodigo(2, $conexion);
    $areaTitulo = mysqli_fetch_array($areaNombre);
    ?>
    <div id="inventarioGerenteContenedorTecnico" class="inventarioAreaGerente-emergente-contenedor inventarioAreaDirector-desactivarContenedor">

        <div class="inventarioAreaGerente-emergente">
            <div class="invetarioArea-emergente-navar">
                <?php
                echo "<h2 class='invetarioArea-emergente-nombre'>" . $areaTitulo['nombre'] . "</h2>"
                ?>
                <span id="cerrarTecnicox" class="inventarioAreaGerente-emergente-x">X</span>
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

                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>">
                            </a>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                $itemsG = mostrarInventarioPorAreaGeneral(2, $conexion);
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
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $itemG['cod'] ?>">
                            </a>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                ?>
            </div>

            <div class="invetarioArea-emergente-footer">
                <a href="../../pdf/pdf-inventario.php?area=2" class="btn btn-secondary invetarioArea-emergente-footer-btn">Descargar</a>
                <button id="cerrarTecnicoBtn" class="btn btn-secondary invetarioArea-emergente-footer-btn"> Cerrar</button>
            </div>
        </div>

    </div>


    <!-- Mostrar inventario Canal -->

    <?php
    $area = mostrarInventarioPorArea(3, $conexion);
    $areaNombre = mostrarAreaPorCodigo(3, $conexion);
    $areaTitulo = mysqli_fetch_array($areaNombre);
    ?>

    <div id="inventarioGerenteContenedorCanal" class="inventarioAreaGerente-emergente-contenedor inventarioAreaDirector-desactivarContenedor">

        <div class="inventarioAreaGerente-emergente">
            <div class="invetarioArea-emergente-navar">
                <?php
                echo "<h2 class='invetarioArea-emergente-nombre'>" . $areaTitulo['nombre'] . "</h2>"
                ?>
                <span id="cerrarCanalx" class="inventarioAreaGerente-emergente-x">X</span>
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

                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>">
                            </a>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                $itemsG = mostrarInventarioPorAreaGeneral(3, $conexion);
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
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $itemG['cod'] ?>">
                            </a>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                ?>
            </div>

            <div class="invetarioArea-emergente-footer">
                <a href="../../pdf/pdf-inventario.php?area=3" class="btn btn-secondary invetarioArea-emergente-footer-btn">Descargar</a>
                <button id="cerrarCanalBtn" class="btn btn-secondary invetarioArea-emergente-footer-btn"> Cerrar</button>
            </div>
        </div>

    </div>


    <!-- Inventario general-->
    <?php
    $areas = mostrarAreaDirectorio($conexion);

    ?>
    <div id="inventarioGerenteContenedorGerente" class="inventarioAreaGerente-emergente-contenedor inventarioAreaDirector-desactivarContenedor">

        <div class="inventarioAreaGerente-emergente">
            <div class="invetarioArea-emergente-navar">
                <?php
                echo "<h1 class='invetarioArea-emergente-nombre'> General </h1>"
                ?>

                <span id="cerrarGeneralx" class="inventarioAreaGerente-emergente-x">X</span>
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

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="No asignado" data-areaid="<?php echo $sinAsignarItem['area'] ?>" data-cod="<?php echo $sinAsignarItem['cod'] ?>" data-nombre="<?php echo $sinAsignarItem['nombre'] ?>" data-estado="<?php echo $sinAsignarItem['estado'] ?>" data-responsableid="<?php echo $sinAsignarItem['id_responsable'] ?>" data-responsablenom="No asignado">
                                    <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $sinAsignarItem['cod'] ?>">
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

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                    <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $item['cod'] ?>">
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

                                <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $itemG['nombre_area'] ?>" data-areaid="<?php echo $itemG['area'] ?>" data-cod="<?php echo $itemG['cod'] ?>" data-nombre="<?php echo $itemG['nombre'] ?>" data-estado="<?php echo $itemG['estado'] ?>" data-responsableid="<?php echo $itemG['id_responsable'] ?>" data-responsablenom="No asignado">
                                    <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                                </button>

                                <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioAreaGerente.php?eliminar=<?php echo $itemG['cod'] ?>">
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
                <a href="../../pdf/pdf-inventario.php?area=0" class="btn btn-secondary invetarioArea-emergente-footer-btn">Descargar</a>
                <button id="cerrarGeneralBtn" class="btn btn-secondary invetarioArea-emergente-footer-btn"> Cerrar</button>
            </div>
        </div>

    </div>

    <?php

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

                        <select id="select-agregar-area-modi" class="inventarioArea-item-formulario-modf modf" name="area">
                            <option id="option-default-area"></option>
                            <?php
                            $areasSM = mostrarAreaDirectorio($conexion);
                            while ($areaSM = mysqli_fetch_array($areasSM)) {
                                echo "<option  value=" . $areaSM['codigo'] . ">" . $areaSM['nombre'] . "</option>";
                            }
                            ?>
                            <option value="Noasignado">No asignado</option>
                        </select>

                        <select id="select-agregar-empleado-modi" class="inventarioArea-item-formulario-modf" name="responsable">

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
        //AGREAGAR

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


        //abrir por persona
        const inventarioAreaContenedorPrincipal = document.querySelector('#inventarioAreaContenedorPrincipal')
        inventarioAreaContenedorPrincipal.addEventListener('click', e => {
            if (e.target.classList.contains('inventarioArea-abrirInventario-persona')) {
                idContenedor = e.target.id;
                const contenedorAbrir = document.querySelector(`#inventarioAreaDirector-abrir-persona-${idContenedor}`)
                contenedorAbrir.classList.remove('inventarioAreaDirector-desactivarContenedor')
            }

        })

        const cerrar = document.querySelectorAll('.inventarioAreaGerente-emergente-contenedor-persona')
        cerrar.forEach(contenedor => {
            contenedor.addEventListener('click', e => {
                if (e.target.classList.contains('cerrar-persona')) {
                    contenedor.classList.add('inventarioAreaDirector-desactivarContenedor')
                }
            })
        })



        // abrir general
        const AbrirGenetal = document.querySelector('#inventarioArea-abrirInventario-general');
        const contenedorGeneral = document.querySelector('#inventarioGerenteContenedorGerente');
        const cerrarGeneralBtn = document.querySelector('#cerrarGeneralBtn');
        const cerrarGeneralx = document.querySelector('#cerrarGeneralx');
        AbrirGenetal.addEventListener('click', e => {
            contenedorGeneral.classList.remove('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarGeneralBtn.addEventListener('click', e => {
            contenedorGeneral.classList.add('inventarioAreaDirector-desactivarContenedor')
        })

        cerrarGeneralx.addEventListener('click', e => {
            contenedorGeneral.classList.add('inventarioAreaDirector-desactivarContenedor')
        })

        // abrir Administracion
        const AbrirAdministracion = document.querySelector('#inventarioArea-abrirInventario-administracio');
        const contenedorAdministracion = document.querySelector('#inventarioGerenteContenedorAdministracion');
        const cerrarAdministracionBtn = document.querySelector('#cerrarAdministracionBtn');
        const cerrarAdministracionx = document.querySelector('#cerrarAdministracionx');
        AbrirAdministracion.addEventListener('click', e => {
            contenedorAdministracion.classList.remove('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarAdministracionBtn.addEventListener('click', e => {
            contenedorAdministracion.classList.add('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarAdministracionx.addEventListener('click', e => {
            contenedorAdministracion.classList.add('inventarioAreaDirector-desactivarContenedor')
        })

        // abrir Tecnico
        const AbrirTecnico = document.querySelector('#inventarioArea-abrirInventario-Tecnico');
        const contenedorTecnico = document.querySelector('#inventarioGerenteContenedorTecnico');
        const cerrarTecnicoBtn = document.querySelector('#cerrarTecnicoBtn');
        const cerrarTecnicox = document.querySelector('#cerrarTecnicox');
        AbrirTecnico.addEventListener('click', e => {
            contenedorTecnico.classList.remove('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarTecnicoBtn.addEventListener('click', e => {
            contenedorTecnico.classList.add('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarTecnicox.addEventListener('click', e => {
            contenedorTecnico.classList.add('inventarioAreaDirector-desactivarContenedor')
        })

        // abrir Canal
        const AbrirCanal = document.querySelector('#inventarioArea-abrirInventario-Canal');
        const contenedorCanal = document.querySelector('#inventarioGerenteContenedorCanal');
        const cerrarCanalBtn = document.querySelector('#cerrarCanalBtn');
        const cerrarCanalx = document.querySelector('#cerrarCanalx');
        AbrirCanal.addEventListener('click', e => {
            contenedorCanal.classList.remove('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarCanalBtn.addEventListener('click', e => {
            contenedorCanal.classList.add('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarCanalx.addEventListener('click', e => {
            contenedorCanal.classList.add('inventarioAreaDirector-desactivarContenedor')
        })


        //modificar
        function eliminar() {
            let respuesta = confirm('¿Está seguro de que desea eliminar este articulo de forma permanente?');

            if (respuesta) {
                return true
            } else {
                return false
            }
        }

        const div = document.querySelectorAll('.inventarioAreaGerente-emergente-contenedor')

        div.forEach(cont => {
            cont.addEventListener('click', e => {
                if (e.target.classList.contains("modificar") || e.target.classList.contains("modificarL")) {
                    buttonComponents = e.target.classList.contains('etiqueta-i') ? e.target.parentNode : e.target;
                    const btn = (e.target.classList.contains("modificar")) ? e.target : e.target.parentNode;
                    document.querySelector('#codigoM').value = btn.dataset.cod
                    document.querySelector('#nombreM').value = btn.dataset.nombre
                    document.querySelector('#option-default-estado').value = btn.dataset.estado
                    document.querySelector('#option-default-estado').textContent = btn.dataset.estado
                    document.querySelector('#option-default-area').value = btn.dataset.areaid
                    document.querySelector('#option-default-area').textContent = btn.dataset.areanom
                    const url = 'get_empleados-modi.php?idEmpleado=' + btn.dataset.responsableid + '&nomEmpleado=' + btn.dataset.responsablenom;
                    $(document).ready(function() {
                        var empleados = $('#select-agregar-empleado-modi');
                        var id_area = $('#select-agregar-area-modi').val();
                        $.ajax({
                            data: {
                                id_area: id_area
                            },
                            dataType: 'html',
                            type: 'POST',
                            url: url,
                        }).done(function(data) {
                            empleados.html(data);
                        });

                    });

                }
            })
        })



        $(document).ready(function() {

            var empleados = $('#select-agregar-empleado-modi');

            $('#select-agregar-area-modi').change(function() {
                var id_area = $(this).val();

                $.ajax({
                    data: {
                        id_area: id_area
                    },
                    dataType: 'html',
                    type: 'POST',
                    url: 'get_empleados-modi1.php',
                }).done(function(data) {
                    empleados.html(data);
                });

            });

        });
    </script>

</body>

</html>