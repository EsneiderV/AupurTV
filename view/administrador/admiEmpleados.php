<table class="table table-striped admiEmpleado-contenedor-principal">
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
                        <button data-bs-toggle="modal" data-bs-target="#empleadosModificar" class="inventarioArea-boton-modificar modificar">
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

    <!-- Modal para el boton de modificar articulos -->
    <div class="modal fade" id="empleadosModificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modf-Art" id="exampleModalLabel"> Modificar Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="empleado-modal-body">
                    <form class="empleado-items-modificar" action="" method="POST">
                        <input autocomplete="off" id="DNI" value="" class="empleado-item-formulario-modf" type="text" name="DNI" placeholder="DNI"> <br>

                        <input autocomplete="off" id="nombre" class="empleado-item-formulario-modf" type="text" name="nombre" placeholder="Nombre"> <br>

                        <input autocomplete="off" id="apellido" value="" class="empleado-item-formulario-modf" type="text" name="apellidos" placeholder="Apellidos"> <br>

                        <input autocomplete="off" id="correo" class="empleado-item-formulario-modf" type="text" name="correo" placeholder="Correo"> <br>

                        <input autocomplete="off" id="telefono" value="" class="empleado-item-formulario-modf" type="text" name="telefono" placeholder="Teléfono"> <br>


                        <select name="rol" id="rol" class="empleado-item-formulario-modf">
                            <option value="" id="option-default-estado">Rol</option>
                            <option value="empleado">Empleado</option>
                            <option value="director">Director</option>
                            <option value="gerente">Gerente</option>
                        </select> <br>

                        <select name="area" id="area" class="empleado-item-formulario-modf">
                            <option value="" id="option-default-estado">Área</option>
                            <option value="Administracion">Administración</option>
                            <option value="tecnico">Tecnico</option>
                            <option value="canal">Canal</option>
                        </select> <br>
                    </form>
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
        const div = document.querySelectorAll('.inventarioAreaGerente-emergente-contenedor')

        div.forEach(cont => {
            cont.addEventListener('click', e => {
                if (e.target.classList.contains("modificar") || e.target.classList.contains("modificarL")) {
                    buttonComponents = e.target.classList.contains('etiqueta-i') ? e.target.parentNode : e.target;
                    const btn = (e.target.classList.contains("modificar")) ? e.target : e.target.parentNode;
                    document.querySelector('#codigoM').value = btn.dataset.cod
                    document.querySelector('#nombreM').value = btn.dataset.nombre
                    document.querySelector('#option-default-estado').value = btn.dataset.estado
                    document.querySelector('#option-default-estado').textContent = btn.dataset.estado
                    document.querySelector('#option-default-area').value = btn.dataset.areaid
                    document.querySelector('#option-default-area').textContent = btn.dataset.areanom
                    const url = 'get_empleados-modi.php?idEmpleado=' + btn.dataset.responsableid + '&nomEmpleado=' + btn.dataset.responsablenom;
                    $(document).ready(function() {
                        var empleados = $('#select-agregar-empleado-modi');
                        var id_area = $('#select-agregar-area-modi').val();
                        $.ajax({
                            data: {
                                id_area: id_area
                            },
                            dataType: 'html',
                            type: 'POST',
                            url: url,
                        }).done(function(data) {
                            empleados.html(data);
                        });

                    });

                }
            })
        })
    </script>

</table>