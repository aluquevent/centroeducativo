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
    <title>Nuevo Admin</title>
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
            <h1 class="col-12">Nuevo administrador</h1>
            
            <form class="col-12" action="#" method="post" enctype="multipart/form-data">            
                <div class="form-group">
                    <label for="nombre_administrador">Nombre administrador</label>
                    <input type="text" class="form-control" id="nombre_administrador" name="nombre_administrador" >
                </div>
                
                <div class="form-group">
                    
                    <label for="apellido1_administrador">Primer apellido</label>
                    <input type="text" class="form-control" id="apellido1_administrador" name="apellido1_administrador">
                    </select>
                </div>
                <div class="form-group">                
                    <label for="apellido2_administrador">Segundo apellido</label>
                    <input type="text" class="form-control" id="apellido2_administrador" name="apellido2_administrador">
                    </select>
                </div>                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena">
                </div>

                <button type="submit" class="btn btn-primary" name="crear">Crear administrador</button>

            </form>
        </div>
    </div>

    <?php 
    if(isset($_POST['crear'])){
        $consulta = $conexion -> prepare("INSERT INTO administrador VALUES (?, ?, ?, ?, ?, ?)");

        $id=null;
        $nombre     =$_POST['nombre_administrador'];
        $apellido1  =$_POST['apellido1_administrador'];
        $apellido2  =$_POST['apellido2_administrador'];
        $email      =$_POST['email'];
        $contrasena =$_POST['contrasena'];
        $contrasena =password_hash($contrasena, PASSWORD_DEFAULT);
    
        $consulta -> bindParam(1,$id);
        $consulta -> bindParam(2,$email);
        $consulta -> bindParam(3,$nombre);
        $consulta -> bindParam(4,$apellido1);
        $consulta -> bindParam(5,$apellido2);
        $consulta -> bindParam(6,$contrasena);

        $consulta -> execute();
        
        $conexion = null;

        echo "<meta http-equiv='refresh' content='0; url=administrador.php'>";

    }
    ?>

</body>
</html>
<?php
}
?>