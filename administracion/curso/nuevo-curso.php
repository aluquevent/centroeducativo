<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre']) or $_SESSION['tipo']=="alumno"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administrador</title>
    <?php import_tipo() ?>
    <?php import_css();
    import_js_bootstrap(); ?>
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
$conexion=conectarBD();
?>

<div class="container">
    <div class="row">
        <h1 class="col-12">Crear curso</h1>
        
        <form class="col-12" action="#" method="post" enctype="multipart/form-data" >
        
            <div class="form-group">
                <label for="nombre_curso">Nombre del curso (máx 50 chars)</label>
                <input type="text" class="form-control" id="nombre_curso" name="nombre_curso">
            </div>
            
            <div class="form-group">
                
                <label for="nombre_profesor">Profesor</label>
                <select class="custom-select" id="nombre_profesor" name="nombre_profesor">
                <option>Elige un profesor</option>

                <?php
                    $consulta_profesores= $conexion -> prepare("SELECT nif_profesor,nombre,apellido1,apellido2 from profesor");
                    $consulta_profesores -> setFetchMode(PDO::FETCH_ASSOC);
                    $consulta_profesores -> execute();
                    while($profesor=$consulta_profesores->fetch()){
                        echo "<option value='$profesor[nif_profesor]'>$profesor[nombre] $profesor[apellido1] $profesor[apellido2]</option>";
                    }
                ?>
                
                </select>
            </div>
            <div class="form-group">
                <label for="descripcion_breve">Descripcion breve</label>
                <textarea class="form-control" id="descripcion_breve" name="descripcion_breve" maxlenth="150" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion_extensa">Descripcion extensa</label>
                <textarea class="form-control" id="descripcion_extensa" name="descripcion_extensa" maxlenth="150" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="num_maximo_alumnos">Nº máximo alumnos</label>
                <input type="number" class="form-control" id="num_maximo_alumnos" name="num_maximo_alumnos" maxlenth="2">
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" class="form-control" step="any" id="precio" name="precio" maxlenth="2">
            </div>
            <label for="imagen">Seleccionar imagen</label>
            
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="imagen" name="imagen">
                <label class="custom-file-label" for="imagen">Seleccionar Archivo</label>            
            </div>
            
            <label for="thumbnail">Seleccionar imagen de vista previa</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="thumbnail " name="thumbnail">
                <label class="custom-file-label" for="thumbnail">Seleccionar imagen</label>
            </div>
            
            <div class="form-group">
                <label  for="seo_imagen">SEO imagen</label>
                <input class="form-control" type="text" name="seo_imagen" id="seo_imagen" maxlenth="20">
            
            </div>
            
            <div class="form-check">
                    
                <input class="form-check-input" type="checkbox" value="" id="visible" name="visible">
                <label class="form-check-label" for="visible">Curso visible</label>                    
            </div>

            <br>
            <button type="submit" class="btn btn-primary p-5 my-5" name="crear">Crear curso</button>
            <br>
        </form>
    </div>
</div>

<?php
    if(isset($_POST['crear'])){
        $consulta_insercion = $conexion -> prepare("INSERT INTO curso VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $id                 = null;
        $nombre_curso       = $_POST['nombre_curso'];
        $profesor           = $_POST['nombre_profesor'];
        $desc_breve         = $_POST['descripcion_breve'];
        $desc_extensa       = $_POST['descripcion_extensa'];
        $num_max_alumnos    = $_POST['num_maximo_alumnos'];
        $precio             = $_POST['precio'];
        $imagen             = "";
        $thumbnail          = "";
        $seo_imagen         = $_POST['seo_imagen'];
        $visible            = 0;
        if(isset($_POST['visible'])){
            $visible=1;
        }

        $consulta_insercion->bindParam(1, $id);
        $consulta_insercion->bindParam(2, $nombre_curso);
        $consulta_insercion->bindParam(3, $profesor);
        $consulta_insercion->bindParam(4, $desc_breve);
        $consulta_insercion->bindParam(5, $desc_extensa);
        $consulta_insercion->bindParam(6, $num_max_alumnos);
        $consulta_insercion->bindParam(7, $precio);
        $consulta_insercion->bindParam(8, $imagen);
        $consulta_insercion->bindParam(9, $thumbnail);
        $consulta_insercion->bindParam(10, $seo_imagen);
        $consulta_insercion->bindParam(11, $visible);

        $consulta_insercion->execute();
        
        //Recojo el id de la inserción del curso

        $id_curso=$conexion->lastInsertId();
        //comprobamos que existe la carpeta de imágenes

        if(!file_exists("../../assets/img/cursos/imagen")){
            mkdir("../../assets/img/cursos/imagen");
        }

        if(!file_exists("../../assets/img/cursos/thumbnail")){
            mkdir("../../assets/img/cursos/thumbnail");
        }

        //Copiar la imagen a nuestro servidor

        $nombre_tmp_imagen=$_FILES['imagen']['tmp_name'];

        $extension_imagen=extension_imagen($_FILES['imagen']['type']);

        //copiamos la imagen con name "imagen"

        $nombre_imagen="imagen_curso_$id_curso".$extension_imagen;
        move_uploaded_file($nombre_tmp_imagen,"../../assets/img/cursos/imagen/$nombre_imagen");

        $nombre_tmp_thumbnail=$_FILES['thumbnail']['tmp_name'];

        $extension_thumbnail=extension_imagen($_FILES['thumbnail']['type']);

        $nombre_thumbnail="thumbnail_curso_$id_curso".$extension_thumbnail;            
        move_uploaded_file($nombre_tmp_thumbnail,"../../assets/img/cursos/thumbnail/$nombre_thumbnail");

        //Actualizamos nuestro curson con los nombres de las imágenes

        $consulta_actualizacion=$conexion->prepare("UPDATE curso SET imagen=?, thumbnail=? where id=$id_curso");

        $consulta_actualizacion->bindParam(1, $nombre_imagen);
        $consulta_actualizacion->bindParam(2, $nombre_thumbnail);

        $consulta_actualizacion->execute();

        echo "<meta http-equiv='refresh' content='0; url=cursos.php'>";
    }


    $conexion=null;
    import_js_bootstrap();
    
    
?>
</body>
</html>
<?php
}
?>