<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre']) or $_SESSION['tipo']!="administrador"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar matrícula</title>
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
    if(!isset($_GET['nif'])){
            echo "<meta http-equiv='refresh' content='0; url=matricula.php'>";
        }else{
            menu_administrador();
            $conexion = conectarBD();

            //Seleccionamos los datos del curso en cuestión
            $consulta_matriculas = $conexion -> prepare("SELECT * FROM matriculas WHERE nif_alumno=? and id_curso=?");

            $nif=$_GET['nif'];
            $id_curso=$_GET['id'];

            $consulta_matriculas -> bindParam(1, $nif);
            $consulta_matriculas -> bindParam(2, $id_curso);
            $consulta_matriculas -> setFetchMode(PDO::FETCH_ASSOC);
            $consulta_matriculas -> execute();

            $datos_matricula= $consulta_matriculas -> fetch();

            $consulta_alumnos = $conexion -> prepare("SELECT nif, nombre, apellido1, apellido2 FROM alumno");            
            $consulta_alumnos -> setFetchMode(PDO::FETCH_ASSOC);
            $consulta_alumnos -> execute();

            $consulta_cursos = $conexion -> prepare("SELECT nombre, id FROM curso");
            $consulta_cursos -> setFetchMode(PDO::FETCH_ASSOC);
            $consulta_cursos -> execute();
            

        
      
?>
    <div class="container">
        <div class="row">
        <form class="col-6" action="#" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="alumno">Nombre Alumno</label>
            <select name="alumno" id="alumno">
                <?php
                    while($datos_alumno = $consulta_alumnos->fetch()){
                        if($datos_alumno['nif']==$nif){
                            echo"<option value='$datos_alumno[nif]' selected>$datos_alumno[nombre] $datos_alumno[apellido1] $datos_alumno[apellido2]</option>";
                        }else{
                            echo"<option value='$datos_alumno[nif]'>$datos_alumno[nombre] $datos_alumno[apellido1] $datos_alumno[apellido2]</option>";
                        }
                    }
                ?>
            </select>            
        </div>
        <div class="form-group">
            <label for="curso">Curso</label>
            <select name="curso" id="curso">
                <?php
                    while($datos_curso = $consulta_cursos->fetch()){
                        if($datos_curso['id']==$id_curso){
                            echo"<option value='$datos_curso[id]' selected>$datos_curso[nombre]</option>";
                        }else{
                            echo"<option value='$datos_curso[id]'>$datos_curso[nombre]</option>";
                        }
                    }
                ?>
            </select>            
        </div>

        <!-- pendiente de revision -->


        <button type="submit" class="btn btn-primary p-3 my-5" name="actualizar">Actualizar</button>
    </form>
    </div>
</div>

<?php
    }//FIN IF ELSE de $_GET['nif']
    if(isset($_POST['actualizar'])){
        $update_matricula = $conexion -> prepare("UPDATE matricula SET nif_alumno=?, id_curso=? WHERE nif_alumno=? and id_curso=?");

        $nuevo_alumno   = $_POST['alumno'];
        $nuevo_curso    = $_POST['curso'];
        $update_matricula -> bindParam(1,$nuevo_alumno);
        $update_matricula -> bindParam(2,$nuevo_curso);
        $update_matricula -> bindParam(3,$nif);
        $update_matricula -> bindParam(4,$id_curso);
        $update_matricula -> execute();

        echo "<meta http-equiv='refresh' content='0; url=matricula.php'>";
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