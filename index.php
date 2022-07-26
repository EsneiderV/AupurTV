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

<body class="index-body">



    <div class="index-contenedor-form">
        <h1>BIENVENIDO</h1>
        <img class="index-form-imagen" src="mononosetapa.png" alt="">

        <form action="#" method="post" class="index-formulario">

            <div class="index-div-inputs">
                <label class="index-label">
                    <span class="index-span-input">Correo</span>
                    <input type="text" autocomplete="off" name="usuario"  class="index-span-input"/>
                </label>
            </div>

            <div class="index-div-inputs">
                <label class="index-label">
                    <span class="index-span-input">Clave</span>
                    <input type="password" autocomplete="off" name="clave"  class="index-span-input"/>
                </label>
            </div>

            <button type="submit">Acceder</button>
        </form>
        <a href="#">Recuperar contraseña</a>

    </div>

    <script>
        const inputs = document.querySelectorAll('input');
        inputs.forEach( input => {
            input.onfocus = ()=>{
                input.value = input.value.trim();
                if(input.value.trim().length == 0){
                    input.previousElementSibling.classList.add('top');
                }
                input.previousElementSibling.classList.add('focus');
                input.parentNode.classList.add('focus');
            }
            input.onblur = ()=>{
                input.previousElementSibling.classList.remove('top');
                input.previousElementSibling.classList.remove('focus');
                input.parentNode.classList.remove('focus');
            }
        });
    </script>
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