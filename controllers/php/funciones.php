<?php
///////////////// Logueo ////////////////////////

//Verifica que el usuario si este registrado en la base de datos
function login($documento, $clave, $conexion)
{
    $query = "SELECT * FROM usuarios WHERE id = '$documento' AND clave = '$clave'";
    return $consulta = mysqli_query($conexion, $query);
}

// despues del logueo manda al usuario a la vista de pendiendo de su rol
function redireccion($rol)
{
    switch ($rol) {
        case '1':
            echo '<script type="text/javascript">
                     window.location.href="view/empleado/empleado.php";
                     </script>';
            break;
        case '2' || '3':
            echo '<script type="text/javascript">
                window.location.href="view/supervisor/supervisor.php";
                </script>';
            break;
        default:
            echo '<script type="text/javascript">
                window.location.href="index.php";
                </script>';
            break;
    }
}

////////////////// empleado//////////////////////////////////

//Datos personales/////

//muestra los datos personales de cada usuario
function datosPersonales($id,$conexion)
{
    $query = "SELECT `id`, usuarios.nombre, rol.nombre AS 'rol', area.nombre AS 'area',correo,telefono FROM `usuarios` INNER JOIN rol ON rol.codigo = usuarios.rol INNER JOIN area ON area.codigo = usuarios.area WHERE id = '$id';";
    return $consulta = mysqli_query($conexion, $query);
}

// Calificar///

//nos trae todas las areas registradas en la base de datos
function mostrarArea($conexion)
{
    $query = "SELECT * FROM `area` ";
    return $consulta = mysqli_query($conexion, $query);
}

