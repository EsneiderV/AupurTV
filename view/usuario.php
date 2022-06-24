<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stilos.css">
    <title>Login</title>
</head>
<body>
    <div>
       
        <form action="" class="login" method="POST">
            <input  name="documento" type="text" placeholder="Documento" required> <br> <br>
            <input  name="clave" type="text" placeholder="Contraseña" required> <br> <br>
            <button name="acceder"  type="submit">Acceder</button>
        </form>
    </div>
</body>
</html>

<?php
include_once'../controllers/php/funciones.php';
include_once'../models/Conexion.php';
 if (isset($_POST['acceder'])) {
    $documento = $_POST['documento'];
    $clave = $_POST['clave'];
    $consulta =  login($documento,$clave,$conexion);
    if($consulta->num_rows > 0){
        session_start();
        $usuario = mysqli_fetch_array($consulta);
        $_SESSION['id']=$usuario['id'];
        $_SESSION['nombre']=$usuario['nombre'];
        $_SESSION['clave']=$usuario['clave'];
        $_SESSION['rol']=$usuario['rol'];
        $_SESSION['area']=$usuario['area'];

        redireccion($_SESSION['rol']);  
    }else {
        echo'<script type="text/javascript">
        alert("Usuario No Encontrado");
        window.location.href="usuario.php";
        </script>';
    }
 }
?>
