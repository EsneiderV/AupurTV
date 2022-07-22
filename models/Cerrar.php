<?php 
session_start();
session_destroy(); // serramos la secion 
header("Location: ../index.php"); // redireccionamos al incio
?>   