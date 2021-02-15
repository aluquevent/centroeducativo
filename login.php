<?php
session_start();
include 'assets/php/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <?php 
    import_tipo();
    import_css(); 
    ?>
</head>
<body>
    <form action="#" method="POST">
        <label for="email">Email</label>
        <input type="email" name="email">  
        <br>
        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena">
        <br>
        <button type="submit" class="btn btn-primary" name="enviar">Iniciar sesión</button>
    </form>
    
    <?php
    $conexion = conectarBD();
    if(isset($_POST['cerrar'])){
        session_destroy();
        echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    }
    if(isset($_POST['enviar'])){
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];
        
        $consulta_profesor = $conexion -> prepare("SELECT id_profesor, nombre, apellido1, nif_profesor, contrasena FROM profesor where email=?");
        $consulta_profesor -> bindParam(1, $email);
        $consulta_profesor -> execute();

        $filas_profesor=$consulta_profesor->rowCount();

        $consulta_alumno = $conexion -> prepare("SELECT id_alumno, nombre, apellido1, nif, contrasena FROM alumno WHERE email=?");
        $consulta_alumno -> bindParam(1,$email);
        $consulta_alumno -> execute();

        $filas_alumno=$consulta_alumno->rowCount();

        $consulta_administrador = $conexion -> prepare("SELECT nombre, apellido1, id, contrasena FROM administrador WHERE email=?");
        $consulta_administrador -> bindParam(1,$email);
        $consulta_administrador -> execute();
        
        $filas_administrador=$consulta_administrador->rowCount();

        if($filas_profesor>0){
            $datos_profesor=$consulta_profesor->fetch();
            if(password_verify($contrasena, $datos_profesor['contrasena'])){
                $_SESSION['nombre'] = $datos_profesor['nombre'];
                $_SESSION['email'] = $email;
                $_SESSION['apellido1'] = $datos_profesor['apellido1'];
                $_SESSION['id'] = $datos_profesor['id'];
                $_SESSION['tipo'] = 'profesor';
                echo "<meta http-equiv='refresh' content='0; url=administracion/curso/cursos.php'>";
            }else{
                echo'<script type="text/javascript">
                    window.location.href="index.php";
                    alert("Los datos no coinciden");
                    
                    </script>';
            
            }

        }elseif($filas_alumno>0){
            $datos_alumno=$consulta_alumno->fetch();
            if(password_verify($contrasena, $datos_alumno['contrasena'])){
                $_SESSION['nombre'] = $datos_alumno['nombre'];
                $_SESSION['email'] = $email;
                $_SESSION['apellido1'] = $datos_alumno['apellido1'];
                $_SESSION['id'] = $datos_alumno['id_alumno'];
                $_SESSION['tipo'] = 'alumno';
                echo "<meta http-equiv='refresh' content='0; url=administracion/alumnos/alumno.php'>";
            }else{
                echo'<script type="text/javascript">
                    window.location.href="index.php";
                    alert("Los datos no coinciden");
                    </script>';
               
            }
        }elseif($filas_administrador>0){
            $datos_administrador=$consulta_administrador->fetch();
            if(password_verify($contrasena, $datos_administrador['contrasena'])){
                $_SESSION['nombre'] = $datos_administrador['nombre'];
                $_SESSION['email'] = $email;
                $_SESSION['apellido1'] = $datos_administrador['apellido1'];
                $_SESSION['id'] = $datos_administrador['id'];
                $_SESSION['tipo'] = 'administrador';
                
                echo "<meta http-equiv='refresh' content='0; url=administracion/administradores/administrador.php'>";
            }else{
                echo'<script type="text/javascript">
                    window.location.href="index.php";
                    alert("Los datos no coinciden");
                    </script>';
               
            }
        }else{
            echo "<p>Los datos no coinciden</p>";
            echo'<script type="text/javascript">
            alert("Los datos no coinciden");
            window.location.href="index.php";
            </script>';
        
        }
    }   
    
    $conexion=null;
    ?>
    <?php
        import_js_bootstrap();
    ?>
</body>
</html>




