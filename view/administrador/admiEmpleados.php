<div class="admiEmpleado-contenedor-proncipal">

    <div class="admiEmpleado-contenedor">
        <h1 class="titulo-admiEmpleados">Empleados AupurTV</h1>

        <div class="admiEmpleados-div-datos-titulo">
            <p class="admiEmpleados-p-datos-titulo">
                <span class="span-datos">DNI</span>
                <span class="span-datos">NOMBRES</span>
                <span class="span-datos">APELLIDOS</span>
                <span class="span-datos">CORREO</span>
                <span class="span-datos">TELÉFONO</span>
                <span class="span-datos">ROL</span>
                <span class="span-datos">ÁREA</span>
                <span class="span-datos">ACCIÓN</span>
            </p>
        </div>
    </div>

    <?php
    $usuarios = usuario($conexion);

    // mostramos todos los datos de los usuarios
    while ($usuario = mysqli_fetch_array($usuarios)) {
    ?>
        <div class="admiEmpleados-div-datos">
            <p class="admiEmpleados-p-datos">
                <span class="span-datos">
                    <?php echo $usuario['DNI'] ?>
                </span>

                <span class="span-datos">
                    <?php echo $usuario['nombre'] ?>
                </span>

                <span class="span-datos">
                    <?php echo $usuario['apellidos'] ?>
                </span>

                <span class="span-datos">
                    <?php echo $usuario['correo'] ?>
                </span>

                <span class="span-datos">
                    <?php echo $usuario['telefono'] ?>
                </span>

                <span class="span-datos">
                    <?php echo $usuario['rol'] ?>
                </span>

                <span class="span-datos">
                    <?php echo $usuario['area'] ?>
                </span>

                <span class="span-datos">
                    <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar">
                        <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                    </button>
                </span>

                <span class="span-datos">
                    <a class="fa-solid fa-trash-can modificar" href=""></a>
                </span>
            </p>
        </div>
    <?php

    }
    ?>
</div>