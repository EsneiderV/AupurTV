<?php 

    $conexion = mysqli_connect("localhost","root","","aupurtv");
    if (mysqli_connect_errno()) {
        throw new RuntimeException("mysqli connection error".mysqli_connect_error());
        
    }else{

        // echo "Se realizo la conexion con la base de datos";

    }

?>
