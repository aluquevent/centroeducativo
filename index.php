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
            <div class="logo-dark"><a href="#">E-<span>Web</span></a></div>
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button> -->
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">Escuela</a>
                <a href="curso_token.php">Cursos</a>
                <a href="noticia_token.php">Noticias</a>
                <a href="#">Contacto</a>
                <button href=""><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
      </nav>
  </header>
  <main>
    <section id="banner"> 
        <h1>Cursos online</h1>
        <h3>Html5 - Css - Php - MySql - Javascript - WordPress - Prestashop</h3>
        <a class="boton1" href="./administracion/curso/cursos.php">Más información</a>
    </section>

    <section id="recursos" class="container">
      <div class="row">      
        <div class="bloque-titulo col-12">
          <h2>Bienvenido a la plataforma E-Web</h2>
          <p class="descText">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic officia ipsam nihil ullam veniam corrupti soluta esse ratione natus dicta. Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis, delectus.</p>
        </div>

        <div class="recurso col-12 col-md-3">
          <div>
            <img src="assets/img/icon_1.png">
          </div>
          <h3>Expertos</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>

        <div class="recurso col-12 col-md-3">
          <div>
            <img src="assets/img/icon_2.png">
          </div>
          <h3>Recursos</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>

        <div class="recurso col-12 col-md-3">
          <div>
            <img src="assets/img/icon_3.png">
          </div>
          <h3>Cursos</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>

        <div class="recurso col-12 col-md-3">
          <div>
            <img src="assets/img/icon_4.png">
          </div>
          <h3>Premios</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>          
      </div><!-- cierre row -->   
    </section>

    <div class="separador"></div>


    <?php
      $consulta_cursos = $conexion -> prepare("SELECT * FROM curso");
      $consulta_cursos -> setFetchMode(PDO::FETCH_ASSOC);
      $consulta_cursos -> execute();
      $num_cursos = $consulta_cursos -> rowCount();

      if($num_cursos>0){
        ?>
         <section id="cursos" class="container">
         <div class="row">
  
           <div class="bloque-titulo col-12">
             <h2>Cursos Online</h2>
             <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum eius magnam consequatur sapiente magni officiis voluptates minima neque alias perferendis.</p>
           </div>
        <?php
        
        
        $i=0;
        while($datos = $consulta_cursos->fetch() and $i<=2){

            $consulta_profe = $conexion->prepare("SELECT nombre, apellido1, apellido2 from profesor where nif_profesor=?");
            $consulta_profe->bindParam(1, $datos['nif_profesor']);
            $consulta_profe -> setFetchMode(PDO::FETCH_ASSOC);
            $consulta_profe-> execute();
            $datos_profe = $consulta_profe -> fetch();
            echo "<div class='col-12 col-md-4'>
            <div class='card'>
              <img src='assets/img/cursos/imagen/$datos[imagen]' class='card-img-top'>
              <div class='card-body'>
                <h3 class='card-title'><a href=curso_token.php?id=$datos[id]'>$datos[nombre]</a></h3>
                <h4>$datos_profe[nombre] $datos_profe[apellido1] $datos_profe[apellido2]</h4>
                <p class='card-text'>$datos[desc_corta]</p>
                <div class='separadorModi'></div>
                <div class='datos-curso'>                
                  <p><i class='fa fa-graduation-cap'></i>$datos[max_alumnos] Estudiantes</p>
                  <p class='precio'>$datos[precio]€</p>
                </div>
              </div>
            </div>
          </div>"; 
          $i++;
        }        
      ?>
          <div class="col-12 d-flex justify-content-center my-5">
            <a class="boton2" href="">Ver todos los cursos</a>
          </div>
  
    
  
        <!-- cierre row -->
        </div>
  
      </section>
        
<?php

      }
    ?>

 
    <div class="separador"></div>
<?php
  $consulta_noticias = $conexion -> prepare ("SELECT * from noticia");
  $consulta_noticias -> setFetchMode(PDO::FETCH_ASSOC);
  $consulta_noticias -> execute();
  $num_noticias = $consulta_noticias -> rowCount();
  if($num_noticias>0){  
?>
    <section id="noticias" class="container">
      <div class="row">      
        <div class="bloque-titulo col-12">
          <h2>Noticias</h2>
          <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate id officiis non architecto quidem facere velit hic quas beatae expedita.</p>
        </div>

    <?php
    $i = 0;

    while($datos_noticias = $consulta_noticias->fetch() and $i<=2){

   

    echo"
        <div class='col-12 col-md-4'>
          <div class='tarjeta'>
            <img src='assets/img/noticias/thumbnail/$datos_noticias[thumbnail]' class='card-img-top img-noticia'>
            <div class='descripcion col-12'>
              <div class='fecha-noticia'>
                <p class='fecha numero'>".devuelveDia($datos_noticias['fecha'])."</p>
                <p class='fecha mes'>".devuelveMes($datos_noticias['fecha'])."</p>
              </div>
              <div class='col-9 tarjeta-body'>
                <h3 class='card-title'><a href='noticia_token.php?id=$datos_noticias[id]'>$datos_noticias[titulo]</a></h3>          
                <p><span></span>".devuelveHoraMinutos($datos_noticias['hora_inicio'])." - ".devuelveHoraMinutos($datos_noticias['hora_fin'])."</p>            
                <p class='card-text' style='max-height:150px; overflow:hidden;'> $datos_noticias[texto]</p>
              </div>
            </div>            
            </div>
        </div>";
      }
      ?>
      </div><!-- cierre row -->
    </section>
<?php
  } //fin if num_noticias
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