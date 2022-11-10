<?php 
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';

$id_area = filter_input(INPUT_POST, 'id_area'); 
$consulta = mostarUsuarioCalificacionArea($id_area,$conexion);
echo "<option class='option-select' value=" .'Noasignado'. ">" .'No asignado'. "</option>";

while ($item = mysqli_fetch_array($consulta)) {

    $apellido = explode(' ',$item['apellidos']) ;
    $nombreCompleto = $item['nombre']. ' ' .$apellido[0];
    $nombreCompleto = ucwords($nombreCompleto);

    echo "<option class='option-select' value=" . $item['id'] . ">" . $nombreCompleto. "</option>";
    

}

?>