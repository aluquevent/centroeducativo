<?php
session_start();
    include "../../assets/php/functions.php";
    if(!isset($_SESSION['nombre']) or $_SESSION['tipo']=="profesor"){
        echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
    }else{
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
            <meta charset="UTF-8">
            <title>Alumnos</title>
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
        if($_SESSION['tipo']=="alumno"){
            $id_alumno=$_SESSION['id'];
            $nombre_alumno = $_SESSION['nombre'];
            $apellido_alumno = $_SESSION['apellido1'];
            $email_alumno = $_SESSION['email'];
            menu_administrador();


            $consulta_alumno = $conexion -> prepare("SELECT * from alumno where id_alumno=?");
            $consulta_alumno -> bindParam(1,$id_alumno);
            $consulta_alumno -> setFetchMode(PDO::FETCH_ASSOC);
            $consulta_alumno -> execute();
            $datos_alumno = $consulta_alumno -> fetch();

            $nif_alumno = $datos_alumno['nif'];

            $consulta_matricula = $conexion -> prepare("SELECT id_curso from matricula where nif_alumno=?");
            $consulta_matricula -> bindParam (1,$nif_alumno);
            $consulta_matricula -> setFetchMode(PDO::FETCH_ASSOC);
            $consulta_matricula -> execute();
            
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-4 align-items-center">
                        <img style="max-width:100px" src="../../assets/img/alumnos/<?php echo $datos_alumno['imagen']?>" alt="">
                        <h2>Información personal</h2>
                        <p>Email:<?php echo $datos_alumno['email']?></p>                 
                        <p>Nombre:<?php echo "$datos_alumno[nombre] $datos_alumno[apellido1] $datos_alumno[apellido2]"?></p>                 
               
                        
                        
                    </div>
                    <div class="col-6">
                        <div id="cursos">
                            <h2>Cursos</h2>
                            <?php
                                while($datos_matricula = $consulta_matricula -> fetch()){
                                    $consulta_curso = $conexion ->prepare("SELECT nombre from curso where id=?");
                                    $consulta_curso -> bindParam(1,$datos_matricula['id_curso']);
                                    $consulta_curso -> setFetchMode(PDO::FETCH_ASSOC);
                                    $consulta_curso -> execute();
                                    $datos_curso = $consulta_curso->fetch();
                                    echo "<p>$datos_curso[nombre]</p>";
                                }
                            ?>
                        </div>                    

                    </div>
                </div>
            </div>
            <?php
        }else{
          
                menu_administrador();
           
            ?>
                <a href="nuevo-alumno.php"><button type="button" class="btn btn-primary">Nuevo alumno</button></a>
                <br><br>
            <?php
            

            //Borramos alumno con id=? si está definido al recargar. 

            if(isset($_GET['id'])){
                $consulta_borrar=$conexion->prepare("DELETE FROM alumno where id_alumno=?");
                $id=$_GET['id'];

                $consulta_borrar->bindParam(1, $id);

                $consulta_borrar->execute();
            }

            $consulta_alumno=$conexion->prepare("SELECT id_alumno, nif, nombre, apellido1, apellido2, email, f_nac, imagen from alumno");

            $consulta_alumno->setFetchMode(PDO::FETCH_ASSOC);
            $consulta_alumno->execute();

            $num_alumno=$consulta_alumno->rowCount();

            if($num_alumno>0){
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

                    while($datos=$consulta_alumno->fetch()){       
                        echo "
                        <tr>                
                            <th scope='row'>$datos[id_alumno]</th>
                            <td><img style='width:50px;' src='../../assets/img/alumnos/$datos[imagen]'</td>
                            <td>$datos[nombre] $datos[apellido1] $datos[apellido2]</td>
                            <td>$datos[email]</td>
                            <td>$datos[f_nac]</td>                                       
                            <td><button class='btn btn-success'><a href='editar-alumno.php?id=$datos[id_alumno]'> Editar </a></button></td>
                            <td><button class='btn btn-danger'><a class='borrar' href='alumno.php?id=$datos[id_alumno]'> Borrar </a></button></td>
                        </tr>";
                    }
                ?>

                    </tbody>
                </table> 
                <?php

            }else{
                echo "<p>No hay alumnos actualmente</p>";
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
        <?php
        }
        ?>
        
        </body>
        </html>
        <?php
    }
?>