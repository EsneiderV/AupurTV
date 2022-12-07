<?php 
// Se incluye el archivo de funciones y conexion con la base de datos
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';

    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];
    $area = $_POST['area'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $tipo = $_FILES['imagen']['type'];
    $nombrei = $_FILES['imagen']['name'];
    $tamano = $_FILES['imagen']['size'];
    $imagenSubida = fopen($_FILES['imagen']['tmp_name'],'r');
    $binariosImagen = fread($imagenSubida,$tamano);
    $binariosImagen =mysqli_escape_string($conexion,$binariosImagen);

    $query = "INSERT INTO usuarios (id, DNI, nombre, apellidos, clave, rol, area, correo, telefono, imagen, tipo_imagen) 
    VALUES ('23', '$dni', '$nombre', '$apellidos', '$clave', '$rol', '$area', '$correo', '$telefono','$binariosImagen','$tipo')";

    $consulta = mysqli_query($conexion, $query);
?>