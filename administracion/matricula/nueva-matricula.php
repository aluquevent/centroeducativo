<?php
session_start();
include '../../assets/php/functions.php';
if(!isset($_SESSION['nombre']) || $_SESSION['tipo']!="administrador"){
    echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
}else{
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva matrícula</title>
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
    
    $consulta_alumnos = $conexion -> prepare("SELECT nif, nombre, apellido1, apellido2 FROM alumno");
    $consulta_alumnos -> setFetchMode(PDO::FETCH_ASSOC);
    $consulta_alumnos -> execute();

    $consulta_cursos = $conexion -> prepare("SELECT id, nombre, nif_profesor FROM curso");
    $consulta_cursos -> setFetchMode(PDO::FETCH_ASSOC);
    $consulta_cursos -> execute();

    // $consulta_matricula -> prepare("SELECT id_curso, nif_alumno") Intento de filtro para que si está ya creada una matricula para ese alumno, no se deje hacer una seleccion
    
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Crear matrícula</h1>
                <form class="col-12" action=# method="POST">
                    <div class="row">
                    
                    <div class="form-group col-6">
                    <label for="alumno">Seleccionar alumno</label>
                        <select class="custom-select" name="alumno" id="alumno">
                            <?php
                            while($datos_alumno=$consulta_alumnos->fetch()){
                                echo"
                                <option value='$datos_alumno[nif]'> $datos_alumno[nombre] $datos_alumno[apellido1] $datos_alumno[apellido2]</option>
                                ";
                            }                       
                            ?>
                        </select>
                        </div>

                        <div class="form-group col-6">
                            <label for="curso">Seleccionar curso</label>
                            <select class="custom-select" name="curso" id="curso">
                                <?php
                                while($datos_curso=$consulta_cursos->fetch()){
                                    $consulta_profesores = $conexion->prepare("SELECT nombre, apellido1, apellido2 FROM profesor WHERE nif_profesor=?");
                                    $consulta_profesores -> bindParam(1,$datos_curso['nif_profesor']);
                                    $consulta_profesores -> setFetchMode(PDO::FETCH_ASSOC);
                                    $consulta_profesores -> execute();
                                    $datos_profesor = $consulta_profesores -> fetch();
                                    echo"
                                    <option value='$datos_curso[id]'> $datos_curso[nombre] - $datos_profesor[nombre] $datos_profesor[apellido1]</option>
                                    ";
                                }                        
                                ?>
                            </select>
                        </div>
                        </div>
                        <div class="col-12">
                        <button type="submit" class="btn btn-primary" name="crear_matricula">Crear matrícula</button>
                        </div>
                    
                </form>
            </div>
        </div>
    </div>

    <?php
    if(isset($_POST['crear_matricula'])){
        $fecha_matricula = date('Y-m-d');
        $nif_alumno = $_POST['alumno'];
        $id_curso = $_POST['curso'];

        $insercion_matricula = $conexion -> prepare("INSERT INTO matricula VALUES (?,?,?)");

        $insercion_matricula -> bindParam(1,$nif_alumno);
        $insercion_matricula -> bindParam(2,$id_curso);
        $insercion_matricula -> bindParam(3,$fecha_matricula);

        $insercion_matricula -> execute();
    }
    $conexion=null;
    import_js_bootstrap();
    ?>

</body>
</html>
<?php
}
?>