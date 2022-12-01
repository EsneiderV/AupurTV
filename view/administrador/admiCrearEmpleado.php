<div class="admiCrearEmpleado-contenedor-principal">
    <h1 class="titulo-admiEmpleados">Crear usuario</h1>

    <!-- Agregar usuario -->
    <form class="admiCrearEmpleado-formulario-crearUsuario" action="" id="form_insert" method="post">
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
            <input class="admiCrearEmpleado-input" id="apellidos" type="text" name="apellidos" placeholder="Apellidos" required>
        </div>

        <div class="admiCrearEmpleado-items">
            <label class="admiCrearEmpleado-label" form="contraseña">Contraseña : </label>
            <input class="admiCrearEmpleado-input" id="clave" type="text" name="clave" placeholder="Contraseña" required>
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
        <input type="file" name="imagen"/><br>
        </div>

        <button class="admiCrearEmpleado-btn-crear" type="submit" name="crear" id="btnCrearUsuario">Crear Usuario</button>
    </form>

    <!-- Script para insertar usuarios -->
    <script src="../../controllers/js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#btnCrearUsuario').click(function() {
                var datos = $('#form_insert').serialize();
                $.ajax({
                    type: "POST",
                    url: "insertarUsuario.php",
                    data: datos,
                    success: function(r) {
                        if (r != 1) {
                            alert("Usuario agregado exitosamente");
                        } else {
                            alert("Fallo en el server");
                        }
                    }
                });
                return false;
            });
        });
    </script>


</div>