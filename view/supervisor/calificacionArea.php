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
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
registroCalificacionArea($_SESSION['area'], $mes, $anio, $conexion);
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
    <a href="supervisor.php" class="calificar-volver-atras"> ᗕ Atrás</a>
    <h1 class="calificar-titulo">Calificación área</h1>
  </div>


  <div class="calificacionesArea-contenedor-principal">

    <?php
    $usuarios = mostarUsuarioCalificacionArea($_SESSION['area'], $conexion);
    $totalUsuario =  totalUsuariosArea($_SESSION['area'], $conexion);
    $totalUsuario = mysqli_fetch_array($totalUsuario);

    // mostramos los usuarios
    while ($usuario = mysqli_fetch_array($usuarios)) {
      $nombre = explode(' ', $usuario['nombre']);
      $apellido = explode(' ', $usuario['apellidos']);
      $i = 0;
      $promT = 0;

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
                $promT = $promT + $notaPregunta;
                $i++;
                echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>" . $pregunta['pregunta'] . " : </p> ";
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $notaPregunta . "</p>";
                echo  '</div>';
              } else {  // para los jefes de cada area
                $ochenta = promedioPreguntaUsuarioJefe($usuario['id'], $mes, $pregunta['id'], $usuario['area'],  $conexion);
                $ochenta = $ochenta[0]  === NULL  ? 0 : $ochenta[0];
                $auto = promedioPreguntaUsuarioAuto($usuario['id'], $mes, $usuario['rol'], $pregunta['id'], $usuario['area'],  $conexion);
                $auto = $auto[0]  === NULL  ? 0 : $auto[0];
                $notaPregunta = (($ochenta * 0.8) + ($auto * 0.2));
                $notaPregunta = number_format($notaPregunta, 2);
                $promT = $promT + $notaPregunta;
                $i++;
                echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>" . $pregunta['pregunta'] . " : </p> ";
                echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $notaPregunta . "</p>";
                echo  '</div>';
              }
            } else { // para el area administrativa
              $general = promedioPreguntaUsuarioAdministracion($usuario['id'], $mes, $pregunta['id'], $usuario['area'],  $conexion);
              $general = $general[0]  === NULL  ? 0 : $general[0];
              $general = number_format($general, 2);
              $promT = $promT + $general;
              $i++;
              echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
              echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>" . $pregunta['pregunta'] . " : </p> ";
              echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $general . "</p>";
              echo  '</div>';
            }
          }

          $promedioTotal = ($promT / $i);
          $promedioTotal = number_format($promedioTotal, 2);
          echo ' <div class="calificacionesArea-contenedor-persona-pregunta">';
          echo "<p class='calificacionesArea-contenedor-persona-pregunta-nombre'>Total : </p> ";
          echo "<p class='calificacionesArea-contenedor-persona-pregunta-nota'>" . $promedioTotal . "</p>";
          echo  '</div>';

          $totaldeNotasRequeridas = $totalUsuario[0] * $i;
          $totaldeNotas = totalDeCalificaciones($usuario['id'], $mes, $conexion);

          $PromedioFinal =  ((100 * $totaldeNotas[0]) / $totaldeNotasRequeridas);
          $PromedioFinal =  number_format($PromedioFinal, 0)
          ?>
        </div>
        <div class="calificacionesArea-contenedor-persona-barra">
          <div class="progress">
            <div class="progress-bar calificacionesArea-contenedor-persona-barra-color" role="progressbar" style="width: <?php echo $PromedioFinal ?>%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"><?php echo $PromedioFinal ?>%</div>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>

  <h3 class="titulo-botones-mes-calificacion">Calificación por mes</h3>
  <div class="calificacionAreaBotonesMesCalificaciones">
    <?php
    $mesesNomNum = [['01', 'Enero'], ['02', 'Febrero'], ['03', 'Marzo'], ['04', 'Abril'], ['05', 'Mayo'], ['06', 'Junio'], ['07', 'Julio'], ['08', 'Agosto'], ['09', 'Septiembre'], ['10', 'Octubre'], ['11', 'Noviembre'], ['12', 'Diciembre'],];

    foreach ($mesesNomNum as $value) {
      $area = $_SESSION['area'];
      $consultaSelectmesexiste = preguntarmesexistecalificacionesmespersonaarea($value[0], $area, $conexion);

      if ($value[0] == $mes) {
        $notasparaActivar = $totaldeNotasRequeridas * $totalUsuario[0];
        $totalnotasmes = totaldenotasporareamesactual($value[0], $area, $anio, $conexion);

        if ($totalnotasmes[0] >= $notasparaActivar) {
          echo "<a href='../../pdf/pdf-notaAreaMes.php?area=$area&mes=$mes' class='calificacionAreaBotonMes'>$value[1]</a>";
        } else {
          echo "<a href='../../pdf/pdf-notaAreaMes.php?area=$area&mes=$mes' class='calificacionAreaBotonMes not-active'>$value[1]</a>";
        }
      } else if ($consultaSelectmesexiste->num_rows > 0) {
        echo "<a href='../../pdf/pdf-notaAreaMes.php?area=$area&mes=$mes' class='calificacionAreaBotonMes'>$value[1]</a>";
      } else {
        echo "<a href='../../pdf/pdf-notaAreaMes.php?area=$area&mes=$mes' class='calificacionAreaBotonMes not-active'>$value[1]</a>";
      }
    }
    ?>

  </div>



  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

  <div class="desactivar-contenedor-650">
    <h3 class="titulo-diagrama-por-año">
      <?php
      if (isset($_POST['aniodiagrama'])) {
        $anio = $_POST['aniodiagrama'];
        echo "Notas " . $anio;
      } else {
        echo "Notas " . $anio;
      }
      ?>
    </h3>
  </div>

  <div class="contenedor-canva-buscador desactivar-contenedor-650">
    <div class="contenedor-de-canvas">
      <canvas id="datosD"></canvas>
    </div>
    <form id="formularioCambiarAnio" action="" method="post">
      <select class="select-año" id="SelectCambiarAnio" name="aniodiagrama" id="">
        <option value="0">Seleccione año</option>
        <?php
        $consultaSelects = traerAniosQueTiene($conexion);
        while ($consultaSelect = mysqli_fetch_array($consultaSelects)) {
        ?>
          <option value="<?php echo $consultaSelect['anio'] ?>"><?php echo $consultaSelect['anio'] ?></option>
        <?php
        }
        ?>
      </select>
    </form>
  </div>



  <?php

  if (isset($_POST['aniodiagrama'])) {
    $anio = $_POST['aniodiagrama'];
    $mesMin = sacarMesMin($anio, $_SESSION['area'], $conexion);
  } else {
    $mesMin = sacarMesMin($anio, $_SESSION['area'], $conexion);
  }


  $arreglodeMeses = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
  $mesesquehay = [];
  $mesesParaMostrarenDiagrama = [];

  while ($array = mysqli_fetch_array($mesMin)) {
    array_push($mesesquehay, $array['mes']);
  }

  foreach ($arreglodeMeses as  $value) {
    if (in_array($value, $mesesquehay)) {
      array_push($mesesParaMostrarenDiagrama, $value);
    } else {
      array_push($mesesParaMostrarenDiagrama, 'null');
    }
  }


  ?>


  <script>
    const formParaLosAnios = document.querySelector('#formularioCambiarAnio')
    const selectCambiarAnio = document.querySelector('#SelectCambiarAnio')
    selectCambiarAnio.addEventListener('input', e => {
      formParaLosAnios.submit();
    })


    const dynamicColors = function() {
      const r = Math.floor(Math.random() * 255);
      const g = Math.floor(Math.random() * 255);
      const b = Math.floor(Math.random() * 255);
      return "rgb(" + r + "," + g + "," + b + ")";
    };


    const ctx = document.getElementById('datosD');
    ctx.height = 100;
    const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', ];
    data = {
      labels,
      datasets: [

        <?php

        $preguntasTotales = sacarPreguntasDiagrama($conexion);
        while ($preguntaTotale = mysqli_fetch_array($preguntasTotales)) {
          $notasTotales = '';

          foreach ($mesesParaMostrarenDiagrama as $value) {
            if ($value != 'null') {
              $notamesarea = NotaPorMesAnioArea($preguntaTotale['id'], $value, $anio, $_SESSION['area'], $conexion);
              $notamesarea = mysqli_fetch_array($notamesarea)[0];
              $notasTotales = $notasTotales . $notamesarea . ",";
            } else {
              $notasTotales = $notasTotales . "0,";
            }
          }


          // while ($mese = mysqli_fetch_array($meses)) {
          //   $notamesarea = NotaPorMesAnioArea($preguntaTotale['id'], $mese['mes'], $anio, $_SESSION['area'], $conexion);
          //   $notamesarea = mysqli_fetch_array($notamesarea)[0];
          //   $notasTotales = $notasTotales . $notamesarea . ",";
          // }
          $nombreDiagrama = $preguntaTotale['pregunta'];
          echo "{";
          echo "label: '$nombreDiagrama',";
          echo "data: [" . $notasTotales . "] ,";
          echo "
            fill: false,
            borderColor: dynamicColors(),
            tension: 0.1,
            yAxisID: 'yAxis'
          },
          ";
        }
        ?>
      ]

    };

    const options = {
      scales: {
        yAxis: {
          type: "linear",
          position: "left",
          min: 0,
          max: 10
        }
      }
    }


    const config = {
      type: 'line',
      data: data,
      options: options
    }

    const myChart = new Chart(ctx, config);
  </script>


</body>

</html>