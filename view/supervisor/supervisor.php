<?php
session_start();
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';

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

if (isset($_POST['cambiarclave'])) {
    $claveActual  = $_POST["cambioclaveactual"];

    $claveNueva = $_POST["cambioclavenueva"];
    $claveNuevaH = password_hash($claveNueva, PASSWORD_DEFAULT);

    // que la clace actual si sea la correcta
    $claveCorrecta = ClaveActual($conexion, $_SESSION['id']);

    $datos =  mysqli_fetch_row($claveCorrecta);
    $claveActualdb = $datos[0];

    if(password_verify($claveActual,$claveActualdb)){


        CambiarClave($conexion,$claveNuevaH,$_SESSION['id']);

        echo '<script type="text/javascript">
        alert("clave actualizada correctamente");
        window.location.href="supervisor.php";
        </script>';
            

    }else{
        echo '<script type="text/javascript">
        alert("Clave actual incorrecta");
        window.location.href="supervisor.php";
        </script>';
    }

}



date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
registroCalificacionArea($_SESSION['area'], $mes, $anio, $conexion);
registroCalificacionpersonaGeneral($mes, $anio, $area, $conexion)
?>

<!DOCTYPE html>
<html lang="es" class="empleado-html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="../../controllers/bootstrap/bootstrap.min.css">
    <script src="../../controllers/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../controllers/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <title> <?php echo $_SESSION['nombre'] ?> - Aupur Televisión </title>
</head>

<body class="empleado-body">

    <div class="empleado-contenedor-principal">

        <div class="empleado-contenedor-izquierdo">
            <div class="empleado-contenedor-logo">
                <img class="empleado-logo" src="../../image/logoNaranja.png" alt="logo de la empresa">
            </div>

            <!-- Mostramos la imagen de secion del usuario -->
            <div class="empleado-contenedor-imagen">
                <img class="empleado-imagen" src="data:<?php echo $_SESSION['tipo_imagen'] ?>;base64,<?php echo base64_encode($_SESSION['imagen']) ?>" alt="foto de perfil">
            </div>
        </div>

        <div class="empleado-contenedor-derecho">
            <div class="empleado-opciones">
                <h1 class="empleado-nombre"> <?php
                                                $apellido = explode(' ', $_SESSION['apellidos']);
                                                $nombrecompleto = $_SESSION['nombre'] . ' ' . $apellido[0];
                                                $nombrecompleto = ucwords($nombrecompleto);

                                                echo $nombrecompleto;
                                                ?></h1>
                <div class="empleado-items">

                    <a href="../general/calificar.php" class="empleado-enlace-jefe empleado-enlace-calificar">Calificar</a>
                    <button class="empleado-item-jefe" data-bs-toggle="modal" data-bs-target="#inventario">Inventario</button>
                    <button class="empleado-item-jefe" data-bs-toggle="modal" data-bs-target="#directorio">Directorio</button>
                    <?php
                    if ($_SESSION['area'] == 1 && $_SESSION['rol'] == 3) {
                    ?>
                        <a class="empleado-enlace-jefe" href="inventarioAreaGerente.php">Inventario área</a>
                    <?php
                    } else {
                    ?>
                        <a class="empleado-enlace-jefe" href="inventarioArea.php">Inventario área</a>
                    <?php
                    }
                    ?>
                    <a class="empleado-enlace-jefe" href="calificacionArea.php">Calificaciones área</a>
                    <a class="empleado-enlace-jefe" target="_blank" href="https://mail.google.com/mail/u/0/">Correo</a>
                    <button class="empleado-item-jefe" data-bs-toggle="modal" data-bs-target="#contraseña">Contraseña</button>
                    <?php
                    if ($_SESSION['admi'] == 1) {
                    ?>
                        <a class="empleado-enlace-jefe" href="../administrador/administrador.php">Administración</a>
                    <?php
                    }
                    ?>
                    <a href="../../models/Cerrar.php" class="empleado-enlace-jefe-salir">Salir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventario -->
    <div class="modal fade" id="inventario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Inventario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $inventarios = mostrarInventario($_SESSION['id'], $conexion);
                    while ($inventario = mysqli_fetch_array($inventarios)) {
                    ?>
                        <hr>
                        <p class="empleados-p-directorio">
                            <span> <b>COD :</b> <?php echo $inventario['cod'] ?> </span>
                            <span> <b>NOM :</b> <?php echo $inventario['nombre'] ?> </span>
                            <span> <b>EST :</b> <?php echo $inventario['estado'] ?></span>
                        </p>

                    <?php
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Directorio-->
    <div class="modal fade" id="directorio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Directorio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $areas = mostrarAreaDirectorio($conexion);
                    while ($area = mysqli_fetch_array($areas)) {
                        echo "<details>";
                        echo "<summary class='pt-4 h5'>" . $area['nombre'] . "</summary>";
                        $directorios = mostrarDirectorio($area['codigo'], $conexion);
                        while ($directorio = mysqli_fetch_array($directorios)) {
                            $apellido = explode(' ', $directorio['apellidos']);
                            $nombreCompleto = $directorio['nombre'] . ' ' . $apellido[0];
                            $nombreCompleto = ucwords($nombreCompleto);

                    ?>
                            <hr>
                            <p class="empleados-p-directorio">
                                <span> <b> <?php echo $nombreCompleto ?> </b> </span>
                                <span> <b>EM :</b> <?php echo $directorio['correo'] ?> </span>
                                <span> <b>CEL :</b> <?php echo $directorio['telefono'] ?></span>
                            </p>

                    <?php
                        }
                        echo "<hr/>";
                        echo "</details>";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Cambiar contraseña -->
    <div class="modal fade" id="contraseña" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="div-cambiar-contraseñas">
                            <label for="">Contraseña Actual :</label>
                            <input required name="cambioclaveactual" type="password">
                        </div>
                        <div class="div-cambiar-contraseñas">
                            <label for="">Nueva Contraseña :</label>
                            <input required name="cambioclavenueva" type="password">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="cambiarclave" class="btn btn-primary">Cambiar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>