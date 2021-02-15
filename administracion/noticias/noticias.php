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
    <title>Noticias</title>
    <?php 
    import_tipo();
    import_css(); 
    import_js_bootstrap();
    ?>
    
    <script src='../../assets/js/bootstrap.min.js'></script>
    <script src="../../assets/js/app.js"></script>
</head>
<body>
<?php
        if($_SESSION['tipo']=="administrador"){
            menu_administrador();
       }else{
           menu_profesor();
       }

?>
    <a href="nueva-noticia.php"><button type="button" class="btn btn-primary">Nueva noticia</button></a>
    <br><br>
<?php
    $conexion=conectarBD();

    //Borramos alumno con id=? si está definido al recargar. 

    if(isset($_GET['id'])){
        $consulta_borrar=$conexion->prepare("DELETE FROM noticia where id=?");
        $id=$_GET['id'];

        $consulta_borrar->bindParam(1, $id);

        $consulta_borrar->execute();
    }

    $consulta_noticias=$conexion->prepare("SELECT id, titulo, texto, fecha, hora_inicio, hora_fin, seo_imagen, thumbnail, visible from noticia");

    $consulta_noticias->setFetchMode(PDO::FETCH_ASSOC);
    $consulta_noticias->execute();

    $num_noticias=$consulta_noticias->rowCount();

    if($num_noticias>0){
?>

    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Imagen</th>
                <th scope="col">Título</th>
                <th scope="col">Texto</th>
                <th scope="col">Fecha</th>
                <th scope="col">Hora inicio</th>
                <th scope="col">Hora fin</th>
                <th scope="col">SEO Imagen</th>
                <th scope="col">¿Visible?</th>            
                <th scope="col">Editar</th>
                <th scope="col">Borrar</th>
            </tr>
        </thead>
        <tbody>        
<?php



    while($datos=$consulta_noticias->fetch()){
        
        echo "
        <tr>                
            <th scope='row'>$datos[id]</th>
            <td><img style='width:50px;' src='../../assets/img/noticias/thumbnail/$datos[thumbnail]'</td>
            <td>$datos[titulo]</td>
            <td>$datos[texto]</td>
            <td>".formatearFecha($datos['fecha'])."</td>
            <td>$datos[hora_inicio]</td>
            <td>$datos[hora_fin]</td>
            <td>$datos[seo_imagen]</td>";
        
        if($datos['visible']==0){
            echo"
            <td>No</td>";     
        }else{
            echo"
            <td>Sí</td>";     
        }                    
        echo"                                       
            <td><button class='btn btn-success'><a href='editar-noticia.php?id=$datos[id]'>Editar</a></button></td>
            <td><button class='btn btn-danger'><a class='borrar' href='noticias.php?id=$datos[id]'> Borrar </a></button></td>
        </tr>";
    }
?>
    </tbody>
</table> 
<?php

    }else{
        echo "<p>No hay noticias actualmente</p>";
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