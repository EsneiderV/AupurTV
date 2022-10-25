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
    $query = "UPDATE `usuarios` SET `imagen`='$binariosImagen',`tipo_imagen`='$tipo'";
     $res =  mysqli_query($conexion,$query);
     if($res){
      echo 'Bien';
    }else{
      echo mysqli_error($conexion);
    }
  }
}
//Holalaaa
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
       $query = "SELECT imagen,tipo_imagen FROM usuarios ";
       $res =  mysqli_query($conexion,$query);
       while ($row = mysqli_fetch_assoc($res)) {
         ?>
         <img width="100" src="data:<?php echo $row['tipo_imagen'] ?>;base64,<?php echo base64_encode($row['imagen']) ?>" alt="">
         <?php
       }
      ?>
    </div>
    
    <style>
      body{
        background: rgb(2,0,36);
        background: linear-gradient(90deg, rgba(2,0,36,0.9416141456582633) 0%, rgba(57,171,87,0.865983893557423) 56%, rgba(0,111,183,1) 100%);
      }
    </style>
</body>
</html>



              