//Nos trae todos los usuarios registrados con su respectiva area
function mostrarUsuario($conexion,$id,$area)
{
    $query = "SELECT id,usuarios.nombre, area.nombre AS 'ambiente',usuarios.area,imagen,tipo_imagen FROM usuarios INNER JOIN area ON area.codigo = usuarios.area WHERE id != '$id' AND usuarios.area = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}

// nos trae los compañeros que ya el usuario a calificado para desactivarlos por ese mes
function empleadoCalificado($mes,$idCalificante,$idCalificador,$conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}

//nos trae la nota de la uatoevaluacion verivicando asi si el usuario ya se autocalifico ese mes 
function empleadoAutocalificado($mes,$idCalificante,$idCalificador,$conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}

//trae las preguntas dependiendo de su tipo ya sea general o no
function mostrarPreguntas($tipo,$conexion)
{
    switch ($tipo) {
        case 1:
                $query = "SELECT * FROM preguntas WHERE general = 1 ";
                return $consulta = mysqli_query($conexion, $query);
            break;
        
        default:
                $query = "SELECT * FROM preguntas ";
                return $consulta = mysqli_query($conexion, $query);
            break;
    }

   
}

//trae las preguntas dependiendo de su tipo ya sea general o no y su identificador unico
function mostrarPreguntasid($tipo,$conexion)
{

    switch ($tipo) {
        case '0':
            $query = "SELECT id , general FROM preguntas ";
            $consulta = mysqli_query($conexion, $query);
            $retornoA = [];
            $i = 0;
            while($pregunta = mysqli_fetch_array($consulta)){
                $retornoA[$i] = [$pregunta['id'], $pregunta['general']];
                $i ++;
            }
            return $retornoA;
        break;

        case '1':
            $query = "SELECT id,general FROM preguntas WHERE general = 1 ";
            $consulta = mysqli_query($conexion, $query);
            $retornoA = [];
            $i = 0;
            while($pregunta = mysqli_fetch_array($consulta)){
                $retornoA[$i] = [$pregunta['id'], $pregunta['general']];
                $i ++;
            }
            return $retornoA;
        break;
        
        default:
            # code...
            break;
    }
   


}

// guarda la calificaciones y sus notas
function guardarCalificaciones($idP,$idCalificante,$idCalificador,$nota,$mes,$area,$tipo,$rol,$area_calificante,$conexion)
{
        $query = "INSERT INTO `calificaciones`(`idP`, `idCalificante`, `idCalificador`, `nota`, `mes`,area,general,rol,area_calificante) VALUES ('$idP','$idCalificante','$idCalificador','$nota','$mes','$area','$tipo','$rol','$area_calificante')";
        $insertar = mysqli_query($conexion, $query);
}


//Mi inventario

// muestra el inventario de cada persona 
function  mostrarInventario($id,$conexion)
{
    $query = "SELECT `cod`,`nombre`,`estado` FROM `inventariogeneral` WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

// Directorio

// muestra la informacion de contacto de todos los usuarios
function  mostrarDirectorio($idArea,$conexion)
{
    $query = "SELECT nombre, correo, telefono FROM `usuarios` WHERE `area` = '$idArea'";
    return $consulta = mysqli_query($conexion, $query);
}



////////////////// empleado Jefe//////////////////////////////////


// Inventario Area

// muestra al jefe de area todo el inventario de su area
function mostarInventarioAreaPersona($area,$conexion)
{
        $query = "SELECT `id_responsable`, usuarios.nombre AS 'nombre_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable WHERE inventariogeneral.area = '$area' GROUP BY id_responsable";
        return $consulta = mysqli_query($conexion, $query);
}


// trae los usuarios de solo una area correspondiente
function mostarUsuarioArea($area,$conexion)
{
        $query = "SELECT `id` AS 'id_responsable' ,`nombre` AS 'nombre_responsable' FROM `usuarios`  WHERE `area` = '$area'";
        return $consulta = mysqli_query($conexion, $query);
}


// elimina un articulo dependiendo de su id
function eliminarProducto($cod,$conexion)
{
    $query = " DELETE FROM `inventariogeneral` WHERE `cod` = '$cod'";
    return $consulta = mysqli_query($conexion, $query);
}

// nos trae solo un producto por su id
function consultarProducto($cod,$conexion)
{
        $query = "SELECT `cod` FROM `inventariogeneral` WHERE `cod` = '$cod'";
        return $consulta = mysqli_query($conexion, $query);
}

// inserta un producto en el inventario
function insertarInventarioAreaProducto($cod,$nombre,$estado,$id_responsable,$area,$conexion)
{
        $query = "INSERT INTO `inventariogeneral`(`cod`, `nombre`, `estado`, `id_responsable`, `area`) VALUES ('$cod','$nombre','$estado','$id_responsable','$area')";
        $consulta = mysqli_query($conexion, $query);
}

// modifica un producto del inventario general
function modificarInventarioAreaProducto($cod,$nombre,$estado,$id_responsable,$conexion)
{
        $query = "UPDATE `inventariogeneral` SET `nombre`='$nombre',`estado`='$estado',`id_responsable`='$id_responsable' WHERE `cod`='$cod'";
        $consulta = mysqli_query($conexion, $query);
}

// nos trae los articulos de cada persona a cargo
function mostarInventarioAreaProducto($id,$conexion)
{

    $query = "SELECT `cod`, inventariogeneral.nombre, `estado`, `id_responsable`, inventariogeneral.area, usuarios.nombre AS 'nombre_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}


///////// Calificacion area//

// muestra todos lo usuarios que pertenecen a un area predeterminada
function mostarUsuarioCalificacionArea($area,$conexion)
{
        $query = "SELECT `id` ,`nombre`,rol, area FROM `usuarios`  WHERE `area` = '$area'";
        return $consulta = mysqli_query($conexion, $query);
}

// nos muestra el promedio por cada pregunta de un usuario determinado
function calificacionPersonaPorcentage($mes,$rol,$id,$conexion)
{

    $query = "SELECT `idP`, `idCalificador`,AVG(nota) AS 'nota',preguntas.pregunta FROM calificaciones INNER JOIN preguntas ON preguntas.id = calificaciones.idP WHERE area_calificante = area AND `mes` = '$mes' AND `idCalificante` != `idCalificador` AND `rol` = '$rol' AND `idCalificador` = '$id' GROUP BY `idP`";
    $consulta = mysqli_query($conexion, $query);
    $retornoA = [];
    $i = 0;
    while($pregunta = mysqli_fetch_array($consulta)){
        $retornoA[$i] = [$pregunta['pregunta'], $pregunta['nota']];
        $i ++;
    }
    return $retornoA;
}

// nos muestra el promedio por cada pregunta de un usuario determinado en su autocalificacion
function autoCalificacionPersonaPorcentage($mes,$id,$conexion)
{

    $query = "SELECT `idP`, `idCalificador`,AVG(nota) AS 'nota',preguntas.pregunta FROM calificaciones INNER JOIN preguntas ON preguntas.id = calificaciones.idP WHERE area_calificante = area AND `mes` = '$mes' AND `idCalificante` = `idCalificador`  AND `idCalificador` = '$id' GROUP BY `idP`";
    $consulta = mysqli_query($conexion, $query);
    $retornoA = [];
    $i = 0;
    while($pregunta = mysqli_fetch_array($consulta)){
        $retornoA[$i] = [$pregunta['pregunta'], $pregunta['nota']];
        $i ++;
    }
    return $retornoA;
}
