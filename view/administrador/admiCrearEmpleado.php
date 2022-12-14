<?php 
if(isset($_POST['crear'])){

    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $rol = $_POST['rol'];
    $area = $_POST['area'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $clave = password_hash($dni, PASSWORD_DEFAULT);

    $tipo = $_FILES['imagen']['type'];
    $nombrei = $_FILES['imagen']['name'];
    $tamano = $_FILES['imagen']['size'];
    $imagenSubida = fopen($_FILES['imagen']['tmp_name'],'r');
    $binariosImagen = fread($imagenSubida,$tamano);
    $binariosImagen =mysqli_escape_string($conexion,$binariosImagen);


    try {
        AdInsertarUsuarios($dni,$nombre,$apellidos, $clave, $rol, $area, $correo, $telefono, $binariosImagen, $tipo, $conexion);
        echo '<script type="text/javascript">
            alert("Usuario creado correctamente");
            window.location.href="administrador.php?option=2";
            </script>';

    } catch (\Throwable $th) {
        echo $th;
    }


    
}


?>

<div class="admiCrearEmpleado-contenedor-principal">
    <h1 class="titulo-admiEmpleados">Crear usuario</h1>

    <!-- Agregar usuario -->
    <form class="admiCrearEmpleado-formulario-crearUsuario" action="administrador.php?option=2" id="form_insert" method="POST" enctype="multipart/form-data">
        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="dni">DNI : </label>
            <input class="admiCrearEmpleado-input" id="dni" type="number" name="dni" placeholder="Documento de Identidad" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="nombre">Nombre : </label>
            <input class="admiCrearEmpleado-input" id="nombre" type="text" name="nombre" placeholder="Nombres" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="apellido">Apellidos : </label>
            <input class="admiCrearEmpleado-input" id="apellidos" type="text" name="apellidos" placeholder="Apellidos" required>
        </div>


        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="correo">Correo : </label>
            <input class="admiCrearEmpleado-input" id="correo" type="text" name="correo" placeholder="Correo Electronico" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="telefono">Teléfono : </label>
            <input class="admiCrearEmpleado-input" id="telefono" type="text" name="telefono" placeholder="Teléfono" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" for="">Rol : </label>
            <select class="admiCrearEmpleado-input" id="rol" name="rol" required>
                <option class="" value="1">Empleado</option>
                <option class="" value="2">Director</option>
                <option class="" value="3">Gerente</option>
            </select>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" for="">Área : </label>
            <select class="admiCrearEmpleado-input" id="area" name="area" required>
                <option class="" value="1">Administración</option>
                <option class="" value="2">Tecnico</option>
                <option class="" value="3">Canal</option>
            </select>
        </div>

        <div class="admiCrearEmpleado-cargar-imagen">
        <input  type="file" name="imagen" required /><br>
        </div>

        <button class="admiCrearEmpleado-btn-crear" type="submit" name="crear" id="btnCrearUsuario">Crear Usuario</button>
    </form>




</div>