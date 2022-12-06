<div class="admiEmpleado-contenedor-principal">

    <table class="table table-striped">
        <thead>
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
                            <button data-bs-toggle="modal" data-bs-target="#articulosModificar" class="inventarioArea-boton-modificar modificar">
                                <i class="fa-solid fa-pen-to-square modificarL etiqueta-i"></i>
                            </button>
                        </span>

                        <span class="span-datos">
                            <a class="fa-solid fa-trash-can modificar" href=""></a>
                        </span>
                    </td>
                </tr>

            <?php

            }
            ?>
        </tbody>

    </table>
</div>