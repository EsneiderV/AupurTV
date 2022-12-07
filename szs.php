<?php 
include_once 'models/Conexion.php';
$query = "SELECT * FROM `usuarios`";
$consulta = mysqli_query($conexion, $query);

while ($consultar = mysqli_fetch_array($consulta)) {
    $clave = $consultar['clave'];
    $id = $consultar['id'];
    $nuevaClave = password_hash($clave, PASSWORD_DEFAULT);

    $query1 = "UPDATE `usuarios` SET `clave`= '$nuevaClave' WHERE `id` = '$id'";
    $consulta1 = mysqli_query($conexion, $query1);

    
}

?>