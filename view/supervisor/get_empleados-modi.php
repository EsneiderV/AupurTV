<?php 
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';

$id_area = filter_input(INPUT_POST, 'id_area'); 
$consulta = mostarUsuarioCalificacionArea($id_area,$conexion);
echo "<option id='option-default-empleado'  value=" . $_GET['idEmpleado'] . ">" . $_GET['nomEmpleado'] . "</option>";
while ($item = mysqli_fetch_array($consulta)) {

    $nombre = explode(' ', $item['nombre']);
    $apellido = explode(' ', $item['apellidos']);

    echo "<option  value=" . $item['id'] . ">" . $nombre[0].' '.$apellido[0] . "</option>";
    
}
echo "<option  value=" .'Noasignado'. ">" .'No asignado'. "</option>";


?>