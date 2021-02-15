<?php
session_start();
include "../../assets/php/functions.php";
if(!isset($_SESSION['nombre']) or $_SESSION['tipo']=="alumno"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar curso</title>
    <?php 
    import_tipo();
    import_css();
    import_js_bootstrap(); 
    ?>
    <script src="../../assets/js/app.js"></script>
    <script src='../../assets/js/bootstrap.min.js'></script>
</head>
<body>

<?php
    if(!isset($_GET['id'])){
        echo "<meta http-equiv='refresh' content='0; url=cursos.php'>";
    }else{
        if($_SESSION['tipo']=="administrador"){
            menu_administrador();
       }else{
           menu_profesor();
       }
        $conexion = conectarBD();

        //Seleccionamos los datos del curso en cuestión
        $consulta = $conexion -> prepare("SELECT * FROM curso WHERE id=?");

        $id=$_GET['id'];

        $consulta -> bindParam(1, $id);
        $consulta -> setFetchMode(PDO::FETCH_ASSOC);
        $consulta -> execute();

        $datos= $consulta -> fetch();
      
?>
<div class="container">
    <div class="row">
    <form class="col-6" action="#" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nombre">Nombre del curso</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $datos['nombre'];?>">
    </div>
    <div class="form-group">
        <label for="profesor">Profesor</label>
        <select class="form-control" id="profesor" name="profesor">
            
<?php

    //Sacar los nombres de los profesores para colocarlos en un select.
       
        
        $consulta_profesor = $conexion -> prepare("SELECT nif_profesor, nombre, apellido1, apellido2 FROM profesor");
        $consulta_profesor -> setFetchMode(PDO::FETCH_ASSOC);
        $consulta_profesor -> execute();
        
        while($profesor = $consulta_profesor -> fetch()){
            if($profesor['nif_profesor']==$datos['nif_profesor']){
                echo"<option value='$profesor[nif_profesor]' selected> $profesor[nombre] $profesor[apellido1] $profesor[apellido2] </option>";
            }else{
                echo"<option value=$profesor[nif_profesor]'> $profesor[nombre] $profesor[apellido1] $profesor[apellido2]</option>";
            }
        }
?>
        </select>
    </div>

    <div class="form-group">
        <label for="desc_corta">Descripción corta</label>
        <textarea class="form-control" id="desc_corta" name="desc_corta" rows="3"><?php echo $datos['desc_corta'];?></textarea>
    </div>

    <div class="form-group">
        <label for="desc_larga">Descripción larga</label>
        <textarea class="form-control" id="desc_larga" name="desc_larga" rows="15"><?php echo $datos['desc_larga'];?></textarea>
    </div>       
    
    <label for="imagen">Seleccionar imagen</label>
    <div class="custom-file">
        <input class="custom-file label" type="file" name="imagen" id="imagen" lang="es">
        <label class="custom-file-label" for="imagen">Seleccionar imagen</label>
    </div> 
                
   
    <label for="thumbnail">Seleccionar imagen de vista previa</label>
    <div class="custom-file">
        <input class="custom-file label" type="file" name="thumbnail" id="thumbnail" lang="es">
        <label class="custom-file-label" for="thumbnail">Seleccionar imagen de vista previa</label>
    </div>    

    <div class="form-group">
        <label  for="seo_imagen">SEO Imagen Text</label>
        <input class="form-control" type="text" value="<?php echo $datos['seo_imagen'];?>" name="seo_imagen" id="seo_imagen" maxlenth="20">    
    </div>
    
    <div class="form-check">
    <?php
        if($datos['visible']==1){
            echo "<input class='form-check-input' type='checkbox' id='visible' name='visible' checked>";
        }else{ 
            echo "<input class='form-check-input' type='checkbox' id='visible' name='visible'>";
        }
    ?>
        <label class="form-check-label" for="visible">¿Visible?</label>                    
    </div>

    <div class="form-group">
        <label for="num_maximo_alumnos">Nº máximo alumnos</label>
        <input type="number" class="form-control" id="num_maximo_alumnos" name="num_maximo_alumnos" value="<?php echo $datos['max_alumnos'];?>" maxlenth="2">
    </div>

    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="number" class="form-control" step="any" id="precio" name="precio" value="<?php echo $datos['precio'];?>" maxlenth="2">
    </div>

    <button type="submit" class="btn btn-primary p-5 my-5" name="actualizar">Actualizar</button>
    </form>
    </div>
</div>

<?php
    }//FIN IF ELSE de $_GET['id'] 
    
    
    


    if(isset($_POST['actualizar'])){   
        
        $consulta_update = $conexion -> prepare ("UPDATE curso SET nombre=?, nif_profesor=?, desc_corta=?, desc_larga=?, max_alumnos=?, precio=?, imagen=?, thumbnail=?, seo_imagen=?, visible=? WHERE id=?");
        
        $nombre         = $_POST['nombre'];
        $profesor       = $_POST['profesor'];
        $desc_corta     = $_POST['desc_corta'];
        $desc_larga     = $_POST['desc_larga'];
        $precio         = $_POST['precio'];
        $imagen         = $datos['imagen'];
        $thumbnail      = $datos['thumbnail'];        
        $seoimagen      = $_POST['seo_imagen'];
        $alumnos        = $_POST['num_maximo_alumnos'];
        $visible        = 0;
        
        if(isset($_POST['visible'])){
            $visible=1;
        }
        
        $id=$_GET['id'];

        
        if($_FILES['imagen']['tmp_name'] != ""){            
            
            //Comprobamos que existe la carpeta imágenes
            if(!file_exists("../../assets/img/cursos/imagen")){
                mkdir("../../assets/img/cursos/imagen");
            }

            //Copiar la imagen a nuestro servidor
            $nombre_tmp_imagen=$_FILES['imagen']['tmp_name'];

            $extension_imagen=extension_imagen($_FILES['imagen']['type']);

            //copiamos la imagen con name "imagen"
            $nombre_imagen="imagen_curso_$id".$extension_imagen;
            move_uploaded_file($nombre_tmp_imagen,"../../assets/img/cursos/imagen/$nombre_imagen");

            $imagen=$nombre_imagen;
        }

        if($_FILES['thumbnail']['tmp_name'] != ""){            

            if(!file_exists("../../assets/img/cursos/thumbnail")){
                mkdir("../../assets/img/cursos/thumbnail");
            }
    
            //Copiar la imagen a nuestro servidor
    
            $nombre_tmp_thumbnail=$_FILES['thumbnail']['tmp_name'];
    
            $extension_thumbnail=extension_imagen($_FILES['thumbnail']['type']);   
             
            $nombre_thumbnail="thumbnail_curso_$id".$extension_thumbnail;
            move_uploaded_file($nombre_tmp_thumbnail,"../../assets/img/cursos/thumbnail/$nombre_thumbnail");
    
            $nombre_tmp_thumbnail=$_FILES['thumbnail']['tmp_name'];
    
            $extension_thumbnail=extension_imagen($_FILES['thumbnail']['type']);
    
            $nombre_thumbnail="thumbnail_curso_$id".$extension_thumbnail;            
            move_uploaded_file($nombre_tmp_thumbnail,"../../assets/img/cursos/thumbnail/$nombre_thumbnail");

            $thumbnail=$nombre_thumbnail;
        }

        $consulta_update -> bindParam(1, $nombre);
        $consulta_update -> bindParam(2, $profesor);
        $consulta_update -> bindParam(3, $desc_corta);
        $consulta_update -> bindParam(4, $desc_larga);
        $consulta_update -> bindParam(5, $alumnos);
        $consulta_update -> bindParam(6, $precio);        
        $consulta_update -> bindParam(7, $imagen);
        $consulta_update -> bindParam(8, $thumbnail);
        $consulta_update -> bindParam(9, $seoimagen);
        $consulta_update -> bindParam(10, $visible);
        $consulta_update -> bindParam(11, $id);

        $consulta_update -> execute();

        echo "<meta http-equiv='refresh' content='0; url=cursos.php'>";
    }
     
    $conexion=null;
    import_js_bootstrap();

?>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<?php    
}
?>
