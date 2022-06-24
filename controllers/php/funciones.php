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
        default:
            echo '<script type="text/javascript">
                window.location.href="usuario.php";
                </script>';
            break;
    }
}

function mostrarUsuario($conexion,$id)
{
    $query = "SELECT id,usuarios.nombre, area.nombre AS 'ambiente' FROM usuarios INNER JOIN area ON area.codigo = usuarios.area WHERE id != '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarPreguntas($conexion)
{
    $query = "SELECT * FROM preguntas ";
    return $consulta = mysqli_query($conexion, $query);
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

function guardarCalificaciones($idP,$idCalificante,$idCalificador,$nota,$mes,$conexion)
{
        $query = "INSERT INTO `calificaciones`(`idP`, `idCalificante`, `idCalificador`, `nota`, `mes`) VALUES ('$idP','$idCalificante','$idCalificador','$nota','$mes')";
        $insertar = mysqli_query($conexion, $query);
}

function promedioPorPregunta($id,$mes,$conexion)
{
        $query = "SELECT `idP`, `pregunta`, AVG(`nota`) AS 'promedio',`idCalificador`,`mes` FROM `calificaciones` INNER JOIN preguntas ON preguntas.id = `idP` WHERE `idCalificador` = '$id' and `mes` = '$mes' GROUP BY idP;";
        return $mostrar = mysqli_query($conexion, $query);
}

function datosPersonales($id,$conexion)
{
    $query = "SELECT `id`, usuarios.nombre, rol.nombre AS 'rol', area.nombre AS 'area' FROM `usuarios` INNER JOIN rol ON rol.codigo = usuarios.rol INNER JOIN area ON area.codigo = usuarios.area WHERE id = '$id';";
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

