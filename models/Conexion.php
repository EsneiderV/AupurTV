<?php 

    $conexion = mysqli_connect("localhost","root","","aupurtv"); // asemos la conecion con la base de datos
    if (mysqli_connect_errno()) { // verificamos que no tenga ningun error
        throw new RuntimeException("mysqli connection error".mysqli_connect_error());
        
    }else{

        // echo "Se realizo la conexion con la base de datos";

    }

?>
