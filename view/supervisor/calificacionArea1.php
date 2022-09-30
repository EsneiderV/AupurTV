<?php
session_start();
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos las funciones que contiene las consultas sql

// verificamos que el entre sea del rol cordinaccion y direccion
if (isset($_SESSION['rol'])) {
  if ($_SESSION['rol'] != 2 && $_SESSION['rol'] != 3) {
    echo '<script type="text/javascript">
                  window.location.href="../../index.php";
                </script>';
  }
} else {
  echo '<script type="text/javascript">
                  window.location.href="../../index.php";
                </script>';
}

$mes = date('m');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../controllers/bootstrap/bootstrap.min.css">
  <script src="../../controllers/bootstrap/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../../controllers/css/style.css">

  <title>Calificación área - Aupur Televisión</title>
</head>

<body class="calificacionArea-body">
  <!-- Barra de navegacion -->

  <div class="inventarioArea-div-nav">
    <a href="supervisor.php" class="inventarioArea-volver"> ᗕ Atrás</a>
    <h1 class="inventarioArea-titulo">Calificación área</h1>
  </div>


  <div class="calificacionesArea-contenedor-principal">

    <div class="calificacionesArea-contenedor-persona">
      <div class="calificacionesArea-contenedor-persona-nombre">
        <h2>Juan Luis Urrego</h2>
      </div>
      <div class="calificacionesArea-contenedor-persona-preguntas">
        <div class="calificacionesArea-contenedor-persona-pregunta">
          <p>ESTA SERIA LA PREGUNTA</p>
          <p>ESTA ES NOTA</p>
        </div>

        <div class="calificacionesArea-contenedor-persona-pregunta">
          <p>ESTA SERIA LA PREGUNTA</p>
          <p>ESTA ES NOTA</p>
        </div>

        <div class="calificacionesArea-contenedor-persona-pregunta">
          <p>ESTA SERIA LA PREGUNTA</p>
          <p>ESTA ES NOTA</p>
        </div>


      </div>
      <div class="calificacionesArea-contenedor-persona-barra">
        <div class="progress">
          <div class="progress-bar calificacionesArea-contenedor-persona-barra-color" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div>
      </div>
    </div>


    <?php
    $usuarios = mostarUsuarioCalificacionArea($_SESSION['area'], $conexion);
    // mostramos los usuarios
    while ($usuario = mysqli_fetch_array($usuarios)) {
      $nombre = explode(' ', $usuario['nombre']);
      $apellido = explode(' ', $usuario['apellidos']);
    ?>
      <div class="calificacionesArea-contenedor-persona">
        <div class="calificacionesArea-contenedor-persona-nombre">
          <h2><?php echo $nombre[0] . ' ' . $apellido[0] ?></h2>
        </div>
        <div class="calificacionesArea-contenedor-persona-preguntas">
          <!-- mostramos las preguntas -->
          <?php
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
                echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>" . $pregunta['pregunta'] . ": </p> ";
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $notaPregunta . "</p>";
                echo  '</div>';
              } else {  // para los jefes de cada area
                $ochenta = promedioPreguntaUsuarioJefe($usuario['id'], $mes, $pregunta['id'],$usuario['area'],  $conexion);
                $ochenta = $ochenta[0]  === NULL  ? 0 : $ochenta[0];
                $auto = promedioPreguntaUsuarioAuto($usuario['id'], $mes, $usuario['rol'], $pregunta['id'],$usuario['area'],  $conexion);
                $auto = $auto[0]  === NULL  ? 0 : $auto[0];
                $notaPregunta = (($ochenta * 0.8) + ($auto * 0.2));
                $notaPregunta = number_format($notaPregunta, 2);
                echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>" . $pregunta['pregunta'] . ": </p> ";
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $notaPregunta . "</p>";
                echo  '</div>';
              }
            } else { // para el area administrativa
              $general = promedioPreguntaUsuarioAdministracion($usuario['id'], $mes, $pregunta['id'],$usuario['area'],  $conexion);
              $general = $general[0]  === NULL  ? 0 : $general[0];
              $general = number_format($general, 2);
                echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>" . $pregunta['pregunta'] . ": </p> ";
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $general . "</p>";
                echo  '</div>';
            }
          }
          ?>
        </div>
        <div class="calificacionesArea-contenedor-persona-barra">
          <div class="progress">
            <div class="progress-bar calificacionesArea-contenedor-persona-barra-color" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">25%</div>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>

</body>



</html>