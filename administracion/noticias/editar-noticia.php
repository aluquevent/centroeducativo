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
        echo "<meta http-equiv='refresh' content='0; url=noticias.php'>";
    }else{
        if($_SESSION['tipo']=="administrador"){
            menu_administrador();
       }else{
           menu_profesor();
       }
        $conexion = conectarBD();
        //Seleccionamos los datos del curso en cuestión
        $consulta = $conexion -> prepare("SELECT * FROM noticia WHERE id=?");

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
        <label for="titulo">Título</label>
        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $datos['titulo'];?>">
    </div>    

    <div class="form-group">
        <label for="texto">Texto</label>
        <textarea class="form-control" id="texto" name="texto" rows="3"><?php echo $datos['texto'];?></textarea>
    </div>

    <div class="form-group">
        <label for=fecha">Fecha</label>
        <input type="date" class="form-control" id=fecha" name=fecha" value="<?php echo $datos['fecha'];?>">
    </div>

    <div class="form-group">
        <label for=hora_i">Hora inicio</label>
        <input type="time" class="form-control" id=hora_i" name=hora_i" value="<?php echo $datos['hora_inicio'];?>">
    </div>

    <div class="form-group">
        <label for=hora_f">Hora fin</label>
        <input type="time" class="form-control" id=hora_f" name=hora_f" value="<?php echo $datos['hora_fin'];?>">
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

    <button type="submit" class="btn btn-primary p-5 my-5" name="actualizar">Actualizar</button>
    </form>
    </div>
</div>

<?php
    }//FIN IF ELSE de $_GET['id']

    if(isset($_POST['actualizar'])){   
        
        $consulta_update = $conexion -> prepare ("UPDATE noticia SET titulo=?, texto=?, fecha=?, hora_inicio=?, hora_fin=?, seo_imagen=?, thumbnail=?,  visible=? WHERE id=?");
        
        $titulo      = $_POST['titulo'];
        $texto       = $_POST['texto'];
        $fecha       = $_POST['fecha'];
        $hora_i      = $_POST['hora_i'];
        $hora_f      = $_POST['hora_f'];
        $thumbnail   = $datos['thumbnail'];   
        $seoimagen   = $_POST['seo_imagen'];
        $visible     = 0;
        
        if(isset($_POST['visible'])){
            $visible=1; 
        }
        
        $id=$_GET['id'];

        
        if($_FILES['thumbnail']['tmp_name'] != ""){            
            
            //Comprobamos que existe la carpeta imágenes
            if(!file_exists("../../assets/img/noticias/thumbnail")){
                mkdir("../../assets/img/noticias/thumbnail");
            }

            //Copiar la imagen a nuestro servidor
            $nombre_tmp_thumbnail=$_FILES['thumbnail']['tmp_name'];

            $extension_thumbnail=extension_imagen($_FILES['thumbnail']['type']);

            //copiamos la imagen con name "imagen"
            $nombre_thumbnail="thumbnail_curso_$id".$extension_thumbnail;
            move_uploaded_file($nombre_tmp_thumbnail,"../../assets/img/noticias/thumbnail/$nombre_thumbnail");

            $thumbnail=$nombre_thumbnail;
        }

        $consulta_update -> bindParam(1, $titulo);
        $consulta_update -> bindParam(2, $texto);
        $consulta_update -> bindParam(3, $fecha);
        $consulta_update -> bindParam(4, $hora_i);
        $consulta_update -> bindParam(5, $hora_f);
        $consulta_update -> bindParam(6, $seoimagen);
        $consulta_update -> bindParam(7, $thumbnail);
        $consulta_update -> bindParam(8, $visible);
        $consulta_update -> bindParam(9, $id);

        $consulta_update -> execute();

        echo "<meta http-equiv='refresh' content='0; url=noticias.php'>";
    }
     
    $conexion=null;
?>
</body>
<?php
import_js_bootstrap();
?>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</html>
<?php
}
?>