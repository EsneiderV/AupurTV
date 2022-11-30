<div class="admiCrearEmpleado-contenedor-principal">
    <h1 class="titulo-admiEmpleados">Crear usuario</h1>

    <!-- Agregar usuario -->
    <form class="admiCrearEmpleado-formulario-crearUsuario" action="" method="post">
        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="dni">DNI : </label>
            <input class="admiCrearEmpleado-input" id="dni" type="text" name="dni" placeholder="Documento de Identidad" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="nombre">Nombre : </label>
            <input class="admiCrearEmpleado-input" id="nombre" type="text" name="nombre" placeholder="Nombres" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="apellido">Apellidos : </label>
            <input class="admiCrearEmpleado-input" id="apellido" type="text" name="apellido" placeholder="Apellidos" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="contraseña">Contraseña : </label>
            <input class="admiCrearEmpleado-input" id="contraseña" type="text" name="contraseña" placeholder="Contraseña" required>
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
            <select class="admiCrearEmpleado-input" id="" name="" required>
                <option class="" value="empleado">Empleado</option>
                <option class="" value="director">Director</option>
                <option class="" value="gerente">Gerente</option>
            </select>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" for="">Área : </label>
            <select class="admiCrearEmpleado-input" id="" name="" required>
                <option class="" value="administracion">Administración</option>
                <option class="" value="tecnico">Tecnico</option>
                <option class="" value="canal">Canal</option>
            </select>
        </div>

        <div class="admiCrearEmpleado-items">
            <form action="" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="imagen" /><br>
                <input type="submit" name="submit" value="UPLOAD" />
            </form>

            <div>
                <?php
                $query = "SELECT imagen,tipo_imagen FROM usuarios ";
                $res =  mysqli_query($conexion, $query);
                while ($row = mysqli_fetch_assoc($res)) {
 
                }
                ?>
            </div>

            <style>
                body {
                    background: rgb(2, 0, 36);
                    background: linear-gradient(90deg, rgba(2, 0, 36, 0.9416141456582633) 0%, rgba(57, 171, 87, 0.865983893557423) 56%, rgba(0, 111, 183, 1) 100%);
                }
            </style>
        </div>

        <button class="admiCrearEmpleado-btn-crear" type="submit" name="crear">Crear Usuario</button>
    </form>

    <!-- Script para insertar usuarios -->
    <script src="../../controllers/js/jquery-1.10.2.min.js"></script>

    <script> </script>


</div>