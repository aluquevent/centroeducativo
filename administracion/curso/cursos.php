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
        <title>Panel de administración</title>
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
    if($_SESSION['tipo']=="administrador"){
         menu_administrador();
    }else{
        menu_profesor();
    }
       
    
    ?>
        <a href="nuevo-curso.php"><button type="button" class="btn btn-primary">Nuevo curso</button></a>
        <br><br>
    <?php
        $conexion=conectarBD();
    
        //Borramos alumno con id=? si está definido al recargar. 
    
        if(isset($_GET['id'])){
            $consulta_borrar=$conexion->prepare("DELETE FROM curso where id=?");
            $id=$_GET['id'];
    
            $consulta_borrar->bindParam(1, $id);
    
            $consulta_borrar->execute();
        }
    
        $consulta_cursos=$conexion->prepare("SELECT id, nombre, thumbnail, nif_profesor, max_alumnos, precio, seo_imagen, visible, imagen from curso");
    
        $consulta_cursos->setFetchMode(PDO::FETCH_ASSOC);
        $consulta_cursos->execute();
    
        $num_cursos=$consulta_cursos->rowCount();
    
        if($num_cursos>0){
    ?>
    
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Profesor</th>
                    <th scope="col">Nº estudiantes</th>
                    <th scope="col">Precio</th>
                    <th scope="col">SEO imagen</th>
                    <th scope="col">Visible</th>            
                    <th scope="col">Editar</th>
                    <th scope="col">Borrar</th>
                </tr>
            </thead>
            <tbody>        
    <?php
    
    
    
        while($datos=$consulta_cursos->fetch()){
            
            $consulta_profesor=$conexion->prepare("SELECT nombre, apellido1, apellido2 from profesor WHERE nif_profesor=?");
            
            $consulta_profesor->bindParam(1, $datos['nif_profesor']);
    
            $consulta_profesor->setFetchMode(PDO::FETCH_ASSOC);
    
            $consulta_profesor->execute();        
            $nom_profesor=$consulta_profesor->fetch();
            
            echo "
            <tr>                
                <th scope='row'>$datos[id]</th>
                <td><img style='width:50px;' src='../../assets/img/cursos/thumbnail/$datos[thumbnail]'</td>
                <td>$datos[nombre]</td>
                <td>$nom_profesor[nombre] $nom_profesor[apellido1] $nom_profesor[apellido2]</td>
                <td>$datos[max_alumnos]</td>
                <td>$datos[precio] €</td>
                <td>$datos[seo_imagen]</td>";
            
            if($datos['visible']==0){
                echo"
                <td>No</td>";     
            }else{
                echo"
                <td>Sí</td>";     
            }                    
            echo"                                       
                <td><button class='btn btn-success'><a href='editar-curso.php?id=$datos[id]'>Editar</a></button></td>
                <td><button class='btn btn-danger'><a class='borrar' href='cursos.php?id=$datos[id]'> Borrar </a></button></td>
            </tr>";
        }
    
    ?>
        </tbody>
    </table> 
    <?php
    
        }else{
            echo "<p>No hay cursos actualmente</p>";
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
