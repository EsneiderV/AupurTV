<?php
///////////////// Logueo ////////////////////////

function login($documento, $clave, $conexion)
{
    $query = "SELECT * FROM usuarios WHERE id = '$documento' AND clave = '$clave'";
    return $consulta = mysqli_query($conexion, $query);
}

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

//Datos personales

function datosPersonales($id,$conexion)
{
    $query = "SELECT `id`, usuarios.nombre, rol.nombre AS 'rol', area.nombre AS 'area',correo,telefono FROM `usuarios` INNER JOIN rol ON rol.codigo = usuarios.rol INNER JOIN area ON area.codigo = usuarios.area WHERE id = '$id';";
    return $consulta = mysqli_query($conexion, $query);
}

// Calificar

function mostrarArea($conexion)
{
    $query = "SELECT * FROM `area` ";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarUsuario($conexion,$id,$area)
{
    $query = "SELECT id,usuarios.nombre, area.nombre AS 'ambiente',usuarios.area,imagen,tipo_imagen FROM usuarios INNER JOIN area ON area.codigo = usuarios.area WHERE id != '$id' AND usuarios.area = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}

function empleadoCalificado($mes,$idCalificante,$idCalificador,$conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}

function empleadoAutocalificado($mes,$idCalificante,$idCalificador,$conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}


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

function guardarCalificaciones($idP,$idCalificante,$idCalificador,$nota,$mes,$area,$tipo,$rol,$area_calificante,$conexion)
{
        $query = "INSERT INTO `calificaciones`(`idP`, `idCalificante`, `idCalificador`, `nota`, `mes`,area,general,rol,area_calificante) VALUES ('$idP','$idCalificante','$idCalificador','$nota','$mes','$area','$tipo','$rol','$area_calificante')";
        $insertar = mysqli_query($conexion, $query);
}


//Mi inventario


function  mostrarInventario($id,$conexion)
{
    $query = "SELECT `cod`,`nombre`,`estado` FROM `inventariogeneral` WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

// Directorio

function  mostrarDirectorio($idArea,$conexion)
{
    $query = "SELECT nombre, correo, telefono FROM `usuarios` WHERE `area` = '$idArea'";
    return $consulta = mysqli_query($conexion, $query);
}



////////////////// empleado Jefe//////////////////////////////////


// Inventario Area

function mostarInventarioAreaPersona($area,$conexion)
{
        $query = "SELECT `id_responsable`, usuarios.nombre AS 'nombre_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable WHERE inventariogeneral.area = '$area' GROUP BY id_responsable";
        return $consulta = mysqli_query($conexion, $query);
}

function mostarUsuarioArea($area,$conexion)
{
        $query = "SELECT `id` AS 'id_responsable' ,`nombre` AS 'nombre_responsable' FROM `usuarios`  WHERE `area` = '$area'";
        return $consulta = mysqli_query($conexion, $query);
}

function eliminarProducto($cod,$conexion)
{
    $query = " DELETE FROM `inventariogeneral` WHERE `cod` = '$cod'";
    return $consulta = mysqli_query($conexion, $query);
}

function consultarProducto($cod,$conexion)
{
        $query = "SELECT `cod` FROM `inventariogeneral` WHERE `cod` = '$cod'";
        return $consulta = mysqli_query($conexion, $query);
}

function insertarInventarioAreaProducto($cod,$nombre,$estado,$id_responsable,$area,$conexion)
{
        $query = "INSERT INTO `inventariogeneral`(`cod`, `nombre`, `estado`, `id_responsable`, `area`) VALUES ('$cod','$nombre','$estado','$id_responsable','$area')";
        $consulta = mysqli_query($conexion, $query);
}

function modificarInventarioAreaProducto($cod,$nombre,$estado,$id_responsable,$conexion)
{
        $query = "UPDATE `inventariogeneral` SET `nombre`='$nombre',`estado`='$estado',`id_responsable`='$id_responsable' WHERE `cod`='$cod'";
        $consulta = mysqli_query($conexion, $query);
}

function mostarInventarioAreaProducto($id,$conexion)
{

    $query = "SELECT `cod`, inventariogeneral.nombre, `estado`, `id_responsable`, inventariogeneral.area, usuarios.nombre AS 'nombre_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

///////// Calificacion area


function mostarUsuarioCalificacionArea($area,$conexion)
{
        $query = "SELECT `id` ,`nombre`,rol, area FROM `usuarios`  WHERE `area` = '$area'";
        return $consulta = mysqli_query($conexion, $query);
}

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
