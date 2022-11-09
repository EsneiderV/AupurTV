<?php
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
// verificamos que si se aya logiado primero el usuario
session_start();
if (!isset($_SESSION['rol'])) {
  echo '<script type="text/javascript">
        window.location.href="../../index.php";
        </script>';
}

$idCalificante = $_SESSION['id'];
$idCalificador = $_SESSION['id'];
$autoCalificacion = empleadoAutocalificado($mes, $idCalificante, $idCalificador, $conexion);

//redireccionar para volver atras
$redirecionar = '';
switch ($_SESSION['rol']) {
  case '1':
    $redirecionar = '../empleado/empleado.php';
    break;

  case '2':
    $redirecionar = '../supervisor/supervisor.php';
    break;

  case '3':
    $redirecionar = '../supervisor/supervisor.php';
    break;
  default:
    # code...
    break;
}

registroCalificacionArea($_SESSION['area'], $mes, $anio, $conexion);
registroCalificacionpersonaGeneral($mes, $anio, $area, $conexion);

?>

<!DOCTYPE html>
<html lang="en" class="calificar-html">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="https://aupur.co/wp-content/uploads/2021/07/cropped-Logos-AUPUR-32x32.png" sizes="32x32">
  <link rel="stylesheet" href="../../controllers/bootstrap/bootstrap.min.css">
  <script src="../../controllers/bootstrap/bootstrap.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../controllers/css/style.css">
  <title>Calificar - Aupur Televisión</title>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>

