<?php
date_default_timezone_set('America/Bogota');
$mes = date('m');
$anio = date('Y');

if (isset($_POST['anioempleadomes']) && $_POST['anioempleadomes'] != 0) {
    $anio = $_POST['anioempleadomes'];
}

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
        <form id="formularioCambiarAnio" action="administrador.php?option=3" method="post">
            <select class="select-año" id="SelectCambiarAnio" name="anioempleadomes" id="">
                <option value="0">Seleccione año</option>
                <?php
                $consultaSelects = sacarTodosLosaniosEmpleadoMes($conexion);
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
                $mesesConsultados = sacarTodosLosMesesEmpleadoMes($anio, $conexion);
                while ($mesConsultados = mysqli_fetch_array($mesesConsultados)) {
                  $ganador = sacarEmpleadodelMes($mesConsultados['mes'],$anio,$conexion);
                ?>
                    <div class="div-foto-mes-persona">
                        <div class="div-mes">
                            <div class=""><?php echo retornarmesNumero($mesConsultados['mes'])?></div>
                        </div>

                        <div class="div-foto">
                            <img class="inventarioArea-img  rounded-circle " src="data:<?php echo $ganador['tipo_imagen'] ?>;base64,<?php echo base64_encode($ganador['imagen']) ?>" alt="foto de perfil">
                        </div>

                        <div class="div-persona">
                            <h4 class="inventarioArea-nombre">
                                <?php

                                $apellido = explode(' ', $ganador['apellidos']);
                                $nombreCompleto = $ganador['nombre'] . ' ' . $apellido[0];
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
                <?php
                $mesesConsultados = sacarTodosLosMesesEmpleadoMes($anio, $conexion);
                while ($mesConsultados = mysqli_fetch_array($mesesConsultados)) {
                ?>
                    <div class="div-mesesDelAño"><a href='../../pdf/pdf-empleadoMes.php?anio=<?php echo $anio?>&mes=<?php echo $mesConsultados['mes']?>' class="mesesDelAño"><?php echo retornarmesNumero($mesConsultados['mes'])?></a></div>
                <?php
                }
                ?>

            </div>
        </div>



    </div>
</div>

<script>
    const formParaLosAnios = document.querySelector('#formularioCambiarAnio')
    const selectCambiarAnio = document.querySelector('#SelectCambiarAnio')
    selectCambiarAnio.addEventListener('input', e => {
        formParaLosAnios.submit();
    })
</script>