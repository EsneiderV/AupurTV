<?php
include_once '../controllers/php/funciones.php';
include_once '../models/Conexion.php';
session_start();
if (!isset($_SESSION['rol'])) {
  echo '<script type="text/javascript">
        window.location.href="usuario.php";
        </script>';
}

if (isset($_POST['calificar'])) {
  $idCalificante = $_SESSION['id'];
  $idCalificador = $_POST['id']; 
  $area = $_POST['area'];
  $nota = $_POST['valor'];
  $mes = date('m');
  $preguntas = mostrarPreguntasid($conexion);
  foreach ($nota as $key => $value) {
    guardarCalificaciones($preguntas[$key],$idCalificante,$idCalificador,$value,$mes,$area,$conexion);
  }
  echo '<script type="text/javascript">
        alert("Enviado correctamente");
        window.location.href="calificar.php";
        </script>';

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../controllers/bootstrap/bootstrap.min.css">
  <script src="../controllers/bootstrap/bootstrap.min.js"></script>
  <title>Calificar</title>
</head>

<body>
  <div class="usuarios_calificar">
    <h1>CALIFICAR</h1>

    <?php
    $mes = date('m');
    $usuarios = mostrarUsuario($conexion,$_SESSION['id']);
    while ($usuario = mysqli_fetch_array($usuarios)) {

     $calificado = empleadoCalificado($mes,$_SESSION['id'],$usuario['id'],$conexion);
      if($calificado->num_rows > 0){
        ?>
<button class=" btnmodal" data-bs-toggle="modal" data-bs-target="#exampleModal" data-nombre="<?php echo $usuario['nombre'] ?>" 
      data-area="<?php echo $usuario['ambiente'] ?>"
      data-id="<?php echo $usuario['id']?>"
      disabled>Foto <?php echo $usuario['nombre'] . ' - ' . $usuario['ambiente'] ?> </button> <br> <br>
        <?php 
      }else{

        ?>
        <button class=" btnmodal" data-bs-toggle="modal" data-bs-target="#exampleModal" data-nombre="<?php echo $usuario['nombre'] ?>" 
      data-area="<?php echo $usuario['ambiente'] ?>"
      data-id="<?php echo $usuario['id']?>"
      data-areaid="<?php echo $usuario['area']?>"
      >Foto <?php echo $usuario['nombre'] . ' - ' . $usuario['ambiente'] ?> </button> <br> <br>
        <?php
      }
    ?>

      

    <?php
    }


    ?>
  </div>



  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="contenedor">
              <?php
              $preguntas = mostrarPreguntas($conexion);
              while ($pregunta = mysqli_fetch_array($preguntas)) {
              ?>
                <div><?php echo $pregunta['pregunta'] ?> <input type="range" name="valor[]" min="1" max="10" id="input" step="1"> <span class="numero">6</span></div>
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




  <script>
// modal
const usuarios_calificar = document.querySelector('.usuarios_calificar');
    usuarios_calificar.addEventListener('click', e => {
      if (e.target.classList.contains('btnmodal')) {
        const btnmodal = e.target;
        document.querySelector('.modal-title').textContent = `${btnmodal.dataset.nombre}   ${ btnmodal.dataset.area}`
        document.querySelector('.id_calificado').value = `${btnmodal.dataset.id}`
        document.querySelector('.area').value = `${btnmodal.dataset.areaid}`

      }
    })

    // poner calificacion
    const contenedor = document.querySelector('.contenedor');
    contenedor.addEventListener('click', (e) => {
      e.target.nextSibling.nextSibling.textContent = e.target.value
    })
  </script>
</body>

</html>