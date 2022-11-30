<?php 

// Variables de mensajes y JSON
$respuestaOK = false;
$mensajeError = "No se puede ejecutar";
$contenidoOK = "";

// Se incluye el archivo de funciones y conexion con la base de datos
include_once '../../controllers/php/funciones.php';
include_once '../../models/Conexion.php';


// Validar conexion con la base de datos
if ($errorDbConexxion == false) {
    // Validacion que existan las variables post
    if (isset($_POST) && !empty($_POST)) {
        // Se verifican las variables de accion
        switch ($_POST['accion']) {
            case 'addUser':
                // Se arma el query
                // $query = sprintf("INSERT INTO usuarios SET DNI, nombre, apellidos,
                //  clave, rol, area, correo, telefono, imagen
            
                //  VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]',
                //  '[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]')");
                break;
            
            default:
            $mensajeError = 'Esta acción no se encuentra disponible';
            break;
        }
    }else {
        $mensajeError = 'No se puede ejecutar';
    }
}else {
    $mensajeError = 'No se puede establecer conexión con la base de datos';
}

$salidaJson = array("respuesta" => $respuestaOK,
                    "mensaje" => $mensajeError,
                    "contenido" => $contenidoOK);

echo json_encode($salidaJson);
