<?php
///////////////// Logueo ////////////////////////

// verificar si el correo existe
function loginCorreo($correo, $conexion)
{
    $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
    return $consulta = mysqli_query($conexion, $query);
}

//Verifica que el usuario si este registrado en la base de datos
function login($correo, $clave, $conexion)
{
    $query = "SELECT * FROM usuarios WHERE correo = '$correo' AND clave = '$clave'";
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
// function datosPersonales($id,$conexion)
// {
//     $query = "SELECT `id`, usuarios.nombre, rol.nombre AS 'rol', area.nombre AS 'area',correo,telefono FROM `usuarios` INNER JOIN rol ON rol.codigo = usuarios.rol INNER JOIN area ON area.codigo = usuarios.area WHERE id = '$id';";
//     return $consulta = mysqli_query($conexion, $query);
// }

// Calificar///

//nos trae todas las areas registradas en la base de datos
function mostrarAreaAdmi($conexion)
{
    $query = "SELECT * FROM `area` WHERE codigo = '1' ";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarArea($conexion)
{
    $query = "SELECT * FROM `area` WHERE codigo != '1' ";
    return $consulta = mysqli_query($conexion, $query);
}

function mostrarAreaDirectorio($conexion)
{
    $query = "SELECT * FROM `area` ";
    return $consulta = mysqli_query($conexion, $query);
}

//Nos trae todos los usuarios registrados con su respectiva area
function mostrarUsuario($conexion, $id, $area)
{
    $query = "SELECT id,usuarios.nombre, area.nombre AS 'ambiente',usuarios.area,imagen,tipo_imagen FROM usuarios INNER JOIN area ON area.codigo = usuarios.area WHERE id != '$id' AND usuarios.area = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}

// nos trae los compañeros que ya el usuario a calificado para desactivarlos por ese mes
function empleadoCalificado($mes, $idCalificante, $idCalificador, $conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}

//nos trae la nota de la uatoevaluacion verivicando asi si el usuario ya se autocalifico ese mes 
function empleadoAutocalificado($mes, $idCalificante, $idCalificador, $conexion)
{
    $query = "SELECT * FROM `calificaciones` WHERE `idCalificante` = '$idCalificante' AND `idCalificador` = '$idCalificador' AND `mes` = '$mes'";
    return $consulta = mysqli_query($conexion, $query);
}

//nos trae los comentarios de cada persona 
function empleadoComentario($mes, $idCalificador, $conexion)
{
    $query = "SELECT `mensaje` FROM `calificaciones` WHERE `mes` = '$mes' AND `idCalificador` = '$idCalificador' AND `mensaje` != 'NULL';";
    return $consulta = mysqli_query($conexion, $query);
}

//trae las preguntas dependiendo de su tipo ya sea general o no
function mostrarPreguntas($tipo, $conexion)
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
function mostrarPreguntasid($tipo, $conexion)
{

    switch ($tipo) {
        case '0':
            $query = "SELECT id , general FROM preguntas ";
            $consulta = mysqli_query($conexion, $query);
            $retornoA = [];
            $i = 0;
            while ($pregunta = mysqli_fetch_array($consulta)) {
                $retornoA[$i] = [$pregunta['id'], $pregunta['general']];
                $i++;
            }
            return $retornoA;
            break;

        case '1':
            $query = "SELECT id,general FROM preguntas WHERE general = 1 ";
            $consulta = mysqli_query($conexion, $query);
            $retornoA = [];
            $i = 0;
            while ($pregunta = mysqli_fetch_array($consulta)) {
                $retornoA[$i] = [$pregunta['id'], $pregunta['general']];
                $i++;
            }
            return $retornoA;
            break;

        default:
            # code...
            break;
    }
}

// guarda la calificaciones y sus notas
function guardarCalificaciones($idP, $idCalificante, $idCalificador, $nota, $mes, $area, $tipo, $rol, $area_calificante,$anio, $conexion)
{
    $query = "INSERT INTO `calificaciones`(`idP`, `idCalificante`, `idCalificador`, `nota`, `mes`,area,general,rol,area_calificante,anio) VALUES ('$idP','$idCalificante','$idCalificador','$nota','$mes','$area','$tipo','$rol','$area_calificante','$anio')";
    $insertar = mysqli_query($conexion, $query);
}

function guardarComentario($mensaje, $idP, $idCalificante, $idCalificador, $mes, $conexion)
{
    $query = "UPDATE `calificaciones` SET mensaje='$mensaje' WHERE `idP`= '$idP' and `idCalificante`= '$idCalificante' and `idCalificador`='$idCalificador'  and `mes`= '$mes'";
    $insertar = mysqli_query($conexion, $query);
}

//Mi inventario

// muestra el inventario de cada persona 
function  mostrarInventario($id, $conexion)
{
    $query = "SELECT `cod`,`nombre`,`estado` FROM `inventariogeneral` WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

// Directorio

// muestra la informacion de contacto de todos los usuarios
function  mostrarDirectorio($idArea, $conexion)
{
    $query = "SELECT nombre, correo, telefono FROM `usuarios` WHERE `area` = '$idArea'";
    return $consulta = mysqli_query($conexion, $query);
}



////////////////// empleado Jefe//////////////////////////////////

// Inventario Area

// muestra al jefe de area todo el inventario de su area
function mostarInventarioAreaPersona($area, $conexion)
{
    $query = "SELECT * FROM `usuarios` WHERE usuarios.area = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}


// trae los usuarios de solo una area correspondiente
function mostarUsuarioArea($area, $conexion)
{
    $query = "SELECT `id` AS 'id_responsable' ,`nombre` AS 'nombre_responsable' FROM `usuarios`  WHERE `area` = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}


// elimina un articulo dependiendo de su id
function eliminarProducto($cod, $conexion)
{
    $query = " DELETE FROM `inventariogeneral` WHERE `cod` = '$cod'";
    return $consulta = mysqli_query($conexion, $query);
}

// nos trae solo un producto por su id
function consultarProducto($cod, $conexion)
{
    $query = "SELECT `cod` FROM `inventariogeneral` WHERE `cod` = '$cod'";
    return $consulta = mysqli_query($conexion, $query);
}

// inserta un producto en el inventario
function insertarInventarioAreaProducto($cod, $nombre, $estado, $id_responsable, $area, $conexion)
{
    $query = "INSERT INTO `inventariogeneral`(`cod`, `nombre`, `estado`, `id_responsable`, `area`) VALUES ('$cod','$nombre','$estado','$id_responsable','$area')";
    $consulta = mysqli_query($conexion, $query);
}

// modifica un producto del inventario general
function modificarInventarioAreaProducto($cod, $nombre, $estado, $id_responsable, $area, $conexion)
{
    $query = "UPDATE `inventariogeneral` SET `nombre`='$nombre',`estado`='$estado',`id_responsable`='$id_responsable',`area`='$area' WHERE `cod`='$cod'";
    $consulta = mysqli_query($conexion, $query);
}

// nos trae los articulos de cada persona a cargo
function mostarInventarioAreaProducto($id, $conexion)
{

    $query = "SELECT `cod`, inventariogeneral.nombre, `estado`, `id_responsable`, inventariogeneral.area, area.nombre AS 'nombre_area', usuarios.nombre AS 'nombre_responsable',usuarios.apellidos AS 'apellido_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable INNER JOIN area ON area.codigo = inventariogeneral.area WHERE `id_responsable` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}



// nos trae los articulos por area de la empresa
function mostrarInventarioPorArea($cod, $conexion)
{

    $query = "SELECT `cod`, inventariogeneral.nombre, `estado`,  area.nombre AS 'nombre_area', `id_responsable`, inventariogeneral.area, usuarios.nombre AS 'nombre_responsable', usuarios.apellidos AS 'apellido_responsable' FROM `inventariogeneral` INNER JOIN usuarios ON usuarios.id = inventariogeneral.id_responsable INNER JOIN area ON area.codigo = inventariogeneral.area WHERE inventariogeneral.area = '$cod' AND inventariogeneral.id_responsable != 'Noasignado' ";
    return $consulta = mysqli_query($conexion, $query);
}

// nos trae los articulos por area de la empresa que no estan asignados a una persona
function mostrarInventarioPorAreaGeneral($cod, $conexion)
{

    $query = "SELECT cod,inventariogeneral.nombre ,estado,id_responsable,area, area.nombre AS 'nombre_area' FROM inventariogeneral INNER JOIN area ON area.codigo = inventariogeneral.area WHERE area = '$cod' AND id_responsable = 'Noasignado'";
    return $consulta = mysqli_query($conexion, $query);
}

// nos trae los articulos por area de la empresa que no estan asignados a una area
function mostrarInventarioPorAreaGeneralNoA($conexion)
{

    $query = "SELECT * FROM `inventariogeneral` WHERE id_responsable = 'Noasignado' AND area = 'Noasignado'";
    return $consulta = mysqli_query($conexion, $query);
}


function mostrarAreaPorCodigo($cod, $conexion)
{
    $query = "SELECT * FROM `area` WHERE codigo = '$cod' ";
    return $consulta = mysqli_query($conexion, $query);
}




///////// Calificacion area//

// muestra todos lo usuarios que pertenecen a un area predeterminada
function mostarUsuarioCalificacionArea($area, $conexion)
{
    $query = "SELECT `id` ,`nombre`,apellidos,rol, area FROM `usuarios`  WHERE `area` = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}

function mostarUsuarioCalificacionAreaEmergente($id, $conexion)
{
    $query = "SELECT * FROM `usuarios`  WHERE `id` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

//mostrar el total de ususarios por area
function totalUsuariosArea($area, $conexion)
{
    $query = "SELECT COUNT(id) AS 'total' FROM `usuarios` WHERE `area` = '$area'";
    return $consulta = mysqli_query($conexion, $query);
}


// nos muestra el promedio por cada pregunta de un usuario determinado
function promedioPreguntaUsuario($idCalificador, $mes, $rol, $idP, $area, $conexion)
{
    $query = "SELECT round(AVG(`nota`),2) AS 'Promedio' FROM calificaciones WHERE `idCalificador` = '$idCalificador' AND `mes` = '$mes' AND `rol` = '$rol' AND `area` = '$area' AND `area_calificante` = `area` AND `idP` = '$idP' AND `idCalificante` != `idCalificador`";
    $consulta = mysqli_query($conexion, $query);
    $retornar = [];
    while ($pregunta = mysqli_fetch_array($consulta)) {
        array_push($retornar, $pregunta['Promedio']);
    }
    return $retornar;
}

// nos muestra el promedio por cada pregunta de un usuario determinado pero para el jefe
function promedioPreguntaUsuarioJefe($idCalificador, $mes, $idP, $area, $conexion)
{
    $query = "SELECT round(AVG(`nota`),2) AS 'Promedio' FROM calificaciones WHERE `idCalificador` = '$idCalificador' AND `mes` = '$mes' AND `idP` = '$idP' AND `area` = '$area' AND `area_calificante` = `area` AND `idCalificante` != `idCalificador`";
    $consulta = mysqli_query($conexion, $query);
    $retornar = [];
    while ($pregunta = mysqli_fetch_array($consulta)) {
        array_push($retornar, $pregunta['Promedio']);
    }
    return $retornar;
}


// nos muestra el promedio por cada pregunta de un usuario determinado pero de la autoevaluacion
function promedioPreguntaUsuarioAuto($idCalificador, $mes, $rol, $idP, $area, $conexion)
{
    $query = "SELECT round(AVG(`nota`),2) AS 'Promedio' FROM calificaciones WHERE `idCalificador` = '$idCalificador' AND `mes` = '$mes' AND `rol` = '$rol' AND `idP` = '$idP' AND `area` = '$area' AND `area_calificante` = `area` AND `idCalificante` = `idCalificador`";
    $consulta = mysqli_query($conexion, $query);
    $retornar = [];
    while ($pregunta = mysqli_fetch_array($consulta)) {
        array_push($retornar, $pregunta['Promedio']);
    }
    return $retornar;
}

// nos muestra el promedio por cada pregunta de un usuario determinado pero del area administrativa
function promedioPreguntaUsuarioAdministracion($idCalificador, $mes, $idP, $area, $conexion)
{
    $query = "SELECT round(AVG(`nota`),2) AS 'Promedio' FROM calificaciones WHERE `idCalificador` = '$idCalificador' AND `mes` = '$mes' AND `idP` = '$idP' AND `area` = '$area' AND `area_calificante` = '$area'";
    $consulta = mysqli_query($conexion, $query);
    $retornar = [];
    while ($pregunta = mysqli_fetch_array($consulta)) {
        array_push($retornar, $pregunta['Promedio']);
    }
    return $retornar;
}

// nos muestra cuantas calificaciones tiene la persona
function totalDeCalificaciones($idCalificador, $mes, $area, $conexion)
{
    $query = "SELECT COUNT(`nota`) AS 'total' FROM `calificaciones` WHERE `mes` = '$mes' AND `area` = '$area' AND `area` = `area_calificante` AND `idCalificador` = '$idCalificador'";
    $consulta = mysqli_query($conexion, $query);
    $retornar = [];
    while ($pregunta = mysqli_fetch_array($consulta)) {
        array_push($retornar, $pregunta['total']);
    }
    return $retornar;
}

function sacarMesMin($anio,$area,$conexion){
    $query = "SELECT  MIN(`mes`) AS 'mes' FROM `registrocalificacionarea` WHERE `anio` = '$anio' AND `area` = '$area'";
    return  $consulta = mysqli_query($conexion,$query);
}

function sacarPreguntasDiagrama($conexion)
{
    $query = "SELECT * FROM `preguntas`";
    return $consulta = mysqli_query($conexion,$query);
}

function sacarMesesDiagrama($anio,$area,$conexion){
    $query = "SELECT `mes` FROM `registrocalificacionarea` WHERE `anio` = '$anio' AND `area` = '$area'  GROUP BY `mes` ORDER BY `mes` ASC";
    return $consulta = mysqli_query($conexion,$query);
}

function NotaPorMesAnioArea($idP,$mes,$anio,$area,$conexion)
{
    $query = "SELECT `nota` FROM `registrocalificacionarea` WHERE `idPregunta` = '$idP' AND `mes` = '$mes' AND `anio` = '$anio' AND `area` = '$area';
    ";
    return $consulta = mysqli_query($conexion,$query);
}

function traerAniosQueTiene($conexion){
    $query = "SELECT  `anio` FROM `registrocalificacionarea` GROUP BY `anio` ASC";
    return $consulta = mysqli_query($conexion,$query);
}






































































//////////////// Administrador ////////////////////

//USUARIOS///

//consultar
function AdMostrarUsuarios($conexion)
{
    $query = "SELECT * FROM `usuarios`";
    return $consulta = mysqli_query($conexion, $query);
}

//eliminar
function AdEliminarUsuarios($id, $conexion)
{
    $query = "DELETE FROM `usuarios` WHERE `id` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}

//insertar
function AdInsertarUsuarios($id, $nombre, $clave, $rol, $area, $correo, $telefono, $imagen, $tipo_imagen, $conexion)
{
    $query = "INSERT INTO `usuarios`(`id`, `nombre`, `clave`, `rol`, `area`, `correo`, `telefono`, `imagen`, `tipo_imagen`) VALUES (`$id`, `$nombre`, `$clave`, `$rol`, `$area`, `$correo`, `$telefono`, `$imagen`, `$tipo_imagen`)";
    return $consulta = mysqli_query($conexion, $query);
}

//modificar
function AdModificarUsuarios($id, $nombre, $clave, $rol, $area, $correo, $telefono, $imagen, $tipo_imagen, $conexion)
{
    $query = "UPDATE `usuarios` SET `nombre`='$nombre',`clave`='$clave',`rol`='$rol',`area`='$area',`correo`='$correo',`telefono`='$telefono',`imagen`='$imagen',`tipo_imagen`='$tipo_imagen' WHERE `id`='$id'";
    return $consulta = mysqli_query($conexion, $query);
}


//PREGUNTAS//

//consultar
function AdConsultarPreguntas($conexion)
{
    $query = "SELECT * FROM `preguntas`";
    return $consulta = mysqli_query($conexion, $query);
}

//eliminar
function AdEliminarPreguntas($id, $conexion)
{
    $query = "DELETE FROM `preguntas` WHERE `id` = '$id'";
    return $consulta = mysqli_query($conexion, $query);
}
//insertar
function AdInsertarPreguntas($id, $pregunta, $general, $conexion)
{
    $query = "INSERT INTO `preguntas`(`id`, `pregunta`, `general`) VALUES ('$id','$pregunta','$general')";
    return $consulta = mysqli_query($conexion, $query);
}

//modificar
function AdModificarPregunta($id, $pregunta, $general, $conexion)
{
    $query = "UPDATE `preguntas` SET `pregunta`='$pregunta',`general`='$general' WHERE `id`='$id'";
    return $consulta = mysqli_query($conexion, $query);
}

































// funcion para actualizar la tabla registro anual por area
function registroCalificacionArea($area, $mes, $anio, $conexion)
{
    $usuarios = mostarUsuarioCalificacionArea($area, $conexion);
    $totalUsuario =  totalUsuariosArea($area, $conexion);
    $totalUsuario = mysqli_fetch_array($totalUsuario);
    $promfD = [];
    $inicializarArreglo =  mostrarPreguntas(0, $conexion);
    $iniciar = 0;
    while ($inicializar = mysqli_fetch_array($inicializarArreglo)) {
        $promfD[$iniciar] = 0;
        $iniciar++;
    }

    // mostramos los usuarios
    while ($usuario = mysqli_fetch_array($usuarios)) {
        $i = 0;
        $promT = 0;
        $contPromfD = 0;

        $preguntas =  mostrarPreguntas(0, $conexion);
        while ($pregunta = mysqli_fetch_array($preguntas)) {
            if ($usuario['area'] != 1) { // preguntamos que no sea del area administracion
                if ($usuario['rol'] == 1) { // preguntamos que si sean empleados
                    $sesenta = promedioPreguntaUsuario($usuario['id'], $mes, 1, $pregunta['id'], $usuario['area'], $conexion);
                    $sesenta = $sesenta[0]  === NULL  ? 0 : $sesenta[0];
                    $treinta = promedioPreguntaUsuario($usuario['id'], $mes, 2, $pregunta['id'], $usuario['area'], $conexion);
                    $treinta = $treinta[0]  === NULL  ? 0 : $treinta[0];
                    $auto = promedioPreguntaUsuarioAuto($usuario['id'], $mes, $usuario['rol'], $pregunta['id'], $usuario['area'], $conexion);
                    $auto = $auto[0]  === NULL  ? 0 : $auto[0];
                    $notaPregunta = (($sesenta * 0.6) + ($treinta * 0.3) + ($auto * 0.1));
                    $notaPregunta = number_format($notaPregunta, 2);
                    $promT = $promT + $notaPregunta;
                    $i++;
                    $promfD[$contPromfD] =  number_format($promfD[$contPromfD] + $notaPregunta,2);
                } else {  // para los jefes de cada area
                    $ochenta = promedioPreguntaUsuarioJefe($usuario['id'], $mes, $pregunta['id'], $usuario['area'],  $conexion);
                    $ochenta = $ochenta[0]  === NULL  ? 0 : $ochenta[0];
                    $auto = promedioPreguntaUsuarioAuto($usuario['id'], $mes, $usuario['rol'], $pregunta['id'], $usuario['area'],  $conexion);
                    $auto = $auto[0]  === NULL  ? 0 : $auto[0];
                    $notaPregunta = (($ochenta * 0.8) + ($auto * 0.2));
                    $notaPregunta = number_format($notaPregunta, 2);
                    $promT = $promT + $notaPregunta;
                    $i++;
                    $promfD[$contPromfD] =  number_format($promfD[$contPromfD] + $notaPregunta,2);
                }
            } else { // para el area administrativa
                $general = promedioPreguntaUsuarioAdministracion($usuario['id'], $mes, $pregunta['id'], $usuario['area'],  $conexion);
                $general = $general[0]  === NULL  ? 0 : $general[0];
                $general = number_format($general, 2);
                $promT = $promT + $general;
                $i++;
                $promfD[$contPromfD] =  number_format($promfD[$contPromfD] + $general,2);
            }
            $contPromfD++;
        }
    }

    $query = "SELECT * FROM `registrocalificacionarea` WHERE `mes` = '$mes' AND `anio` = '$anio' AND `area` = '$area' ";
    $consulta = mysqli_query($conexion, $query);
    $r = 0;


    if ($consulta->num_rows > 0){
       $preguntas =  mostrarPreguntas(0, $conexion);
        while ($pregunta = mysqli_fetch_array($preguntas)) {
            $nota =  $promfD[$r]/$totalUsuario[0];
            $nota = number_format($nota,2); 
            $idPregunta = $pregunta['id'];
            $query = "UPDATE `registrocalificacionarea` SET `nota`='$nota' WHERE `area` = '$area' AND  `idPregunta`='$idPregunta' AND `mes`='$mes' AND `anio`='$anio'";
            $consulta = mysqli_query($conexion, $query);
            $r++;
        }
    }else{
        $preguntas =  mostrarPreguntas(0, $conexion);
        while ($pregunta = mysqli_fetch_array($preguntas)) {
           $nota = $promfD[$r]/$totalUsuario[0];
            $nota = number_format($nota,2);  
            $idPregunta = $pregunta['id'];
            $nombreP = $pregunta['pregunta'];
            $query = "INSERT INTO `registrocalificacionarea`(`idPregunta`, `nombreP`, `mes`, `anio`, `nota`, `area`) VALUES ('$idPregunta','$nombreP','$mes','$anio','$nota','$area')";
            $consulta = mysqli_query($conexion, $query);
            $r++;
        }
    }
}


// Eliminar todas las calificaciones que sean diferentes al año actual.
function eliminarCalificacionAnual($anio, $conexion)
{
    $query = "DELETE FROM `calificaciones` WHERE `anio` != '$anio'";
    $consulta = mysqli_query($conexion, $query);
}