<!DOCTYPE html>
<html lang="es" class="usuario-html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="controllers/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <title>Login - Aupur Televisión</title>
</head>

<body class="index-body">

        <div class="index-contenedor-formulario">
            <div class="index-contenedor-logo">
                <img src="image/logo.png" alt="logo" class="index-logo">
            </div>
            <form action="" class="index-login" method="POST">
                <div class="index-contenedor-inputs">
                <label for="correo" class="index-label">Correo</label>
                    <input type="text" name="correo" id="correo"  autocomplete="off" class="index-inputs">
                </div>
                <div class="index-contenedor-inputs">
                <label for="clave" class="index-label">Clave</label>
                    <input type="password" name="clave" id="clave" class="index-inputs">
                </div>
                   
                       <button type="submit" name="acceder" class="index-submit">Ingresar</button>
                   
            </form>
<div class="index-foto-contenedor-footer">

    <img src="image/footerLogin.jpeg" alt="foto de pie de pagina"  class="index-foto-footer">
</div>

            
        </div>
</body>
</html>

<?php
include_once 'controllers/php/funciones.php';
include_once 'models/Conexion.php';
if (isset($_POST['acceder'])) {
    $documento = $_POST['correo'];
    $clave = $_POST['clave'];
    $consulta =  login($documento, $clave, $conexion);
    if ($consulta->num_rows > 0) {
        session_start();
        $usuario = mysqli_fetch_array($consulta);
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['clave'] = $usuario['clave'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['area'] = $usuario['area'];
        $_SESSION['imagen'] = $usuario['imagen'];
        $_SESSION['tipo_imagen'] = $usuario['tipo_imagen'];

        redireccion($_SESSION['rol']);
    } else {
        echo '<script type="text/javascript">
        alert("Usuario No Encontrado");
        </script>';
    }
}
?>