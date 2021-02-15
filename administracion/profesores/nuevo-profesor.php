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
    <title>Profesores</title>
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
    menu_administrador();
    $conexion = conectarBD();
    ?>

    <div class="container">
        <div class="row">
            <h1 class="col-12">Nuevo profesor</h1>
            
            <form class="col-12" action="#" method="post" enctype="multipart/form-data" >
            
                <div class="form-group">
                    <label for="nombre_profesor">Nombre profesor</label>
                    <input type="text" class="form-control" id="nombre_profesor" name="nombre_profesor" >
                </div>
                
                <div class="form-group">
                    
                    <label for="apellido1_profesor">Primer apellido</label>
                    <input type="text" class="form-control" id="apellido1_profesor" name="apellido1_profesor">
                    </select>
                </div>
                <div class="form-group">                
                    <label for="apellido2_profesor">Segundo apellido</label>
                    <input type="text" class="form-control" id="apellido2_profesor" name="apellido2_profesor">
                    </select>
                </div>
                <div class="form-group">
                    <label for="nif_profesor">NIF</label>
                    <input type="text" class="form-control" id="nif_profesor" name="nif_profesor" placeholder="69435323X">
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="fecha_nac">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac">
                </div>

                <label for="imagen">Seleccionar imagen</label>            
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="imagen" name="imagen">
                    <label class="custom-file-label" for="imagen">Seleccionar Archivo</label>            
                </div>

                <br>
                <button type="submit" class="btn btn-primary p-5 my-5" name="crear">Crear profesor</button>
                <br>
            </form>
        </div>
    </div>

    <?php 
    if(isset($_POST['crear'])){
        $consulta = $conexion -> prepare("INSERT INTO profesor VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $id=null;
        $nif        =$_POST['nif_profesor'];
        $nombre     =$_POST['nombre_profesor'];
        $apellido1  =$_POST['apellido1_profesor'];
        $apellido2  =$_POST['apellido2_profesor'];
        $email      =$_POST['email'];
        $f_nac      =$_POST['fecha_nac'];
        $imagen     ="";
        $contrasena =$_POST['contrasena'];
        $contrasena =password_hash($contrasena, PASSWORD_DEFAULT);
    
        $consulta -> bindParam(1,$id);
        $consulta -> bindParam(2,$nif);
        $consulta -> bindParam(3,$nombre);
        $consulta -> bindParam(4,$apellido1);
        $consulta -> bindParam(5,$apellido2);
        $consulta -> bindParam(6,$email);
        $consulta -> bindParam(7,$f_nac);
        $consulta -> bindParam(8,$imagen);
        $consulta -> bindParam(9,$contrasena);

        $consulta -> execute();

        $id_nuevo_profesor=$conexion->lastInsertId();


    //Subimos la imagen del alumno

        $temp = $_FILES['imagen']['tmp_name'];

        if(!file_exists("../../img")){
        mkdir("../../img");
        }

        //Identificamos la extensión del archivo
        $extension="";

        switch($_FILES['imagen']['type']){
        case "image/jpeg": $extension=".jpeg";
        break;
        case "image/png": $extension=".png";
        break;
        }

        $nombre_archivo="foto_profesor_$id_nuevo_profesor"."".$extension;

        move_uploaded_file($temp,"../../assets/img/profesores/$nombre_archivo");

        $consulta_img = $conexion -> prepare("UPDATE profesor SET imagen=? where id_profesor=$id_nuevo_profesor");

        $consulta_img ->bindParam(1, $nombre_archivo);

        $consulta_img ->execute();

        
        $conexion = null;

        echo "<meta http-equiv='refresh' content='0; url=profesor.php'>";

    }
    ?>

</body>
</html>
<?php
}
?>