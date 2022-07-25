<?php
include_once '../../controllers/php/funciones.php'; // traemos las funciones que contiene las consultas sql
include_once '../../models/Conexion.php'; // traemos la conexion con la base de datos 


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
  $mes = date('m');
  $preguntas = mostrarPreguntasid($tipo, $conexion);
  foreach ($nota as $key => $value) {
     guardarCalificaciones($preguntas[$key][0], $idCalificante, $idCalificador, $value, $mes, $area,$preguntas[$key][1],$rol,$_SESSION['area'], $conexion);
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
     guardarCalificaciones($preguntas[$key][0], $idCalificante, $idCalificador, $value, $mes, $area,$preguntas[$key][1],$rol,$_SESSION['area'], $conexion);
  }
  echo '<script type="text/javascript">
        window.location.href="calificar.php";
        </script>';
}

$idCalificante = $_SESSION['id'];
$idCalificador = $_SESSION['id'];
$mes = date('m');
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

?>

<!DOCTYPE html>
<html lang="en">

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
  <div class="calificar-contenedor-auto">
    <a href="<?php echo $redirecionar ?>"> ᗕ Volver atrás</a>
    <h1>Calificar</h1>

  <!-- si ya esta auto evaluado sesavilitamos el boton -->
    <?php
    if ($autoCalificacion->num_rows <= 0) {
      echo "<button data-bs-toggle='modal' data-bs-target='#autoevaluacion'>Auto evaluación</button>";
    } else {
      echo "<button data-bs-toggle='modal' onclick='return auto()'>Auto evaluación</button>";
    }

    ?>

  </div>

  <div class="calificar-contenedorp-empleado">
    <?php
    $mes = date('m'); // retiene el mes actual
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
          <button class="btnmodal calificar-btnmodal" data-bs-toggle="modal" data-nombre="<?php echo $usuario['nombre'] ?>" data-id="<?php echo $usuario['id'] ?>" data-areaid="<?php echo $usuario['area'] ?>" disabled>
            <span class="calificar-contenedor-imagen">
              <img class="calificar-imagen-js" src="data:<?php echo $usuario['tipo_imagen'] ?>;base64,<?php echo base64_encode($usuario['imagen']) ?>" alt="foto de perfil">
            </span>
            <span class="calificar-nombre-btn"><?php echo $usuario['nombre'] ?></span>
          </button>
        <?php


        } else {
        ?>
          <button class="btnmodal calificar-btnmodal" data-bs-toggle="modal" data-bs-target="<?php echo $modal ?>" data-nombre="<?php echo $usuario['nombre'] ?>" data-id="<?php echo $usuario['id'] ?>" data-areaid="<?php echo $usuario['area'] ?>">
            <span class="calificar-contenedor-imagen"><img class="calificar-imagen-js" src="data:<?php echo $usuario['tipo_imagen'] ?>;base64,<?php echo base64_encode($usuario['imagen']) ?>" alt="foto de perfil"></span>
            <span class="calificar-nombre-btn"><?php echo $usuario['nombre'] ?></span>
          </button>
    <?php
        }
      }
      echo "</div>";
    }
    ?>
  </div>
  <!-- Modal para todas las preguntas -->
  <div class="modal fade" id="completo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
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
                <div><?php echo $pregunta['pregunta'] ?> <input type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"> <span class="numero">5</span></div>
              <?php
              }
              ?>
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
          <h5 class="modal-title1" id="exampleModalLabel"></h5>
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
                <div><?php echo $pregunta['pregunta'] ?> <input type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"> <span class="numero">5</span></div>
              <?php
              }
              ?>
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
          <h5 class="modal-title" id="exampleModalLabel">Auto evaluación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
            <div class="contenedor2">
              <?php
              $preguntas = mostrarPreguntas(0, $conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div><?php echo $pregunta['pregunta'] ?> <input type="range" name="valor[]" min="1" max="10" value="5" id="input" step="1"> <span class="numero">5</span></div>
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

  <script>
    const contenedordiv = document.querySelector('.calificar-contenedorp-empleado');
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

      if (e.target.classList.contains('calificar-contenedor-imagen') || e.target.classList.contains('calificar-nombre-btn')) {
        btnmodal = e.target.parentNode

        document.querySelector('.modal-title').textContent = `${btnmodal.dataset.nombre}`
        document.querySelector('.id_calificado').value = `${btnmodal.dataset.id}`
        document.querySelector('.area').value = `${btnmodal.dataset.areaid}`

        document.querySelector('.modal-title1').textContent = `${btnmodal.dataset.nombre}`
        document.querySelector('.id_calificado1').value = `${btnmodal.dataset.id}`
        document.querySelector('.area1').value = `${btnmodal.dataset.areaid}`
      }

      if (e.target.classList.contains('calificar-imagen-js')) {
        btnmodal = e.target.parentNode.parentNode

        document.querySelector('.modal-title').textContent = `${btnmodal.dataset.nombre}`
        document.querySelector('.id_calificado').value = `${btnmodal.dataset.id}`
        document.querySelector('.area').value = `${btnmodal.dataset.areaid}`

        document.querySelector('.modal-title1').textContent = `${btnmodal.dataset.nombre}`
        document.querySelector('.id_calificado1').value = `${btnmodal.dataset.id}`
        document.querySelector('.area1').value = `${btnmodal.dataset.areaid}`
      }



    })



    const contenedor = document.querySelector('.contenedor');
    contenedor.addEventListener('click', (e) => {
      e.target.nextSibling.nextSibling.textContent = e.target.value
    })


    const contenedor1 = document.querySelector('.contenedor1');
    contenedor1.addEventListener('click', (e) => {
      e.target.nextSibling.nextSibling.textContent = e.target.value
    })

    const contenedor2 = document.querySelector('.contenedor2');
    contenedor2.addEventListener('click', (e) => {
      e.target.nextSibling.nextSibling.textContent = e.target.value
    })

    function auto() {
      alert('Este mes ya te Autoevaluastes')
    }
  </script>
</body>

</html>