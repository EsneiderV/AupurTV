<?php 
include_once 'models/Conexion.php';
if(isset($_POST['submit'])){
  if (isset($_FILES['imagen']['name'])) {
    $tipo = $_FILES['imagen']['type'];
    $nombre = $_FILES['imagen']['name'];
    $tamano = $_FILES['imagen']['size'];
    $imagenSubida = fopen($_FILES['imagen']['tmp_name'],'r');
    $binariosImagen = fread($imagenSubida,$tamano);
    $binariosImagen =mysqli_escape_string($conexion,$binariosImagen);
    $query = "INSERT INTO imagenes (nombre,imagen,tipo,tamano) VALUES ('$nombre','$binariosImagen','$tipo','$tamano')";
     $res =  mysqli_query($conexion,$query);
     if($res){
      echo 'Bien';
    }else{
      echo mysqli_error($conexion);
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<body>
    <form action="" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="imagen"/><br>
        <input type="submit" name="submit" value="UPLOAD"/>
    </form>

    <div>
      <?php 
       $query = "SELECT nombre,imagen,tipo FROM imagenes ";
       $res =  mysqli_query($conexion,$query);
       while ($row = mysqli_fetch_assoc($res)) {
         echo $row['nombre']."<br>";
         echo $row['tipo']."<br>";
         ?>
         <img width="100" src="data:<?php echo $row['tipo'] ?>;base64,<?php echo base64_encode($row['imagen']) ?>" alt="">
         <?php
       }
      ?>
    </div>

</body>
</html>