<?php
    session_start();
    include "../../assets/php/functions.php";
    if(!isset($_SESSION['nombre']) || $_SESSION['tipo']!="administrador"){
        echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
    }else{
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administradores</title>
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

?>
    <a href="nuevo-administrador.php"><button type="button" class="btn btn-primary">Nuevo administrador</button></a>
    <br><br>
<?php
    $conexion=conectarBD();

    if(isset($_GET['id'])){
        $consulta_borrar=$conexion->prepare("DELETE FROM administrador where id=?");
        $id=$_GET['id'];

        $consulta_borrar->bindParam(1, $id);

        $consulta_borrar->execute();
    }

    $consulta_administrador=$conexion->prepare("SELECT id, nombre, apellido1, apellido2, email from administrador");

    $consulta_administrador->setFetchMode(PDO::FETCH_ASSOC);
    $consulta_administrador->execute();

    $num_administrador=$consulta_administrador->rowCount();

    if($num_administrador>0){
?>

    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>          
                <th scope="col">Editar</th>
                <th scope="col">Borrar</th>
            </tr>
        </thead>
        <tbody>        
<?php

    while($datos=$consulta_administrador->fetch()){       
        echo "
        <tr>                
            <th scope='row'>$datos[id]</th>
            <td>$datos[nombre] $datos[apellido1] $datos[apellido2]</td>
            <td>$datos[email]</td>                                      
            <td><button class='btn btn-success'><a href='editar-administrador.php?id=$datos[id]'> Editar </a></button></td>
            <td><button class='btn btn-danger'><a class='borrar' href='administrador.php?id=$datos[id]'> Borrar </a></button></td>
        </tr>";
    }
?>

    </tbody>
</table> 
<?php

    }else{
        echo "<p>No hay administradores actualmente</p>";
    }       
    $conexion=null;

?>
<script>

$(document).ready(function() {
     $(".borrar").click(function() {
          if (!confirm("¿Está seguro de esta operación?")) {
               return false;
          }
     });
});
</script>
            
</body>
</html>
<?php
    }
?>