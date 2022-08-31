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

    <div class="inventarioArea-agregar-articulo">
        <h3 class="text-center titulo-agregar">AGREGAR</h3>
        <form action="" class="inventarioArea-formulario" method="POST" id="formulario">
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="codigo" placeholder="Código artículo" required>
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="nombre" placeholder="Nombre artículo" required>
            <input autocomplete="off" class="inventarioArea-item-formulario" type="text" name="estado" placeholder="Estado artículo" required>
            <select class="inventarioArea-item-formulario" id="select-agregar" name="responsable" required>
                <option class="option-select" value="0">Seleccione...</option>
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
        <a href="../supervisor/supervisor.php" class="inventarioArea-volver"> ᗕ Atrás</a>
        <h1 class="inventarioArea-titulo">Inventario área</h1>
    </div>

    <div class="inventarioArea-contenedor">
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
                    <button class="inventarioArea-abrirInventario" type="button" class="" data-bs-toggle="modal" data-bs-target="#abrirInventario">
                        Inventario
                    </button>
                </div>
            </div>
        <?php
        }
        ?>

    </div>

    <!-- Modal para abrir el inventario-->
    <div class="modal fade" id="abrirInventario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Launch demo modal
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Descargar Inventario</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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