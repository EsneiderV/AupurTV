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
$articulos = mostarInventarioAreaPersona($_SESSION['area'], $conexion);

// eliminar articulo
if(isset($_GET['eliminar'])){
     $cod = $_GET['eliminar'];
 eliminarProducto($cod,$conexion);

 echo'<script type="text/javascript">
        window.location.href="inventarioArea.php";
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
    <script src="https://kit.fontawesome.com/510f22fb48.js" crossorigin="anonymous"></script>
    <title>Inventario Area</title>
</head>

<body class="inventarioArea-body">
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
                    echo "<option value=" . $idPersona['id_responsable'] . ">" . $idPersona['nombre_responsable'] . "</option>";
                }
                ?>
                <option>Otro</option>
            </select>
            <button class="inventarioArea-item-formulario-agregar" type="submit">Agregar</button>
        </form>
    </div>

    <div class="inventarioArea-div-nav">
        <a href="jefe.php" class="inventarioArea-volver"> ᗕ Volver atrás</a>
        <h1 class="inventarioArea-titulo">Inventario Area</h1>
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
                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar"
                            data-cod = "<?php echo $producto['cod'] ?>"
                            data-nombre = "<?php echo $producto['nombre'] ?>"
                            data-estado = "<?php echo $producto['estado'] ?>"
                            data-responsableid = "<?php echo $producto['id_responsable'] ?>"
                            data-responsablenom = "<?php echo $producto['nombre_responsable'] ?>"
                            >
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
                    Codigo : <input id="codigo" class="inventarioArea-item-formulario" type="number" name="codigo"> <br>
                    Nombre : <input id="nombre" class="inventarioArea-item-formulario" type="text" name="nombre"> <br>
                    Estado : <input id="estado" class="inventarioArea-item-formulario" type="text" name="estado"> <br>
                    Empleado : <select class="inventarioArea-item-formulario" name="responsable">
                            <option id="optionRes">Seleccione...</option>
                            <?php
                            while ($articulo = mysqli_fetch_array($articulos)) {
                                echo "<option value=" . $articulo['id_responsable'] . ">" . $articulo['nombre_responsable'] . "</option>";
                            }
                            ?>
                            <option>Otro</option>
                        </select>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="calificar" class="btn btn-primary">Modificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>
</html>

<script>
    function eliminar(){
        let respuesta = confirm('¿Está seguro de que desea eliminar este articulo de forma permanente?');

        if(respuesta){
            return true
        }else{
            return false
        }
    }

    const div = document.querySelector('.inventarioArea-contenedor')
    div.addEventListener("click", e =>{
        if (e.target.classList.contains("modificar")) {
            const btn = e.target; 
            document.querySelector('#codigo').value=btn.dataset.cod
            document.querySelector('#nombre').value=btn.dataset.nombre
            document.querySelector('#estado').value=btn.dataset.estado
            document.querySelector('#optionRes').value=`${btn.dataset.responsableid}`;
            document.querySelector('#optionRes').textContent=`${btn.dataset.responsablenom}`;
            
        }

        if (e.target.classList.contains("modificarL")) {
            const btn = e.target.parentNode; 
            document.querySelector('#codigo').value=btn.dataset.cod
            document.querySelector('#nombre').value=btn.dataset.nombre
            document.querySelector('#estado').value=btn.dataset.estado
            document.querySelector('#optionRes').value=`${btn.dataset.responsableid}`
            document.querySelector('#optionRes').textContent=`${btn.dataset.responsablenom}`
        }

    })
</script>