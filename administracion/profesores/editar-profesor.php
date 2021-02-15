<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre'])or $_SESSION['tipo']!="administrador"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar profesor</title>
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
                echo "<meta http-equiv='refresh' content='0; url=profesor.php'>";
            }else{
                menu_administrador();
                $conexion = conectarBD();
    
                //Seleccionamos los datos del curso en cuestión
                $consulta = $conexion -> prepare("SELECT * FROM profesor WHERE id_profesor=?");
    
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
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $datos['nombre'];?>">
            </div>
            <div class="form-group">
                <label for="nif_profesor">NIF</label>
                <input type="text" class="form-control" id="nif_profesor" name="nif_profesor" value="<?php echo $datos['nif_profesor'];?>">
            </div>
            <div class="form-group">
                <label for="apellido1">Primer apellido</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1" value="<?php echo $datos['apellido1'];?>">
            </div>
            <div class="form-group">
                <label for="apellido2">Segundo apellido</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2" value="<?php echo $datos['apellido2'];?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $datos['email'];?>">
            </div>
            <div class="form-group">
                <label for="f_nac">Email</label>
                <input type="date" class="form-control" id="f_nac" name="f_nac" value="<?php echo $datos['f_nac'];?>">
            </div>       
            
            <label for="imagen">Seleccionar imagen</label>
            <div class="custom-file">
                <input class="custom-file label" type="file" name="imagen" id="imagen" lang="es">
                <label class="custom-file-label" for="imagen">Seleccionar imagen</label>
            </div> 
    
            <button type="submit" class="btn btn-primary p-5 my-5" name="actualizar">Actualizar</button>
        </form>
        </div>
    </div>
    
    <?php
        }//FIN IF ELSE de $_GET['id']
    
    
        if(isset($_POST['actualizar'])){   
            
            $consulta_update = $conexion -> prepare ("UPDATE profesor SET nombre=?, nif_profesor=?, apellido1=?, apellido2=?, email=?, f_nac=?, imagen=? WHERE id_profesor=?");
            
            $nombre         = $_POST['nombre'];
            $nif            = $_POST['nif_profesor'];
            $apellido1      = $_POST['apellido1'];
            $apellido2      = $_POST['apellido2'];
            $email          = $_POST['email'];
            $f_nac          = $_POST['f_nac'];
            $imagen         = $datos['imagen'];       
                    
            $id=$_GET['id'];
            
            if($_FILES['imagen']['tmp_name'] != ""){            
                
                //Comprobamos que existe la carpeta imágenes
                if(!file_exists("../../assets/img/profesores")){
                    mkdir("../../assets/img/profesores");
                }
    
                //Copiar la imagen a nuestro servidor
                $nombre_tmp_imagen=$_FILES['imagen']['tmp_name'];
    
                $extension_imagen=extension_imagen($_FILES['imagen']['type']);
    
                //copiamos la imagen con name "imagen"
                $nombre_imagen="foto_profesor_$id".$extension_imagen;
                move_uploaded_file($nombre_tmp_imagen,"../../assets/img/profesores/$nombre_imagen");
    
                $imagen=$nombre_imagen;
            }
    
            $consulta_update -> bindParam(1, $nombre);
            $consulta_update -> bindParam(2, $nif);
            $consulta_update -> bindParam(3, $apellido1);
            $consulta_update -> bindParam(4, $apellido2);
            $consulta_update -> bindParam(5, $email);
            $consulta_update -> bindParam(6, $f_nac);
            $consulta_update -> bindParam(7, $imagen);        
            $consulta_update -> bindParam(8, $id);
    
            $consulta_update -> execute();
    
            echo "<meta http-equiv='refresh' content='0; url=profesor.php'>";
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