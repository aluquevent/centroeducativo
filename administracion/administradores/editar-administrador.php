<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre']) || $_SESSION['tipo']!="administrador"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar administrador</title>
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
            echo "<meta http-equiv='refresh' content='0; url=administrador.php'>";
        }else{
            menu_administrador();
            $conexion = conectarBD();

            //Seleccionamos los datos del curso en cuestión
            $consulta = $conexion -> prepare("SELECT * FROM administrador WHERE id=?");

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

        <button type="submit" class="btn btn-primary p-5 my-5" name="actualizar">Actualizar</button>
    </form>
    </div>
</div>

<?php
    }//FIN IF ELSE de $_GET['id']


    if(isset($_POST['actualizar'])){   
        
        $consulta_update = $conexion -> prepare("UPDATE administrador SET email=?, nombre=?, apellido1=?, apellido2=? WHERE id=?");
        
        $nombre         = $_POST['nombre'];;
        $apellido1      = $_POST['apellido1'];
        $apellido2      = $_POST['apellido2'];
        $email          = $_POST['email'];  

        $consulta_update -> bindParam(1, $email);
        $consulta_update -> bindParam(2, $nombre);
        $consulta_update -> bindParam(3, $apellido1);
        $consulta_update -> bindParam(4, $apellido2);
        $consulta_update -> bindParam(5, $id);


        $consulta_update -> execute();

        echo "<meta http-equiv='refresh' content='0; url=administrador.php'>";
    }
     
    $conexion=null;
?>
</body>
<?php

?>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</html>
<?php
}
?>