<?php
    session_start();
    include "../../assets/php/functions.php";
    if(!isset($_SESSION['nombre']) or $_SESSION['tipo']=="alumno"){
        echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
    }else{
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    $conexion=conectarBD();
        if($_SESSION['tipo']=="administrador"){
           menu_administrador();

       }else{
           menu_profesor();
       }

?>
    <a href="nuevo-profesor.php"><button type="button" class="btn btn-primary">Nuevo profesor</button></a>
    <br><br>
<?php
    $conexion=conectarBD();

    //Borramos alumno con id=? si está definido al recargar. 

    if(isset($_GET['id'])){
        $consulta_borrar=$conexion->prepare("DELETE FROM profesor where id_profesor=?");
        $id=$_GET['id'];

        $consulta_borrar->bindParam(1, $id);

        $consulta_borrar->execute();
    }

    $consulta_profesores=$conexion->prepare("SELECT id_profesor, nif_profesor, nombre, apellido1, apellido2, email, f_nac, imagen from profesor");

    $consulta_profesores->setFetchMode(PDO::FETCH_ASSOC);
    $consulta_profesores->execute();

    $num_profesores=$consulta_profesores->rowCount();

    if($num_profesores>0){
?>

    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Imagen</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Fecha nacimiento</th>            
                <th scope="col">Editar</th>
                <th scope="col">Borrar</th>
            </tr>
        </thead>
        <tbody>        
<?php

    while($datos=$consulta_profesores->fetch()){       
        echo "
        <tr>                
            <th scope='row'>$datos[id_profesor]</th>
            <td><img style='width:50px;' src='../../assets/img/profesores/$datos[imagen]'</td>
            <td>$datos[nombre] $datos[apellido1] $datos[apellido2]</td>
            <td>$datos[email]</td>
            <td>$datos[f_nac]</td>
            <td><button class='btn btn-success'><a href='editar-profesor.php?id=$datos[id_profesor]'> Editar </a></button></td>
            <td><button class='btn btn-danger'><a class='borrar' href='profesor.php?id=$datos[id_profesor]'> Borrar </a></button></td>
        </tr>";
    }

?>
    </tbody>
</table> 
<?php

    }else{
        echo "<p>No hay profesores actualmente</p>";
    }       
    $conexion=null;
    import_js_bootstrap();
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