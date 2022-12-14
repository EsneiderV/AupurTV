<?php
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');
?>

<div class="admiEmpleadoDelMes-contenedor-principal">
    <div class="admiEmpleadoDelMes-contenedor-titulos">
        <h1 class="titulo-empleado-mes">Empleado del mes</h1>

        <h3 class="titulo-año-actual">
            <?php
            if (isset($_POST['aniodiagrama'])) {
                $anio = $_POST['aniodiagrama'];
                echo "Año " . $anio;
            } else {
                echo "Año " . $anio;
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

            <div class="div-mes-persona">
                <div class="div-mesesDelAño">Enero :</div>
                <div>Pepito de Jesus Perez Perez</div>
            </div>
        </div>

        <div class="div-empleados-por-mes">
            <h2 class="titulo-empleado-por-mes">Empleados por mes</h2>

            <div class="div-mes-persona">
                <div class="div-mesesDelAño">Enero :</div>
                <div>Pepito de Jesus Perez Perez</div>
            </div>
        </div>
    </div>
</div>