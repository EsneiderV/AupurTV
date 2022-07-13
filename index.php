<!DOCTYPE html>
<html lang="en" class="usuario-html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="controllers/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <title>Login - Aupur Televisión</title>
</head>

<body class="usuario-body">


    <div class="usuario-contenedor-video">
        <video class="video1" autoplay="" muted="" playsinline="" loop="" src="https://aupur.co/wp-content/uploads/2021/09/camara.mp4"></video>
    </div>

    <div class="usuario-login">

        <div class="usuario-item-login titulo">
            <h1>BIENVENIDO</h1>
        </div>
        <div class="usuario-item-login">
            <form action="" method="POST">
                <input name="documento" type="text" placeholder="Documento" required> <br> <br>
                <input name="clave" type="password" placeholder="Contraseña" required> <br> <br>
                <button name="acceder" type="submit">Acceder</button>
            </form>
        </div>


        <div class="usuario-item-login">
            <a href="" class="usuario-enlaces">Recuperar contraseña</a>
            <a href="../index.php" class="usuario-enlaces">Volver</a>
        </div>
    </div>
</body>

</html>

<?php
include_once 'controllers/php/funciones.php';
include_once 'models/Conexion.php';
if (isset($_POST['acceder'])) {
    $documento = $_POST['documento'];
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