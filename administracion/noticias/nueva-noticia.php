<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre']) or $_SESSION['tipo']=="alumno"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva noticia</title>
    <?php import_tipo() ?>
    <?php import_css(); 
    import_js_bootstrap();?>
    <script src="../../assets/js/app.js"></script>
    <script src='../../assets/js/bootstrap.min.js'></script>
</head>
<body>
    <?php
        if($_SESSION['tipo']=="administrador"){
            menu_administrador();
       }else{
           menu_profesor();
       }
    $conexion = conectarBD();
    ?>
<div class="container">
    <div class="row">
        <h1 class="col-12">Crear noticia</h1>
        
        <form class="col-12" action="#" method="post" enctype="multipart/form-data" >
        
            <div class="form-group">
                <label for="titulo_noticia">Título de la noticia</label>
                <input type="text" class="form-control" id="titulo_noticia" name="titulo_noticia">
            </div>
            
            <div class="form-group">
                <label for="texto_noticia">Texto</label>
                <textarea class="form-control" id="texto_noticia" name="texto_noticia" maxlenth="1500" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" >
            </div>
            
            <div class="form-group">
                <label for="hora_inicio">Hora Inicio</label>
                <input type="time" min="09:00" max="18:00" class="form-control" id="hora_inicio" name="hora_inicio" >
            </div>

            <div class="form-group">
                <label for="hora_fin">Hora Fin</label>
                <input type="time" min="09:00" max="18:00" class="form-control" id="hora_fin" name="hora_fin" >
            </div>

            <div class="form-group">
                <label  for="seo_imagen">SEO imagen</label>
                <input class="form-control" type="text" name="seo_imagen" id="seo_imagen" maxlenth="20">            
            </div>
            
            <label for="thumbnail">Seleccionar imagen de vista previa</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="thumbnail " name="thumbnail">
                <label class="custom-file-label" for="thumbnail">Seleccionar imagen</label>
            </div>            
            <div class="form-check">                    
                <input class="form-check-input" type="checkbox" id="visible" name="visible">
                <label class="form-check-label" for="visible">Noticia visible</label>                    
            </div>
            <br>
            <button type="submit" class="btn btn-primary p-5 my-5" name="crear_noticia">Crear noticia</button>
            <br>
        </form>
    </div>
</div>  
<?php
    if(isset($_POST['crear_noticia'])){
        $consulta_insert = $conexion -> prepare("INSERT INTO noticia VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $id=null;
        $titulo     =$_POST['titulo_noticia'];
        $texto      =$_POST['texto_noticia'];
        $fecha      =$_POST['fecha'];
        $hora_i     =$_POST['hora_inicio'];
        $hora_f     =$_POST['hora_fin'];
        $seo        =$_POST['seo_imagen'];
        $thumbnail  ="";
        $visible    =0;

        if(isset($_POST['visible'])){
            $visible=1;
        }

        $consulta_insert -> bindParam(1,$id);
        $consulta_insert -> bindParam(2,$titulo);
        $consulta_insert -> bindParam(3,$texto);
        $consulta_insert -> bindParam(4,$fecha);
        $consulta_insert -> bindParam(5,$hora_i);
        $consulta_insert -> bindParam(6,$hora_f);
        $consulta_insert -> bindParam(7,$seo);
        $consulta_insert -> bindParam(8,$thumbnail);
        $consulta_insert -> bindParam(9,$visible);

        $consulta_insert -> execute();
        
        $id_noticia=$conexion->lastInsertId();
        //comprobamos que existe la carpeta de imágenes

        if(!file_exists("../../assets/img/noticias/thumbnail")){
            mkdir("../../assets/img/noticias/thumbnail");
        }

        //Copiar la imagen a nuestro servidor

        $nombre_tmp_thumbnail=$_FILES['thumbnail']['tmp_name'];
        $extension_thumbnail=extension_imagen($_FILES['thumbnail']['type']);

        //copiamos la thumbnail con name "thumbnail"

        $nombre_thumbnail="thumbnail_noticia_$id_noticia".$extension_thumbnail;
        move_uploaded_file($nombre_tmp_thumbnail,"../../assets/img/noticias/thumbnail/$nombre_thumbnail");

        //Actualizamos nuestro notician con los nombres de las imágenes

        $consulta_actualizacion=$conexion -> prepare("UPDATE noticia SET thumbnail=? WHERE id=?");

        $consulta_actualizacion -> bindParam(1, $nombre_thumbnail);
        $consulta_actualizacion -> bindParam(2, $id_noticia);

        $consulta_actualizacion -> execute();

        echo "<meta http-equiv='refresh' content='0; url=noticias.php'>";
    }
?>
<?php
    $conexion = null;
    import_js_bootstrap();
?>
</body>
</html>
<?php
}
?>