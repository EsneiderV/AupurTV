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

// calificamos 
if (isset($_POST['calificar'])) {
  $idCalificante = $_SESSION['id'];
  $idCalificador = $_POST['id'];
  $area = $_POST['area'];
  $nota = $_POST['valor'];
  $tipo = $_POST['tipo'];
  $rol = $_SESSION['rol'];
  $mensaje = $_POST['mensaje'];
  $mes = date('m');
  $preguntas = mostrarPreguntasid($tipo, $conexion);
  foreach ($nota as $key => $value) {
    guardarCalificaciones($preguntas[$key][0], $idCalificante, $idCalificador, $value, $mes, $area, $preguntas[$key][1], $rol, $_SESSION['area'],$anio, $conexion);
  }
  if ($mensaje != "") {
    guardarComentario($mensaje, $preguntas[0][0], $idCalificante, $idCalificador, $mes, $conexion);
  }

  echo '<script type="text/javascript">
        window.location.href="calificar.php";
        </script>';
}

// autoevaalumos
if (isset($_POST['auto'])) {
  $idCalificante = $_SESSION['id'];
  $idCalificador = $_SESSION['id'];
  $area = $_SESSION['area'];
  $nota = $_POST['valor'];
  $tipo = 0;
  $rol = $_SESSION['rol'];
  $mes = date('m');
  $preguntas = mostrarPreguntasid($tipo, $conexion);
  foreach ($nota as $key => $value) {
    guardarCalificaciones($preguntas[$key][0], $idCalificante, $idCalificador, $value, $mes, $area, $preguntas[$key][1], $rol, $_SESSION['area'],$anio, $conexion);
  }
  echo '<script type="text/javascript">
        window.location.href="calificar.php";
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



registroCalificacionArea($_SESSION['area'],$mes, $anio, $conexion);
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
          echo "<button class='auto-calificar-btnmodal btnmodal' data-bs-toggle='modal' data-bs-target='#autoevaluacion'>Auto evaluación</button>";
        } else {
          echo "<button class='auto-calificar-btnmodal' data-bs-toggle='modal' onclick='return auto()'>Auto evaluación</button>";
        }

        echo "<button class='auto-calificar-btnmodal' data-bs-toggle='modal' data-bs-target='#comentarios'>Comentarios</button>";
        echo "</div>";

        echo " </div>";
        echo "<div class='calificar-administracion'>";
        $areasA = mostrarAreaAdmi($conexion);
        while ($areaA = mysqli_fetch_array($areasA)) { // mostrar cada area en un cuadro
          $usuarios = mostrarUsuario($conexion, $_SESSION['id'], $areaA['codigo']);
          echo "<h3 class='calificar-contenedor-titulo'>" . strtoupper($areaA['nombre']) . "</h1>";
          while ($usuario = mysqli_fetch_array($usuarios)) {
            $calificado = empleadoCalificado($mes, $_SESSION['id'], $usuario['id'], $conexion);
            $modal = '';
            if ($usuario['area'] == $_SESSION['area']) {
              $modal = '#completo';
            } else {
              $modal = '#general';
            }
            if ($calificado->num_rows > 0) {
        ?>
              <button class="calificar-btn-disable" disabled>
                <?php echo $usuario['nombre'] ?>
              </button>
            <?php


            } else {
            ?>
              <button class="btnmodal calificar-btnmodal" data-bs-toggle="modal" data-bs-target="<?php echo $modal ?>" data-nombre="<?php echo $usuario['nombre'] ?>" data-id="<?php echo $usuario['id'] ?>" data-areaid="<?php echo $usuario['area'] ?>">
                <?php echo $usuario['nombre'] ?>
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
        echo "<h3 class='calificar-contenedor-titulo'>" . strtoupper($area['nombre']) . "</h1>";
        while ($usuario = mysqli_fetch_array($usuarios)) {
          $calificado = empleadoCalificado($mes, $_SESSION['id'], $usuario['id'], $conexion);
          $modal = '';
          if ($usuario['area'] == $_SESSION['area']) {
            $modal = '#completo';
          } else {
            $modal = '#general';
          }
          if ($calificado->num_rows > 0) {
      ?>
            <button class="calificar-btn-disable" disabled>
              <?php echo $usuario['nombre'] ?>
            </button>
          <?php


          } else {
          ?>
            <button class="btnmodal calificar-btnmodal" data-bs-toggle="modal" data-bs-target="<?php echo $modal ?>" data-nombre="<?php echo $usuario['nombre'] ?>" data-id="<?php echo $usuario['id'] ?>" data-areaid="<?php echo $usuario['area'] ?>">
              <?php echo $usuario['nombre'] ?>
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
          <form action="" method="POST">
            <input type="text" name="id" class="id_calificado" hidden>
            <input type="text" name="area" class="area" hidden>
            <input type="text" name="tipo" value="0" class="area" hidden>
            <div class="contenedor">
              <?php
              $preguntas = mostrarPreguntas(0, $conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div class="calificar-div-preguntas"> <span class="calificar-prefunta"> <?php echo $pregunta['pregunta'] ?> </span> <input type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"><span class="calificar-numero">5</span></div>
              <?php
              }
              ?>
              <textarea type="text" class="text-field form-control" id="edit_content" placeholder="Mensaje" rows="3" name="mensaje"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="calificar" class="btn btn-primary">Calificar</button>
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
          <form action="" method="POST">
            <input type="text" name="id" class="id_calificado1" hidden>
            <input type="text" name="area" class="area1" hidden>
            <input type="text" name="tipo" value="1" class="area" hidden>
            <div class="contenedor1">
              <?php
              $preguntas = mostrarPreguntas(1, $conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div class="calificar-div-preguntas"> <span class="calificar-prefunta"> <?php echo $pregunta['pregunta'] ?> </span> <input type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"><span class="calificar-numero">5</span></div>
              <?php
              }
              ?>
              <textarea type="text" class="text-field form-control" id="edit_content" placeholder="Mensaje" rows="5" name="mensaje"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="calificar" class="btn btn-primary">Calificar</button>
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
          <h5 class="modal-title1 calificar-modal-titulo modal-title-calificar" id="exampleModalLabel">Auto evaluación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
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
          <button type="submit" name="auto" class="btn btn-primary">Calificar</button>
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
          <h5 class="modal-title" id="exampleModalLabel">Comentarios</h5>
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

  <script>
    const contenedordiv = document.querySelector('.calificar-cuerpo');
    contenedordiv.addEventListener('click', e => {
      let btnmodal;
      if (e.target.classList.contains('btnmodal')) {
        btnmodal = e.target
        console.log('Hola')


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