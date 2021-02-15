<?php

function conectarBD(){
    // ESTABLECER CONEXIÓN A LA BASE DE DATOS PDO
    $usuario="root";
    $contrasena="";
    try{
        $mbd = new PDO(
            'mysql:host=localhost;dbname=centro_edu_eag',
            $usuario,
            $contrasena,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    }catch(PDOException $e){
        echo $e -> getMessage();
    }

    return $mbd;
}

function formatearFecha($fecha){
    $timestamp=strtotime($fecha);
    $fecha_nacimiento=date('d/m/Y', $timestamp);
    return $fecha_nacimiento;
}
/* Funcion para importar estilo desde assets*/

function import_css(){
    echo "
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='../../assets/icons/css/font-awesome.min.css'>
    <link rel='stylesheet' href='../../assets/css/bootstrap.min.css'>
    <link rel='stylesheet' href='../../assets/css/estilo.css'>";
}


//function para importar bootstrap

function import_tipo(){
    echo "<link href='https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700;900&display=swap' rel='stylesheet'><link rel='stylesheet' href='../../assets/icons/css/font-awesome.min.css'>";
}

function import_js_bootstrap(){
    echo"<script
    src='https://code.jquery.com/jquery-3.5.1.slim.js'
    integrity='sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM='
    crossorigin='anonymous'></script><script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>";

}

function menu_administrador(){
?> 
<header id="header">
    <div class="container top-nav">
      <div class="row">
        <div class="col-12">
          <small>Tienes alguna pregunta?</small>
          <a class="mr-3 "href="tel:+34958278060"><i class="mr-1 fa fa-phone"></i>958 27 80 60</a>
          <a href="info@escuelaartegranada.com">info@escuelaartegranada.com</a>
        </div>
        
      </div>
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
              <form action="../../login.php" method="POST">
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
          <div class="logo-dark"><a href="../../index.php">E-<span>Web</span></a></div>
          <div class="nav-links">
              <a href="../matricula/matricula.php">Matrículas</a>
              <a href="../curso/cursos.php">Cursos</a>
              <a href="../noticias/noticias.php">Noticias</a>
              <a href="../profesores/profesor.php">Profesores</a>
              <a href="../alumnos/alumno.php">Alumnos</a>
              <button href=""><i class="fa fa-search"></i></button>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <?php  
}

function menu_profesor(){
  ?> 
<header id="header">
    <div class="container top-nav">
      <div class="row">
        <div class="col-12">
          <small>Tienes alguna pregunta?</small>
          <a class="mr-3 "href="tel:+34958278060"><i class="mr-1 fa fa-phone"></i>958 27 80 60</a>
          <a href="info@escuelaartegranada.com">info@escuelaartegranada.com</a>
        </div>
        
      </div>
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
              <form action="../../login.php" method="POST">
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
          <div class="logo-dark"><a href="../../index.php">E-<span>Web</span></a></div>
          <div class="nav-links">
              <a href="../profesores/profesor.php">Perfil</a>              
              <a href="../curso/cursos.php">Cursos</a>
              <a href="../noticias/noticias.php">Noticias</a>
              <button href=""><i class="fa fa-search"></i></button>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <?php  
  }

//Función para determinar la extensión de una imagen
function extension_imagen($tipo_imagen){
    $extension="";
    switch($tipo_imagen){
        case "image/jpeg": $extension=".jpeg";
        break;
        case "image/png": $extension=".png";
        break;
    }
    
    return $extension;
}

function devuelveDia($fecha){
  $fecha = date('j',strtotime($fecha));
  return $fecha;
}

function devuelveMes($fecha){
  $fecha = date('M',strtotime($fecha));
  return $fecha;
}

function devuelveHoraMinutos($hora){
  $horas = date('G',strtotime($hora));
  $minutos = date('i', strtotime($hora));
  $hora_completa = $horas." : ".$minutos;
  return $hora_completa;
}



?>