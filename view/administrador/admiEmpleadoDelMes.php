<?php
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
?>

<div class="admiEmpleadoDelMes-contenedor-principal">
    <div class="admiEmpleadoDelMes-contenedor-titulos">
        <h1 class="titulo-empleado-mes">Calificaciones empleados</h1>

        <h3 class="titulo-empleado-mes">
            <?php
            if (isset($_POST['aniodiagrama'])) {
                $anio = $_POST['aniodiagrama'];
                echo "año " . $anio;
            } else {
                echo "año " . $anio;
            }
            ?>
        </h3>
    </div>

    <div class="div-seleccionar-año">
        <form id="formularioCambiarAnio" action="" method="post">
            <select class="select-año" id="SelectCambiarAnio" name="aniodiagrama" id="">
                <option value="0">Seleccione año</option>
                <?php
                $consultaSelects = traerAniosQueTiene($conexion);
                while ($consultaSelect = mysqli_fetch_array($consultaSelects)) {
                ?>
                    <option value="<?php echo $consultaSelect['anio'] ?>"><?php echo $consultaSelect['anio'] ?></option>
                <?php
                }
                ?>
            </select>
        </form>
    </div>

    <div class="admiEmpleadoDelMes-contenedor-empleados">
        <div class="div-empleados-por-mes">
            <h2 class="titulo-empleado-por-mes">Empleados por mes</h2>
            <div class="div-contenedor-foto-mes-persona">
                <?php
                $personas = mostarInventarioAreaPersona($_SESSION['area'], $conexion);
                while ($persona = mysqli_fetch_array($personas)) {
                ?>
                    <div class="div-foto-mes-persona">
                        <div class="div-mes">
                            <div class="">Enero :</div>
                        </div>

                        <div class="div-foto">
                            <img class="inventarioArea-img  rounded-circle " src="data:<?php echo $persona['tipo_imagen'] ?>;base64,<?php echo base64_encode($persona['imagen']) ?>" alt="foto de perfil">
                        </div>

                        <div class="div-persona">
                            <h4 class="inventarioArea-nombre">
                                <?php
                                $apellido = explode(' ', $persona['apellidos']);
                                $nombreCompleto = $persona['nombre'] . ' ' . $apellido[0];
                                $nombreCompleto = ucwords($nombreCompleto);

                                echo $nombreCompleto;
                                ?>
                            </h4>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>

        <div class="div-empleados-por-mes">
            <h2 class="titulo-empleado-por-mes">Calificaciones mensuales</h2>

            <div class="div-mes-año">
                <div class="div-mesesDelAño"><a href='../../pdf/pdf-empleadoMes.php?anio=2022&mes=12' class="mesesDelAño">Enero</a></div>
                <div class="div-mesesDelAño"><a href="" value="2" class="mesesDelAño">Febrero</a></div>
                <div class="div-mesesDelAño"><a href="" value="3" class="mesesDelAño">Marzo</a></div>
                <div class="div-mesesDelAño"><a href="" value="4" class="mesesDelAño">Abril</a></div>
                <div class="div-mesesDelAño"><a href="" value="5" class="mesesDelAño">Mayo</a></div>
                <div class="div-mesesDelAño"><a href="" value="6" class="mesesDelAño">Junio</a></div>
                <div class="div-mesesDelAño"><a href="" value="7" class="mesesDelAño">Julio</a></div>
                <div class="div-mesesDelAño"><a href="" value="8" class="mesesDelAño">Agosto</a></div>
                <div class="div-mesesDelAño"><a href="" value="9" class="mesesDelAño">Septiembre</a></div>
                <div class="div-mesesDelAño"><a href="" value="10" class="mesesDelAño">Octubre</a></div>
                <div class="div-mesesDelAño"><a href="" value="11" class="mesesDelAño">Noviembre</a></div>
                <div class="div-mesesDelAño"><a href="" value="12" class="mesesDelAño">Dicciembre</a></div>
            </div>
        </div>



    </div>
</div>