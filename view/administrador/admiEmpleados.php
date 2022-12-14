<?php 
if(isset($_POST['modificar'])){
  $id = $_POST['id'];
  $DNI = $_POST['DNI'];
  $nombre = $_POST['nombre'];
  $apellidos = $_POST['apellidos'];
  $correo = $_POST['correo'];
  $telefono = $_POST['telefono'];
  $rol = $_POST['rol'];
  $area = $_POST['area'];

  $tipo = $_FILES['imagen']['type'];


  if($tipo == ""){
    AdModificarUsuarios($id,$DNI, $nombre,$apellidos,$correo,$telefono, $rol, $area, $conexion);
    echo '<script type="text/javascript">
    alert("Usuario modificado correctamente");
    window.location.href="administrador.php";
    </script>';
  }else{
    $nombrei = $_FILES['imagen']['name'];
    $tamano = $_FILES['imagen']['size'];
    $imagenSubida = fopen($_FILES['imagen']['tmp_name'],'r');
    $binariosImagen = fread($imagenSubida,$tamano);
    $binariosImagen =mysqli_escape_string($conexion,$binariosImagen);
    AdModificarUsuariosImagen($id,$DNI, $nombre,$apellidos,$correo,$telefono, $rol, $area,$binariosImagen,$tipo, $conexion);
    echo '<script type="text/javascript">
    alert("Usuario modificado correctamente");
    window.location.href="administrador.php";
    </script>';
  }

}

if(isset($_GET['idremove'])){
  $id = $_GET['idremove'];
  EliminarUsuario($id,$conexion);
  echo '<script type="text/javascript">
  alert("Usuario eliminado correctamente");
  window.location.href="administrador.php";
  </script>';
}
?>

