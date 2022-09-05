<?php 
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';

$id_area = filter_input(INPUT_POST, 'id_area'); 
$consulta = mostarUsuarioCalificacionArea($id_area,$conexion);
echo "<option value=" .'0'. ">" .'Empleados'. "</option>";
echo "<option value=" .'Noasignado'. ">" .'No asignado'. "</option>";

while ($item = mysqli_fetch_array($consulta)) {

    echo "<option value=" . $item['id'] . ">" . $item['nombre'] . "</option>";
    

}

?>