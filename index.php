<?php
if (empty(session_id()) && !headers_sent()) {
    session_start();
}
include_once 'controllers/php/funciones.php';
include_once 'models/Conexion.php';
include_once 'email/index.php';
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
eliminarCalificacionAnual($anio, $conexion);

$claseDesactivarSpan = 'desactivar-span-recuperar-clave';
if (isset($_SESSION['clave']) && $_SESSION['clave'] == true) {
    $claseDesactivarSpan = '';
    $_SESSION['clave'] = false;
}

if (isset($_POST['acceder'])) {
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    $consultaCorreo = loginCorreo($correo, $conexion);
    if ($consultaCorreo->num_rows > 0) {
        $consulta =  login($correo, $clave, $conexion);
        if ($consulta->num_rows > 0) {
            $usuario = mysqli_fetch_array($consulta);
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellidos'] = $usuario['apellidos'];
            $_SESSION['clave'] = $usuario['clave'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['area'] = $usuario['area'];
            $_SESSION['imagen'] = $usuario['imagen'];
            $_SESSION['tipo_imagen'] = $usuario['tipo_imagen'];

            redireccion($_SESSION['rol']);
        } else {
            $_SESSION['clave'] = true;
            echo '<script type="text/javascript">
            alert("Clave Incorrecta");
            window.location.href="index.php";
            </script>';
        }
    } else {
        echo '<script type="text/javascript">
        alert("El correo no se encuentra registrado");
        window.location.href="index.php";
        </script>';
    }
}

if (isset($_POST['correoRecuperar'])) {
    $correoRecuperar = $_POST['correoRecuperar'];
    $existe = buscarCorreoRecuperarClave($correoRecuperar, $conexion);

    if ($existe->num_rows <= 0) {
        echo '<script type="text/javascript">
        alert("El correo no se encuentra registrado");
        window.location.href="index.php";
        </script>';
    } else {

        $datos =  mysqli_fetch_row($existe);
        $nombre = $datos[1];
        $correo = $datos[0];
        $nuevaClave = random_password();

        try {
            restablecerClave($nuevaClave, $correo, $conexion);
            recuperarClave($nombre, $nuevaClave);
        } catch (\Throwable $th) {
            echo $th;
        }

        echo '<script type="text/javascript">
        alert("Revisa tu correo eletronico");
        window.location.href="index.php";
        </script>';
    }
}

?>


<!DOCTYPE html>
<html lang="es" class="index-html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="controllers/css/style.css">
    <link rel="stylesheet" href="controllers/bootstrap/bootstrap.min.css">
    <script src="controllers/bootstrap/bootstrap.min.js"></script>
    <title>Login - Aupur Televisión</title>
</head>

<body class="index-body">
    <div class="index-login-container">
        <div class="index-login-info-container">
            <div class="index-logo-container">
                <img src="image/logo.png" alt="" class="index-logo">
            </div>

            <form class="index-inputs-container" action="" method="POST">
                <label for="correo" class="index-label">Correo</label>
                <input required name="correo" class="index-input" type="email" id="correo">
                <label for="clave" class="index-label">Clave</label>
                <input required name="clave" class="index-input" type="password" id="clave">
                <span data-bs-toggle="modal" data-bs-target="#exampleModal" class="index-span-recuper-clave <?php echo $claseDesactivarSpan ?>">Recuperar contraseña.</span>
                <button class="index-btn" name="acceder">Ingresar</button>
            </form>
            <div class="index-footer-container">
                <img src="image/footerLogin.jpeg" alt="Imagen del footer" class="index-footer">
            </div>

        </div>

    </div>

    <!-- Modal para recuperar contraseña-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 ms-auto" id="exampleModalLabel">Recuperar Contraseña</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <p>Introduce tu dirección de email. Te enviaremos un correo donde se adjunta tu contraseña.</p>
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="correoRecuperar">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>