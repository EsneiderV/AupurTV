<?php
session_start();
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';
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
$idPersonas = mostarUsuarioArea($_SESSION['area'], $conexion);
$articulos = mostarUsuarioArea($_SESSION['area'], $conexion);

// eliminar articulo
if (isset($_GET['eliminar'])) {
    $cod = $_GET['eliminar'];
    eliminarProducto($cod, $conexion);

    echo '<script type="text/javascript">
        window.location.href="inventarioArea.php";
      </script>';
}

if (isset($_POST['agregar'])) {
    $cod = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $id_responsable = $_POST['responsable'];
    $area = $_SESSION['area'];

    $consultaProducto = consultarProducto($cod, $conexion);
    if ($consultaProducto->num_rows > 0) {
        echo '<script type="text/javascript">
    alert("El codigo del articulo ya esta registrado");
    window.location.href="inventarioArea.php";
    </script>';
    } else {
        insertarInventarioAreaProducto($cod, $nombre, $estado, $id_responsable, $area, $conexion);
        echo '<script type="text/javascript">
            window.location.href="inventarioArea.php";
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
    window.location.href="inventarioArea.php";
    </script>';
}

?>
<!DOCTYPE html>
<html lang="en">

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
    <div class="inventarioArea-agregar-articulo">
        <h3 class="text-center">AGREGAR</h3>
        <form action="" class="inventarioArea-formulario" method="POST" id="formulario">
            <input autocomplete="off" class="inventarioArea-item-formulario" type="number" name="codigo" placeholder="Código artículo" required>
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="nombre" placeholder="Nombre artículo" required>
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="estado" placeholder="Estado artículo" required>
            <select class="inventarioArea-item-formulario" id="select-agregar" name="responsable" required>
                <option value="0">Seleccione...</option>
                <?php
                while ($idPersona = mysqli_fetch_array($idPersonas)) {
                    echo "<option value=" . $idPersona['id_responsable'] . ">" . $idPersona['nombre_responsable'] . "</option>";
                }
                ?>

            </select>
            <button class="inventarioArea-item-formulario-agregar" type="submit" name="agregar">Agregar</button>
        </form>
    </div>

    <div class="inventarioArea-div-nav">
        <a href="../supervisor/supervisor.php" class="inventarioArea-volver"> ᗕ Volver atrás</a>
        <h1 class="inventarioArea-titulo">Inventario área</h1>
    </div>



    <div class="inventarioArea-contenedor">
        <?php
        while ($persona = mysqli_fetch_array($personas)) {
        ?>
            <div class="inventarioArea-contenedor-producto">
                <h4 class="text-center"> <?php echo $persona['nombre_responsable'] ?> </h4>
                <?php
                $productos = mostarInventarioAreaProducto($persona['id_responsable'], $conexion);
                while ($producto = mysqli_fetch_array($productos)) {
                ?>
                    <div class="inventarioArea-div-producto">
                        <p class="inventarioArea-p-producto">
                            <span>
                                <?php echo $producto['cod'] ?>
                            </span>
                            <span>
                                <?php echo $producto['nombre'] ?>
                            </span>
                            <span>
                                <?php echo $producto['estado'] ?>
                            </span>
                        </p>


                        <div id="contenedorBtn">
                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar" data-cod="<?php echo $producto['cod'] ?>" data-nombre="<?php echo $producto['nombre'] ?>" data-estado="<?php echo $producto['estado'] ?>" data-responsableid="<?php echo $producto['id_responsable'] ?>" data-responsablenom="<?php echo $producto['nombre_responsable'] ?>">
                                <i class="fa-solid fa-pen-to-square modificarL"></i>
                            </button>

                            <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="inventarioArea.php?eliminar=<?php echo $producto['cod'] ?>">
                            </a>

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

    <!-- Modal para el boton de modificar articulos -->
    <div class="modal fade" id="articulosModificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Modificar articulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        Código : <input autocomplete="off" readonly id="codigo" class="inventarioArea-item-formulario" type="number" name="codigo"> <br>
                        Nombre : <input autocomplete="off" id="nombre" class="inventarioArea-item-formulario" type="text" name="nombre"> <br>
                        Estado : <input autocomplete="off" id="estado" class="inventarioArea-item-formulario" type="text" name="estado"> <br>
                        Empleado : <select class="inventarioArea-item-formulario" name="responsable">
                            <option id="optionRes">Seleccione...</option>
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
        const select = document.querySelector('#select-agregar').value
        console.log(select)
        if (select == 0) {
            e.preventDefault();
            alert('Por favor seleccione un empleado')
        }

    })
</script>