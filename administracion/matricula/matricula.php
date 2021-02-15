<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre']) or $_SESSION['tipo']!="administrador"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrículas</title>
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

    if(isset($_GET['nif'])){
        $consulta_borrar=$conexion->prepare("DELETE FROM matricula where id_curso=? and nif_alumno=?");
        $id=$_GET['id'];
        $nif=$_GET['nif'];

        $consulta_borrar->bindParam(1, $id);
        $consulta_borrar->bindParam(2, $nif);

        $consulta_borrar->execute();
    }
    ?>
    <a href="nueva-matricula.php"><button type="button" class="btn btn-primary">Nueva matricula</button></a>
        <br><br>
    <?php
    $consulta_matricula = $conexion -> prepare("SELECT * FROM matricula");
    $consulta_matricula -> setFetchMode(PDO::FETCH_ASSOC);
    $consulta_matricula -> execute();
    $num_filas_matricula = $consulta_matricula -> rowCount();
    if($num_filas_matricula>0){
        ?>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Fecha matrícula</th>
                    <th scope="col">Alumno</th>
                    <th scope="col">Curso</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Borrar</th>
                </tr>
            </thead>
            <tbody>
            <?php
            while($datos_matricula = $consulta_matricula -> fetch()){
                $consulta_curso = $conexion -> prepare("SELECT nombre FROM curso WHERE id=?");
                $consulta_curso -> bindParam(1, $datos_matricula['id_curso']);
                $consulta_curso -> setFetchMode(PDO::FETCH_ASSOC);
                $consulta_curso -> execute();
                $datos_curso = $consulta_curso -> fetch();

                $consulta_alumno = $conexion -> prepare("SELECT nombre, apellido1, apellido2 FROM alumno WHERE nif=?");
                $consulta_alumno -> bindParam(1, $datos_matricula['nif_alumno']);
                $consulta_alumno -> setFetchMode(PDO::FETCH_ASSOC);
                $consulta_alumno -> execute();
                $datos_alumno = $consulta_alumno -> fetch();

                echo "
                <tr>                
                    <th scope='row'>$datos_matricula[f_matricula]</th>
                    <td>$datos_alumno[nombre] $datos_alumno[apellido1] $datos_alumno[apellido2]</td>
                    <td>$datos_curso[nombre]</td>                                     
                    <td><button class='btn btn-success'><a href='editar-matricula.php?nif=$datos_matricula[nif_alumno]&id=$datos_matricula[id_curso]'> Editar </a></button></td>
                    <td><button class='btn btn-danger'><a class='borrar' href='matricula.php?nif=$datos_matricula[nif_alumno]&id=$datos_matricula[id_curso]'> Borrar </a></button></td>
                </tr>";

                
            }
            ?>
            </tbody>
        </table> 
        <?php
    }else{
        echo "<p>No hay matriculas actualmente</p>";
    }
        
    $conexion=null;
    import_js_bootstrap();
    ?>

</body>
</html>

<script>

$(document).ready(function() {
     $(".borrar").click(function() {
          if (!confirm("¿Está seguro de esta operación?")) {
               return false;
          }
     });
});
</script>
<?php
}
?>