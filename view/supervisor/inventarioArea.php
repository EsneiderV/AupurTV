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
    <div class="calificar-nav">
        <a href="../supervisor/supervisor.php" class="calificar-volver-atras"> ᗕ ATRÁS</a>
        <h1 class="calificar-titulo">INVENTARIO ÁREA</h1>
    </div>

    <!-- Contenedor de contenedorcitos de cada persona y area -->
    <div id="inventarioAreaContenedorPrincipal" class="inventarioAreaDirector-contenedor">

        <div class="inventarioAreaDirector-contenedor-empleados">
            <div class="inventarioAreaDirector-contenedor-img">
                <img class="inventarioArea-img  rounded-circle " src="../../image/logoinventario.png" alt="foto de perfil">
            </div>

            <div class="inventarioArea-contenedor-nombre">
                <h4 class="inventarioArea-nombre">
                    <?php
                    if ($_SESSION['area'] == '2') {
                        echo 'TECNICOS';
                    } elseif ($_SESSION['area'] == '3') {
                        echo 'ÁREA CANAL';
                    }
                    ?>
                </h4>
            </div>

            <div class="inventarioAreaDirector-contenedor-abrirInventario">
                <span id="inventarioArea-abrirInventario-area" class="inventarioArea-abrirInventario">INVENTARIO</span>
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
                    <h4 class="inventarioArea-nombre">
                        <?php
                         $apellido = explode(' ', $persona['apellidos']);
                         $letraApellido = substr($persona['apellidos'], 0, 1);
                         $letraApellido = strtoupper($letraApellido);
                         $nombreCompleto = strtoupper($persona['nombre'] . ' ' . $apellido[0] . ' ' . $letraApellido . '.');

                        echo $nombreCompleto;
                        ?>
                    </h4>
                </div>

                <div class="inventarioAreaDirector-contenedor-abrirInventario">
                    <span id="<?php echo $persona['id'] ?>" class="inventarioArea-abrirInventario inventarioArea-abrirInventario-persona">INVENTARIO</span>
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
                                    <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                                </button>
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
    <!-- Inventario por cada area -->
    <?php
    $area = mostrarInventarioPorArea($_SESSION['area'], $conexion);
    $areaNombre = mostrarAreaPorCodigo($_SESSION['area'], $conexion);
    $areaTitulo = mysqli_fetch_array($areaNombre);
    ?>
    <div id="inventarioGerenteContenedorarea" class="inventarioAreaGerente-emergente-contenedor inventarioAreaDirector-desactivarContenedor">

        <div class="inventarioAreaGerente-emergente">
            <div class="invetarioArea-emergente-navar">
                <?php
                echo "<h2 class='invetarioArea-emergente-nombre'>" . $areaTitulo['nombre'] . "</h2>"
                ?>
                <span id="cerrarAreax" class="inventarioAreaGerente-emergente-x">X</span>
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
                            $nombreC = $nombre1[0] . ' ' . $apellido1[0];

                            ?>
                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-areanom="<?php echo $item['nombre_area'] ?>" data-areaid="<?php echo $item['area'] ?>" data-cod="<?php echo $item['cod'] ?>" data-nombre="<?php echo $item['nombre'] ?>" data-estado="<?php echo $item['estado'] ?>" data-responsableid="<?php echo $item['id_responsable'] ?>" data-responsablenom="<?php echo $item['nombre_responsable'] ?>">
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>
                        </div>


                    </div>
                    <hr>
                <?php
                }
                $itemsG = mostrarInventarioPorAreaGeneral($_SESSION['area'], $conexion);
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

                        </div>


                    </div>
                    <hr>
                <?php
                }
                ?>
            </div>

            <div class="invetarioArea-emergente-footer">
                <a href="../../pdf/pdf-inventario.php?area=<?php echo $_SESSION['area'] ?>" class="btn btn-secondary invetarioArea-emergente-footer-btn">Descargar</a>
                <button id="cerrarareaBtn" class="btn btn-secondary invetarioArea-emergente-footer-btn"> Cerrar</button>
            </div>
        </div>

    </div>





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
                                $apellido = explode(' ', $personaM['apellidos']);
                                $letraApellido = substr($personaM['apellidos'], 0, 1);
                                $letraApellido = strtoupper($letraApellido);
                                $nombreCompleto = strtolower($personaM['nombre'] . ' ' . $apellido[0] . ' ' . $letraApellido . '.');

                                echo "<option  value=" . $personaM['id'] . ">" .$nombreCompleto. "</option>";
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

    <script src="../../controllers/js/jquery-1.10.2.min.js"></script>


    <script>
        //abrir inventario area
        const AbrirArea = document.querySelector('#inventarioArea-abrirInventario-area');
        const contenedorarea = document.querySelector('#inventarioGerenteContenedorarea');
        const cerrarAreax = document.querySelector('#cerrarAreax');
        const cerrarareaBtn = document.querySelector('#cerrarareaBtn');
        AbrirArea.addEventListener('click', e => {
            contenedorarea.classList.remove('inventarioAreaDirector-desactivarContenedor')
        })
        cerrarAreax.addEventListener('click', e => {
            contenedorarea.classList.add('inventarioAreaDirector-desactivarContenedor')

        })
        cerrarareaBtn.addEventListener('click', e => {
            contenedorarea.classList.add('inventarioAreaDirector-desactivarContenedor')
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


        const divC = document.querySelectorAll('.inventarioAreaGerente-emergente-contenedor')

        divC.forEach(cont => {
            cont.addEventListener('click', e => {
                if (e.target.classList.contains("modificar") || e.target.classList.contains("modificarL")) {
                    buttonComponents = e.target.classList.contains('etiqueta-i') ? e.target.parentNode : e.target;
                    const btn = (e.target.classList.contains("modificar")) ? e.target : e.target.parentNode;
                    document.querySelector('#codigoM').value = btn.dataset.cod
                    document.querySelector('#nombreM').value = btn.dataset.nombre
                    document.querySelector('#option-default-estado').value = btn.dataset.estado
                    document.querySelector('#option-default-estado').textContent = btn.dataset.estado
                    document.querySelector('#option-default-encargado').value = btn.dataset.responsableid
                    document.querySelector('#option-default-encargado').textContent = btn.dataset.responsablenom
                }
            })
        })
    </script>

</body>

</html>