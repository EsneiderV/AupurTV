<?php
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
                     window.location.href="empleado.php";
                     </script>';
            break;
        case '2':
            echo '<script type="text/javascript">
                window.location.href="administrador.php";
                </script>';
            break;
        case '3':
             echo '<script type="text/javascript">
                 window.location.href="jefe.php";
                </script>';
            break;
        default:
            echo '<script type="text/javascript">
                window.location.href="usuario.php";
                </script>';
            break;
    }
}

function mostrarUsuario($conexion,$id,$area)
{
    $query = "SELECT id,usuarios.nombre, area.nombre AS 'ambiente',usuarios.area FROM usuarios INNER JOIN area ON area.codigo = usuarios.area WHERE id != '$id' AND usuarios.area = '$area'";
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

function mostrarPreguntasid($conexion)
{
    $query = "SELECT id FROM preguntas ";
    $consulta = mysqli_query($conexion, $query);
    $retornoA = [];
    $i = 0;
    while($pregunta = mysqli_fetch_array($consulta)){
        $retornoA[$i] = $pregunta['id'];
        $i ++;
    }
    return $retornoA;
}

function guardarCalificaciones($idP,$idCalificante,$idCalificador,$nota,$mes,$area,$conexion)
{
        $query = "INSERT INTO `calificaciones`(`idP`, `idCalificante`, `idCalificador`, `nota`, `mes`,area) VALUES ('$idP','$idCalificante','$idCalificador','$nota','$mes','$area')";
        $insertar = mysqli_query($conexion, $query);
}

function promedioPorPregunta($id,$mes,$conexion)
{
        $query = "SELECT `idP`, `pregunta`, AVG(`nota`) AS 'promedio',`idCalificador`,`mes` FROM `calificaciones` INNER JOIN preguntas ON preguntas.id = `idP` WHERE `idCalificador` = '$id' and `mes` = '$mes' GROUP BY idP;";
        return $mostrar = mysqli_query($conexion, $query);
}

function datosPersonales($id,$conexion)
{
    $query = "SELECT `id`, usuarios.nombre, rol.nombre AS 'rol', area.nombre AS 'area',correo,telefono FROM `usuarios` INNER JOIN rol ON rol.codigo = usuarios.rol INNER JOIN area ON area.codigo = usuarios.area WHERE id = '$id';";
    return $consulta = mysqli_query($conexion, $query);
}

function empeladoDelMes($mes,$conexion)
{
    $query = "SELECT `idCalificador`,usuarios.nombre, area.nombre AS 'area', AVG(`nota`)AS 'promedio',`mes` FROM `calificaciones` INNER JOIN usuarios ON usuarios.id = calificaciones.idCalificador INNER JOIN area ON area.codigo = usuarios.area WHERE `mes` = '$mes' GROUP BY `idCalificador` ORDER BY promedio DESC LIMIT 1;";
    return $consulta = mysqli_query($conexion, $query);
}

function empleadoCalificado($mes,$idCalificante,$idCalificador,$conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarUsuarioAdmin($conexion)
{
    $query = "SELECT id,usuarios.nombre, area.nombre AS 'ambiente' FROM usuarios INNER JOIN area ON area.codigo = usuarios.area ";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarCalificacionAdmin($idusuario,$idPregunta,$mes,$conexion)
{
    $query = "SELECT nota FROM `calificaciones` WHERE `idCalificador` = '$idusuario' AND `mes` = '$mes' AND `idP` = '$idPregunta';";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarArea($conexion)
{
    $query = "SELECT * FROM `area` ";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarNotaArea($mes,$area,$conexion)
{
    $query = "SELECT preguntas.pregunta, AVG(nota) AS 'promedio' FROM `calificaciones` INNER JOIN preguntas ON calificaciones.idP = preguntas.id WHERE `mes` = '$mes' AND `area` = '$area' GROUP BY `idP`";
    return $consulta = mysqli_query($conexion, $query);
}

function  mostrarDirectorio($idArea,$conexion)
{
    $query = "SELECT nombre, correo, telefono FROM `usuarios` WHERE `area` = '$idArea'";
    return $consulta = mysqli_query($conexion, $query);
}

function  mostrarInventario($id,$conexion)
{
    $query = "SELECT `cod`,`nombre`,`estado` FROM `inventariogeneral` WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

function mostarInventarioAreaPersona($area,$conexion)
{
        $query = "SELECT `id_responsable`, usuarios.nombre AS 'nombre_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable WHERE inventariogeneral.area = '$area' GROUP BY id_responsable";
        return $consulta = mysqli_query($conexion, $query);
}

function mostarInventarioAreaProducto($id,$conexion)
{

    $query = "SELECT `cod`, inventariogeneral.nombre, `estado`, `id_responsable`, inventariogeneral.area, usuarios.nombre AS 'nombre_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}


function eliminarProducto($cod,$conexion)
{
    $query = " DELETE FROM `inventariogeneral` WHERE `cod` = '$cod'";
    return $consulta = mysqli_query($conexion, $query);
}


function mostarUsuarioArea($area,$conexion)
{
        $query = "SELECT `id` AS 'id_responsable' ,`nombre` AS 'nombre_responsable' FROM `usuarios`  WHERE `area` = '$area'";
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