<body class="calificar-body">

  <div class="calificar-nav">
    <a class="calificar-volver-atras" href="<?php echo $redirecionar ?>"> ᗕ Atrás</a>
    <h1 class="calificar-titulo">Calificación</h1>
  </div>


  <div class="calificar-cuerpo">
    <!-- si ya esta auto evaluado sesavilitamos el boton -->
    <div class="calificar-auto-admi">

      <div class="calificar-auto">


        <img class="calificar-imagen" src="data:<?php echo $_SESSION['tipo_imagen'] ?>;base64,<?php echo base64_encode($_SESSION['imagen']) ?>" alt="foto de perfil">

        <?php

        echo "<div class='calificar-auto-comentario'>";
        if ($autoCalificacion->num_rows <= 0) {
          echo "<button id='btnAutoJq' class='auto-calificar-btnmodal btnmodal' data-bs-toggle='modal' data-bs-target='#autoevaluacion'>Auto Evaluación</button>";
        } else {
          echo "<button class='auto-calificar-btnmodal' data-bs-toggle='modal' onclick='return auto()'>Auto Evaluación</button>";
        }

        echo "<button class='auto-calificar-btnmodal' data-bs-toggle='modal' data-bs-target='#comentarios'>Comentarios</button>";
        echo "<button id='abrircontenedorvercalificacion' class='auto-calificar-btnmodal' data-bs-toggle='modal' data-bs-target='#misNotas'>Mis Notas</button>";
        echo "</div>";

        echo " </div>";
        echo "<div class='calificar-administracion'>";
        $areasA = mostrarAreaAdmi($conexion);
        while ($areaA = mysqli_fetch_array($areasA)) { // mostrar cada area en un cuadro
          $usuarios = mostrarUsuario($conexion, $_SESSION['id'], $areaA['codigo']);
          echo "<h3 class='calificar-contenedor-titulo'>" . ucwords($areaA['nombre']) . "</h3>";
          while ($usuario = mysqli_fetch_array($usuarios)) {
            $calificado = empleadoCalificado($mes, $_SESSION['id'], $usuario['id'], $conexion);
            $modal = '';
            if ($usuario['area'] == $_SESSION['area']) {
              $modal = '#completo';
            } else {
              $modal = '#general';
            }
            if ($calificado->num_rows > 0) {
              $apellido = explode(' ',$usuario['apellidos']) ;
              $nombreCompleto = $usuario['nombre']. ' ' .$apellido[0];
              $nombreCompleto = ucwords($nombreCompleto);
        ?>
              <button class="calificar-btn-disable" disabled>
                <?php 
                 echo $nombreCompleto;
                 ?>
              </button>
            <?php


            } else {
              $apellido = explode(' ',$usuario['apellidos']) ;
              $nombreCompleto = $usuario['nombre']. ' ' .$apellido[0];
              $nombreCompleto = ucwords($nombreCompleto);

            ?>
              <button class="btnmodal calificar-btnmodal" id="<?php echo $usuario['id'] ?>" data-bs-toggle="modal" data-bs-target="<?php echo $modal ?>" data-nombre="<?php echo $nombreCompleto ?>" data-id="<?php echo $usuario['id'] ?>" data-areaid="<?php echo $usuario['area'] ?>">
              <?php 
                 echo $nombreCompleto;
                 ?>
              </button>

        <?php
            }
          }
        }
        ?>
      </div>
    </div>




    <div class="calificar-empleados">


      <?php

      $areas = mostrarArea($conexion); //recolecta todas las areas
      while ($area = mysqli_fetch_array($areas)) { // mostrar cada area en un cuadro
        $usuarios = mostrarUsuario($conexion, $_SESSION['id'], $area['codigo']);
        echo "<div class='calificar-contenedor-empleado'>";
        echo "<h3 class='calificar-contenedor-titulo'>" . ucwords($area['nombre']) . "</h1>";
        while ($usuario = mysqli_fetch_array($usuarios)) {
          $calificado = empleadoCalificado($mes, $_SESSION['id'], $usuario['id'], $conexion);
          $modal = '';
          if ($usuario['area'] == $_SESSION['area']) {
            $modal = '#completo';
          } else {
            $modal = '#general';
          }
          if ($calificado->num_rows > 0) {
            $apellido = explode(' ',$usuario['apellidos']) ;
              $nombreCompleto = $usuario['nombre']. ' ' .$apellido[0];
              $nombreCompleto = ucwords($nombreCompleto);
      ?>
            <button class="calificar-btn-disable" disabled>
            <?php echo $nombreCompleto;
                 ?>
            </button>
          <?php


          } else {
            $apellido = explode(' ',$usuario['apellidos']) ;
            $nombreCompleto = $usuario['nombre']. ' ' .$apellido[0];
            $nombreCompleto = ucwords($nombreCompleto);
          ?>
            <button class="btnmodal calificar-btnmodal" id="<?php echo $usuario['id'] ?>" data-bs-toggle="modal" data-bs-target="<?php echo $modal ?>" data-nombre="<?php echo $nombreCompleto?>" data-id="<?php echo $usuario['id'] ?>" data-areaid="<?php echo $usuario['area'] ?>">
            <?php 
            echo $nombreCompleto;
                 ?>
            </button>

      <?php
          }
        }
        echo "</div>";
      }
      ?>

      <div class="calificar-basio"></div>
    </div>

  </div>



  <!-- Modal para todas las preguntas -->
  <div class="modal fade" id="completo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title1 calificar-modal-titulo modal-title-calificar" id="exampleModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formTodasPreguntas" method="POST">
            <input type="text" name="id" class="id_calificado" hidden>
            <input type="text" name="area" class="area" hidden>
            <input type="text" name="tipo" value="0" class="area" hidden>
            <div class="contenedor">
              <?php
              $preguntas = mostrarPreguntas(0, $conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div class="calificar-div-preguntas"> <span class="calificar-prefunta"> <?php echo $pregunta['pregunta'] ?> </span> <input class="calificarModalTodasPreguntas" type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"><span class="calificar-numero calificarModalTodasPreguntasSpan">5</span></div>
              <?php
              }
              ?>
              <textarea type="text" class="text-field form-control" id="edit_content" placeholder="Mensaje" rows="3" name="mensaje"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" id="btnTodasPreguntas" name="calificar" class="btn btn-primary">Calificar</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal para las preguntas generales -->
  <div class="modal fade" id="general" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title calificar-modal-titulo modal-title-calificar" id="exampleModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formGpreguntas" method="POST">
            <input type="text" name="id" class="id_calificado1" hidden>
            <input type="text" name="area" class="area1" hidden>
            <input type="text" name="tipo" value="1" class="area" hidden>
            <div class="contenedor1">
              <?php
              $preguntas = mostrarPreguntas(1, $conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div class="calificar-div-preguntas"> <span class="calificar-prefunta"> <?php echo $pregunta['pregunta'] ?> </span> <input class="calificarModalPreguntasGeneral" type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"><span class="calificar-numero calificarModalPreguntasGeneralSpan">5</span></div>
              <?php
              }
              ?>
              <textarea type="text" class="text-field form-control" id="edit_content" placeholder="Mensaje" rows="5" name="mensaje"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="btnGPreguntas" name="calificar" class="btn btn-primary">Calificar</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal para la autoevalucion -->
  <div class="modal fade" id="autoevaluacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title1 calificar-modal-titulo modal-title-calificar" id="exampleModalLabel">Auto Evaluación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formAuto" method="POST">
            <div class="contenedor2">
              <?php
              $preguntas = mostrarPreguntas(0, $conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div class="calificar-div-preguntas"> <span class="calificar-prefunta"> <?php echo $pregunta['pregunta'] ?> </span> <input type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"><span class="calificar-numero">5</span></div>
              <?php
              }
              ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="btnAuto" name="auto" class="btn btn-primary">Calificar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para los comentarios -->
  <div class="modal fade" id="comentarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title1 calificar-modal-titulo modal-title-calificar" id="exampleModalLabel">Comentarios</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="calificar-div-general-comentarios">
            <?php
            $comentarios = empleadoComentario($mes, $idCalificador, $conexion);;
            while ($comentario = mysqli_fetch_array($comentarios)) {
            ?>
              <div class="calificar-div-comentarios"> <span class="calificar-comentarios"> <?php echo $comentario['mensaje'] ?></div>
              <hr>
            <?php
            }
            ?>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- calificarnotaPersona-desactivarContenedor -->
  <div id="calificarcontenedorvercalificacion" class="calificarmostrarnotascadaempleadocontenedor calificarnotaPersona-desactivarContenedor">
    <div class="calificarmostrarnotascadaempleado">
      <div class="inventarioAreaGerente-emergente">
        <div class="invetarioArea-emergente-navar">          
          <h1 class='invetarioArea-emergente-nombre'>Mis Notas</h1>
          <span id="cerrarverCalificarx" class="inventarioAreaGerente-emergente-x">X</span>
        </div>
        <div class="invetarioArea-emergente-main">
          <div class="calificarvernotamescontenedores">

            <div class="calificacionesArea-contenedor-persona calificar-contenedores-nota">
              <div class="calificacionesArea-contenedor-persona-nombre">
                <h2>Grupo</h2>
              </div>
              <div class="calificacionesArea-contenedor-persona-preguntas">

                <?php
                $preguntasMostrarPersona = AdConsultarPreguntas($conexion);
                $notaFinal = 0;
                $i = 0;
                while ($pregunta = mysqli_fetch_array($preguntasMostrarPersona)) {
                  $notaPersonas = mostrarnotasmespersonacalificaciones($_SESSION['area'], $mes, $_SESSION['id'], $pregunta['id'], $conexion);
                  $nota = 0.00;
                  if ($notaPersonas->num_rows > 0) {
                    while ($notaPersona =  mysqli_fetch_array($notaPersonas)) {
                      $nota = $notaPersona['nota'];
                    }
                  }

                ?>
                  <div class="calificacionesArea-contenedor-persona-pregunta">
                    <p class='calificacionesArea-contenedor-persona-pregunta-nombre'><?php echo $pregunta['pregunta'] ?></p>
                    <p class='calificacionesArea-contenedor-persona-pregunta-nota'><?php echo $nota ?></p>
                  </div>
                <?php
                  $i++;
                  $notaFinal = $notaFinal + $nota;
                }
                ?>
                <div class="calificacionesArea-contenedor-persona-pregunta">
                  <p class='calificacionesArea-contenedor-persona-pregunta-nombre'>Total</p>
                  <p class='calificacionesArea-contenedor-persona-pregunta-nota'><?php echo number_format($notaFinal / $i, 2); ?></p>
                </div>
              </div>
            </div>



            <div class="calificacionesArea-contenedor-persona calificar-contenedores-nota">
              <div class="calificacionesArea-contenedor-persona-nombre">
                <h2>Empresa</h2>
              </div>
              <div class="calificacionesArea-contenedor-persona-preguntas">


                <?php
                //consultar las preguntas generales
                $preguntasGenerales = traerPreguntas($conexion);
                $i = 0;
                while ($preguntaGeneral = mysqli_fetch_array($preguntasGenerales)) {
                  $i++;
                  $consultaNotaGeneral =  consultarPreguntasGeneralesPorPersona($preguntaGeneral['id'], $_SESSION['id'], $mes, $anio, $_SESSION['area'], $conexion);
                  $nota =  mysqli_fetch_array($consultaNotaGeneral)[0];
                  $promedioTotal = $promedioTotal + $nota;
                ?>
                  <div class="calificacionesArea-contenedor-persona-pregunta">
                    <p class='calificacionesArea-contenedor-persona-pregunta-nombre'><?php echo $preguntaGeneral['pregunta'] ?></p>
                    <p class='calificacionesArea-contenedor-persona-pregunta-nota'><?php echo $nota ?></p>
                  </div>
                <?php
                }

                ?>

                <div class="calificacionesArea-contenedor-persona-pregunta">
                  <p class='calificacionesArea-contenedor-persona-pregunta-nombre'>Total</p>
                  <p class='calificacionesArea-contenedor-persona-pregunta-nota'><?php echo number_format($promedioTotal / $i, 2)?></p>
                </div>

              </div>
            </div>

          </div>

          <div class="contenedordecanvasprincipal desactivar-contenedor-650">
            <h4 class="tituloContenedoresCanvasPrincipal">Calificaciones mensuales del grupo</h4>
            <div class="contenedor-de-canvas">
              <canvas id="datosD"></canvas>
            </div>
          </div>

          <div class="contenedordecanvasprincipal desactivar-contenedor-650">
          <h4 class="tituloContenedoresCanvasPrincipal">Calificaciones mensuales de la empresa</h4>
            <div class="contenedor-de-canvas">
              <canvas id="datosDE"></canvas>
            </div>
          </div>

        </div>
        <div class="invetarioArea-emergente-footer">
          <button id="cerrarvercalicacionBtn" class="btn btn-secondary invetarioArea-emergente-footer-btn"> Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <?php
  $arreglodeMeses = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
  $mesesquehay = [];
  $meses = sacarMesesDiagramaPersona($_SESSION['id'], $conexion);
  while ($array = mysqli_fetch_array($meses)) {
    array_push($mesesquehay, $array['mes']);
  }
  $mesesParaMostrarenDiagrama = [];

  foreach ($arreglodeMeses as  $value) {
    if (in_array($value, $mesesquehay)) {
      array_push($mesesParaMostrarenDiagrama, $value);
    } else {
      array_push($mesesParaMostrarenDiagrama, 'null');
    }
  }

  $mesesquehayGeneral = [];


    $mesesGeneral = sacarMesesDiagramaPersonaGeneral($_SESSION['id'], $conexion);


  while ($arrayG = mysqli_fetch_array($mesesGeneral)) {
    array_push($mesesquehayGeneral, $arrayG['mes']);
  }

  $mesesParaMostrarenDiagramaGeneral = [];

  foreach ($arreglodeMeses as  $value) {
    if (in_array($value, $mesesquehayGeneral)) {
      array_push($mesesParaMostrarenDiagramaGeneral, $value);
    } else {
      array_push($mesesParaMostrarenDiagramaGeneral, 'null');
    }
  }

  ?>

  <script>
    const dynamicColors = function(i) {
      const arregloDeColores = [
        'rgb(199, 81, 220)',
        'rgb(87, 126, 246)',
        'rgb(119, 177, 21)',
        'rgb(101, 224, 20)',
        'rgb(23, 130, 230)',
        'rgb(173, 77, 244)',
        'rgb(255, 113, 56)',
        'rgb(30, 108, 165)',
        'rgb(229, 61, 119)',
        'rgb(207, 78, 78)',
        'rgb(240, 121, 31)',
      ]

      return arregloDeColores[i];
    };

    // diagrama para Todas las preguntas
    const ctx = document.getElementById('datosD');
    ctx.height = 100;
    const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', ];
    data = {
      labels,
      datasets: [

        <?php
        $i = 0;
        $preguntasTotales = sacarPreguntasDiagrama($conexion);
        while ($preguntaTotale = mysqli_fetch_array($preguntasTotales)) {

          $notasTotales = '';
          foreach ($mesesParaMostrarenDiagrama as $value) {
            if ($value != 'null') {
              $notamesarea = NotaPorMesAreaPersona($preguntaTotale['id'], $value, $_SESSION['id'], $conexion);
              $notamesarea = mysqli_fetch_array($notamesarea)[0];
              $notasTotales = $notasTotales . $notamesarea . ",";
            } else {
              $notasTotales = $notasTotales . "0,";
            }
          }


          $nombreDiagrama = $preguntaTotale['pregunta'];          

          echo "{";
          echo "label: '$nombreDiagrama',";
          echo "data: [" . $notasTotales . "] ,";
          echo "
            fill: false,
            borderColor: dynamicColors($i),
            tension: 0.1,
            yAxisID: 'yAxis'
          },
          ";
          $i++;
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







    // Diagra para  calificaciones por grupo por persona
    const ctxE = document.getElementById('datosDE');
    ctxE.height = 100;
    data = {
      labels,
      datasets: [

        <?php
        $i = 0;
        $preguntasTotales = traerPreguntas($conexion);
        while ($preguntaTotale = mysqli_fetch_array($preguntasTotales)) {

          $notasTotales = '';
          foreach ($mesesParaMostrarenDiagramaGeneral as $value) {
            if ($value != 'null') {
              $notamesarea = NotaPorMesAreaPersonaGeneralMes($preguntaTotale['id'], $value, $_SESSION['id'], $conexion);
              $notamesarea = mysqli_fetch_array($notamesarea)[0];
              $notasTotales = $notasTotales . $notamesarea . ",";
            } else {
              $notasTotales = $notasTotales . "0,";
            }
          }


          $nombreDiagrama = $preguntaTotale['pregunta'];


          echo "{";
          echo "label: '$nombreDiagrama',";
          echo "data: [" . $notasTotales . "] ,";
          echo "
            fill: false,
            borderColor: dynamicColors($i),
            tension: 0.1,
            yAxisID: 'yAxis'
          },
          ";
          $i++;
        }
        ?>
      ]

    };

    const optionsE = {
      scales: {
        yAxis: {
          type: "linear",
          position: "left",
          min: 0,
          max: 10
        }
      }
    }


    const configE = {
      type: 'line',
      data: data,
      options: optionsE
    }

    const myChartE = new Chart(ctxE, configE);
  </script>



  <script src="../../controllers/js/jquery-3.2.1.min.js"></script>
  <!-- Para todas las preguntas -->
  <script type="text/javascript">

   function reiniciarModalGenerales(){
    const PreguntasGenerales = document.querySelectorAll('.calificarModalPreguntasGeneral')

      PreguntasGenerales.forEach(pregunta => {
        pregunta.value = 5;
    });

    const calificarModalPreguntasGeneralSpan = document.querySelectorAll('.calificarModalPreguntasGeneralSpan')

      calificarModalPreguntasGeneralSpan.forEach(numeroPregunta => {
        numeroPregunta.textContent = '5'
      })

   }

   function reiniciarModalTodas(){
    const calificarModalTodasPreguntas = document.querySelectorAll('.calificarModalTodasPreguntas')
    calificarModalTodasPreguntas.forEach(pregunta => {
      pregunta.value = 5;
    });

    const calificarModalTodasPreguntasSpan = document.querySelectorAll('.calificarModalTodasPreguntasSpan')
    calificarModalTodasPreguntasSpan.forEach(numeroPregunta => {
      numeroPregunta.textContent = '5'
    })
   }

    $(document).ready(function() {
      $('#btnTodasPreguntas').click(function() {
        let datos = $('#formTodasPreguntas').serialize();
        $.ajax({
          type: "POST",
          url: "insertarTpreguntas.php",
          data: datos,
          success: function(r) {
            if (!r) {
              let id = $('#formTodasPreguntas').serializeArray();
              id = id.filter(value => value.name == 'id')
              id = id[0].value;
              $(`#${id}`).addClass('calificar-btn-disable')
              $(`#${id}`).attr('disabled', true)
              $("#completo").modal('hide');
              alert("agregado con exito");
              reiniciarModalTodas()
            } else {
              alert("Ya lo calificastes");
              reiniciarModalTodas()
            }
          }
        });
        return false;
      });
    });
  </script>

  <!-- para las preguntas generales -->
  <script type="text/javascript">
    const abrircontenedorvercalificacion = document.querySelector('#abrircontenedorvercalificacion')
    const calificarcontenedorvercalificacion = document.querySelector('#calificarcontenedorvercalificacion')
    const cerrarverCalificarx = document.querySelector('#cerrarverCalificarx');
    const cerrarvercalicacionBtn = document.querySelector('#cerrarvercalicacionBtn')

    abrircontenedorvercalificacion.addEventListener('click', () => {
      calificarcontenedorvercalificacion.classList.remove('calificarnotaPersona-desactivarContenedor')
    })

    cerrarverCalificarx.addEventListener('click', () => {
      calificarcontenedorvercalificacion.classList.add('calificarnotaPersona-desactivarContenedor')
    })

    cerrarvercalicacionBtn.addEventListener('click', () => {
      calificarcontenedorvercalificacion.classList.add('calificarnotaPersona-desactivarContenedor')
    })

    $(document).ready(function() {
      $('#btnGPreguntas').click(function() {
        let datos = $('#formGpreguntas').serialize();
        $.ajax({
          type: "POST",
          url: "insertarTpreguntas.php",
          data: datos,
          success: function(r) {
            if (!r) {
              let id = $('#formGpreguntas').serializeArray();
              id = id.filter(value => value.name == 'id')
              id = id[0].value;
              $(`#${id}`).addClass('calificar-btn-disable')
              $(`#${id}`).attr('disabled', true)
              $("#general").modal('hide');
              alert("agregado con exito");
              reiniciarModalGenerales();
            } else {
              alert("Ya lo calificastes");
              reiniciarModalGenerales();
            }
          }
        });
        return false;
      });
    });
  </script>

  <!-- para la auto -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#btnAuto').click(function() {
        let datos = $('#formAuto').serialize();
        $.ajax({
          type: "POST",
          url: "auto.php",
          data: datos,
          success: function(r) {
            if (!r) {
              $(`#btnAutoJq`).attr('disabled', true)
              $("#autoevaluacion").modal('hide');
              alert("agregado con exito");
            } else {
              alert("Ya lo calificastes");
            }
          }
        });
        return false;
      });
    });
  </script>



  <script type="text/javascript">
    const contenedordiv = document.querySelector('.calificar-cuerpo');
    contenedordiv.addEventListener('click', e => {
      let btnmodal;
      if (e.target.classList.contains('btnmodal')) {
        btnmodal = e.target


        document.querySelector('.modal-title').textContent = `${btnmodal.dataset.nombre}`
        document.querySelector('.id_calificado').value = `${btnmodal.dataset.id}`
        document.querySelector('.area').value = `${btnmodal.dataset.areaid}`

        document.querySelector('.modal-title1').textContent = `${btnmodal.dataset.nombre}`
        document.querySelector('.id_calificado1').value = `${btnmodal.dataset.id}`
        document.querySelector('.area1').value = `${btnmodal.dataset.areaid}`
      }

    })



    const btnmodals = document.querySelectorAll('.btnmodal');

    btnmodals.forEach(btnmodal => {
      btnmodal.addEventListener('click', buscarInpust)
    })

    function buscarInpust() {
      const inputs = document.querySelectorAll('input[type="range"]');
      inputs.forEach(input => {
        input.addEventListener('input', e => {
          const valor = e.target.value;
          const span = e.target.nextSibling;
          span.textContent = valor;
        })
      })
    }


    function auto() {
      alert('Este mes ya te Autoevaluastes')
    }
  </script>



</body>

</html>