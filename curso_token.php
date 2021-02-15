<?php
session_start();

include 'assets/php/functions.php';
$conexion = conectarBD();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Práctica Sass - Php</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab|Roboto:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/icons/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>
  <header id="header">
    <div class="container top-nav">
      <div class="row">
        <div class="col-12">
          <small>Tienes alguna pregunta?</small>
          <a class="mr-3"href="tel:+34958278060"><i class="mr-1 fa fa-phone"></i>958 27 80 60</a>
          <a href="info@escuelaartegranada.com">info@escuelaartegranada.com</a>
        </div>        
      </div>
      
<!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="background:none; border:none;">
      <a href="#"><i class="fa fa-user" aria-hidden="true"></i></a>
      </button>

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="myModalLabel">Login</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="login.php" method="POST">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input id=#myInput class="form-control" type="mail" name="email">   
                </div>
                <div class="form-group"> 
                  <label for="contrasena">Contraseña</label>
                  <input type="password" class="form-control" name="contrasena">
                </div>
                <button type="submit" class="btn btn-primary" name="enviar">Iniciar sesión</button>
                <button type="submit" class="btn btn-primary" name="cerrar">Cerrar sesión</button>
              </form>              
            </div>
          </div>
        </div>
      </div>   
      </div>
    
      <nav class="nav-bar">    
        <div class="container">
          <div class="row">      
            <div class="logo-dark"><a href="index.php">E-<span>Web</span></a></div>
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button> -->
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">Escuela</a>
                <a href="#">Cursos</a>
                <a href="#">Blog</a>
                <a href="#">Contacto</a>
                <button href=""><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
      </nav>
  </header>
  <main>
    <?php
    if(isset($_GET['id'])){
        $consulta_curso = $conexion -> prepare("SELECT * FROM curso WHERE id=?");
        $id = $_GET['id'];
        $consulta_curso -> bindParam(1, $id);
        $consulta_curso -> setFetchMode(PDO::FETCH_ASSOC);
        $consulta_curso -> execute();
        $datos_curso = $consulta_curso -> fetch();

        $consulta_profe = $conexion -> prepare("SELECT nombre, apellido1, apellido2 FROM profesor where nif_profesor=?");
        $nif_profesor = $datos_curso['nif_profesor'];
        $consulta_profe -> bindParam(1, $nif_profesor);
        $consulta_profe -> setFetchMode(PDO::FETCH_ASSOC);
        $consulta_profe -> execute();
        $datos_profe = $consulta_profe->fetch();
        ?>
        <div class="container">
            <div class="row text-center">
                <div class="col-12 text-center" style="background: url(assets/img/cursos/imagen/<?php echo "$datos_curso[imagen]"?> center center; background-size: cover;"">
                    <img style="border-radius:50px;"src="assets/img/cursos/thumbnail/<?php echo "$datos_curso[thumbnail]"?>" alt="">
                    <h1><?php echo "$datos_curso[nombre]"?></h1>
                    <p><?php echo "$datos_profe[nombre] $datos_profe[apellido1] $datos_profe[apellido2]"?></p>
                </div>
                <div class="col-6">
                  <h3>Precio</h3>
                <p><?php echo "$datos_curso[precio]"?> €</p>
                </div>
                <div class="col-6">
                  <h3>Máximo de alumnos</h3>
                  <p><?php echo "$datos_curso[max_alumnos]"?> alumnos</p>
                </div>
                <div class="col-12">
                  <h3>Descripción</h3>
                  <p><?php echo "$datos_curso[desc_larga]"?></p>
                </div>
                    


            </div>
        </div>
        <?php
    }else{
        echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
    }
    ?>
  </main>

  <footer id="footer">
    <div class="container">
      <div class="row">    
        <div class="col-4">
          <div class="logo-light">
            <a href="#">E-<span>Web</span></a>
          </div>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iure quisquam vel fugit, cum doloribus error illo, minus sit nulla veritatis soluta rerum voluptatum esse.</p>
          <div class=" col-12 rrss">            
              <a href="#"><i class="fa fa-facebook"></i></a>  
              <a href="#"><i class="fa fa-google-plus"></i></a>                       
              <a href="#"><i class="fa fa-instagram"></i></a>   
              <a href="#"><i class="fa fa-twitter"></i></a>  
          </div>
        </div>
  
        <div class="col-4">
          <h5>Contactar</h5>
          <ul>
              
            <li>Email: <a href="info.escuelaartegranada.com">info.escuelaartegranada.com</a></li>
            <li>Tel: <a href="tel:+34958278060">958 27 80 60</a></li>
            <li>Avda. Doctor Olóriz, 6, Granada, 18012</li>
          </ul>
        </div>
  
        <div class="col-12 col-md-4"> 
          <div class="row">
            <h5 class="col-12">Cursos</h5>
            <ul class="col-6">
              <li><a href="">Web</a></li>
              <li><a href="">Gráfico</a></li>
              <li><a href="">Informática</a></li>
              <li><a href="">3D</a></li>
            </ul>
            <ul class="col-6">
              <li><a href="">Interiores</a></li>
              <li><a href="">Ciclos</a></li>
              <li><a href="">Fotografía</a></li>
              <li><a href="">Audio Visual</a></li>
            </ul>          
          </div>
            
      </div>
      <div class="col-12 separador"></div>
      <div class="col-12 small">
        <small>Copyright &copy;2019 Todos los derechos reservados | <span>Escuela Arte Granada</span></small>
      </div>
    </div>
    
    
  </footer>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/app.js"></script>
 
</body>
</html>  