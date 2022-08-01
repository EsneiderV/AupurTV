<!DOCTYPE html>
<html lang="es" class="index-html">

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
    <div class="index-login-container">
        <div class="index-login-info-container">
            <div class="index-logo-container">
               <img src="image/logo.png" alt="" class="index-logo">
            </div>
            <form class="index-inputs-container" action="" method="POST">
                <label  for="correo" class="index-label">Correo</label>
                <input required name="correo" class="index-input" type="email" id="correo" >

                <label  for="clave" class="index-label">Clave</label>
                <input required name="clave"  class="index-input" type="password" id="clave">

                <button class="index-btn" name="acceder">Ingresar</button>

            </form>

            <div class="index-footer-container">
                <img src="image/footerLogin.jpeg" alt="Imagen del footer" class="index-footer">
            </div>
        </div>

    </div>

</body>
</html>

<?php
include_once 'controllers/php/funciones.php';
include_once 'models/Conexion.php';
if (isset($_POST['acceder'])) {
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

   $consultaCorreo = loginCorreo($correo,$conexion);
    if($consultaCorreo->num_rows > 0){
        $consulta =  login($correo, $clave, $conexion);
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
            alert("Clave Incorrecta");
            window.location.href="index.php";
            </script>';
        }
    }else{
        echo '<script type="text/javascript">
        alert("El correo no se encuentra registrado");
        window.location.href="index.php";
        </script>';

    } 
}
?>