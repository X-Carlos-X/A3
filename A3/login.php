<?php
  // Con este if, haremos POST solamete si hay un nombre de usuario y la contraseña.
  // En la contraseña he puesto "md5" por que de esta manera la encriptará con ese método.
  if(isset($_POST) && !empty($_POST['nombre']) && !empty($_POST['pass'])) {
    session_start(); 
    include 'lib/conectar.php';
    $nombreUsuario = htmlspecialchars($_POST['nombre']);
    $pass = md5(htmlspecialchars($_POST['pass']));
    // Ahora en sql y query como hicimos antes, le pasaremos los parámetros con "?" mencionado anteriormente y lo guardaremos con store_result.
    $sql = "SELECT id_usuario FROM usuarios WHERE nombre = ? AND password = ?";
    $query = $db->prepare($sql); 
    $query->bind_param("ss", $nombreUsuario, $pass);
    $query->execute(); 
    $query->store_result(); 
    // Este if, al dar 1 significa que existe el usuario y cargamos el resultado en dos variables query
    if($query->num_rows == 1) {
      $query->bind_result($idUsuario);
      $query->fetch();
    // Guardaremos la sesión del usuario y el propio nombre de usuario.
      $_SESSION['usuario']['id'] = $idUsuario;
      $_SESSION['usuario']['nombre'] = $nombreUsuario; 
      // Con la siguiente cookie haremos que el usuario recuerde la sesión en el navegador, guardando su username y password.
      if(isset($_POST['recordar']) && $_POST['recordar'] == 1) {
        setCookie("password", $_POST['pass'], time()+(100*80*40*60)); 
        setCookie("nombreUsuario", $nombreUsuario, time()+(100*80*40*60)); 
      }
      header('Location: tasklist.php');
    // Si no existe el usuario, pues daremos el mensaje de error.
      } else {
      $error = true; 
    }
  }
  include 'lib/head.php';
  include 'lib/header.php';
?>
<!-- Form dónde introduciremos los datos de sesión -->
<div class="container">
  <ol class="breadcrumb">
    <li><a href="index.php">Inicio</a></li>
    <li class="active">Iniciar sesion</li>
  </ol>
  <section class="row">
    <div class="col-lg-6 col-lg-offset-3">
      <h1 class="text-center">Iniciar Sesion</h1>
      <?php if(isset($error) && $error): ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          El user o la contraseña son incorrectos.
        </div>
      <?php endif; ?>
      <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Nombre Usuario</label><br />
        <?php if(isset($_COOKIE) && !empty($_COOKIE['nombreUsuario'])): ?>
          <input type="text" name="nombre" value="<?php echo $_COOKIE['nombreUsuario']; ?>" required /><br />
        <?php else: ?>
          <input type="text" name="nombre" required /><br />
        <?php endif; ?>
        <label>Contraseña</label><br />
        <?php if(isset($_COOKIE) && !empty($_COOKIE['password'])): ?>
          <input type="password" name="pass" value="<?php echo $_COOKIE['password']; ?>" required /><br />
        <?php else: ?>
          <input type="password" name="pass" required /><br />
        <?php endif; ?>
        <label>Recordar</label>
        <input style="width: 20px; height: 20px;" type="checkbox" name="recordar" value="1" /><br />
        <input class="btn btn-default" type="submit" value="entrar" />
      </form>
    </div>
  </section>
</div>