<table class="table table-striped admiEmpleado-contenedor-principal" id="contenedor-datos">
  <thead class="admiEmpleado-contenedor-titulo-datos">
    <tr>
      <th class="span-datos">DNI</th>
      <th class="span-datos">NOMBRES</th>
      <th class="span-datos">APELLIDOS</th>
      <th class="span-datos">CORREO</th>
      <th class="span-datos">TELÉFONO</th>
      <th class="span-datos">ROL</th>
      <th class="span-datos">ÁREA</th>
      <th class="span-datos">ACCIÓN</th>
    </tr>
  </thead>

  <tbody>
    <?php
    $usuarios = usuario($conexion);
    // mostramos todos los datos de los usuarios
    while ($usuario = mysqli_fetch_array($usuarios)) {
    ?>
      <tr>
        <td>
          <span class="span-datos">
            <?php echo $usuario['DNI'] ?>
          </span>
        </td>

        <td>
          <span class="span-datos">
            <?php echo $usuario['nombre'] ?>
          </span>
        </td>

        <td>
          <span class="span-datos">
            <?php echo $usuario['apellidos'] ?>
          </span>
        </td>

        <td>
          <span class="span-datos">
            <?php echo $usuario['correo'] ?>
          </span>
        </td>

        <td>
          <span class="span-datos">
            <?php echo $usuario['telefono'] ?>
          </span>
        </td>

        <td>
          <span class="span-datos">
            <?php echo $usuario['rol'] ?>
          </span>
        </td>

        <td> <span class="span-datos">
            <?php echo $usuario['area'] ?>
          </span>
        </td>

        <td>
          <span class="span-datos">
            <button data-dni="<?php echo $usuario['DNI'] ?>" data-nombre="<?php echo $usuario['nombre'] ?>" data-apellidos="<?php echo $usuario['apellidos'] ?>" data-correo="<?php echo $usuario['correo'] ?>" data-telefono="<?php echo $usuario['telefono'] ?>" data-idrol="<?php echo $usuario['idRol'] ?>" data-idarea="<?php echo $usuario['idArea'] ?>" data-rol="<?php echo $usuario['rol'] ?>" data-area="<?php echo $usuario['area'] ?>" data-id="<?php echo $usuario['id'] ?>" data-bs-toggle="modal" data-bs-target="#empleadosModificar" class="inventarioArea-boton-modificar modificar btn-modificar-admin-empleados">
              <i class="fa-solid fa-pen-to-square modificarL etiqueta-i i-modificar-admin-empleados"></i>
            </button>
          </span>

          <span class="span-datos">
              <a onclick="return eliminar()" class="fa-solid fa-trash-can modificar" href="administrador.php?option=1&idremove=<?php echo $usuario['id'] ?>"></a>
          </span>
        </td>
      </tr>

    <?php

    }
    ?>
  </tbody>

  <!-- Modal para el boton de modificar articulos -->
  <div class="modal fade" id="empleadosModificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title modf-Art" id="exampleModalLabel"> Modificar Empleado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="empleado-modal-body">
          <form class="empleado-items-modificar" action="administrador.php" method="POST" enctype="multipart/form-data">
            <input hidden autocomplete="off" id="id" value="" class="empleado-item-formulario-modf" type="text" name="id" placeholder="DNI"> <br>

            <input autocomplete="off" id="DNI" value="" class="empleado-item-formulario-modf" type="text" name="DNI" placeholder="DNI"> <br>

            <input autocomplete="off" id="nombre" class="empleado-item-formulario-modf" type="text" name="nombre" placeholder="Nombre"> <br>

            <input autocomplete="off" id="apellido" value="" class="empleado-item-formulario-modf" type="text" name="apellidos" placeholder="Apellidos"> <br>

            <input autocomplete="off" id="correo" class="empleado-item-formulario-modf" type="text" name="correo" placeholder="Correo"> <br>

            <input autocomplete="off" id="telefono" value="" class="empleado-item-formulario-modf" type="text" name="telefono" placeholder="Teléfono"> <br>


            <select name="rol" id="rol" class="empleado-item-formulario-modf">
              <option value="" id="option-default-rol"></option>
              <option value="1">Empleado</option>
              <option value="2">Director</option>
              <option value="3">Gerente</option>
            </select> <br>

            <select name="area" id="area" class="empleado-item-formulario-modf">
              <option value="" id="option-default-area"></option>
              <option value="1">Administración</option>
              <option value="2">Tecnico</option>
              <option value="3">Canal</option>
            </select> <br>

            <div class="admiCrearEmpleado-cargar-imagen">
              <input class="modificar-imagen" type="file" name="imagen"/><br>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="modificar" class="btn btn-primary">Modificar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    //MODIFICAR EMPLEADO

    //Validación para eliminar empleado
    function eliminar() {
      let respuesta = confirm('¿Está seguro de que desea eliminar este articulo de forma permanente?');

      if (respuesta) {
        return true
      } else {
        return false
      }
    }

    // Modificar datos del empleado
    const div = document.querySelector('#contenedor-datos')

    div.addEventListener('click', e => {

      if (e.target.classList.contains("btn-modificar-admin-empleados") || e.target.classList.contains("i-modificar-admin-empleados")) {
        const btn = (e.target.classList.contains("btn-modificar-admin-empleados")) ? e.target : e.target.parentNode;
        document.querySelector('#id').value = btn.dataset.id
        document.querySelector('#DNI').value = btn.dataset.dni
        document.querySelector('#nombre').value = btn.dataset.nombre
        document.querySelector('#apellido').value = btn.dataset.apellidos
        document.querySelector('#correo').value = btn.dataset.correo
        document.querySelector('#telefono').value = btn.dataset.telefono
        document.querySelector('#option-default-rol').value = btn.dataset.idrol
        document.querySelector('#option-default-rol').textContent = btn.dataset.rol
        document.querySelector('#option-default-area').value = btn.dataset.idarea
        document.querySelector('#option-default-area').textContent = btn.dataset.area

      }
    })
  </script>

</table>