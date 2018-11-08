<?php
  // Al haber datos en los siguientes campos para ahcer singup guardaremos el nombre de usuario y la contraseña normal y repetida.
  if(isset($_POST) && !empty($_POST['username']) && !empty($_POST['pass']) && !empty($_POST['repass'])) {
    include 'lib/conectar.php';
    $usuario = htmlspecialchars($_POST['username']); 
    $password = htmlspecialchars($_POST['pass']);
    $repassword = htmlspecialchars($_POST['repass']);
    try {
      // Hacemos la comprobación de contraseñas y la encriptamos del mismo modo que hicimos en login.
      if($password == $repassword) {
        $md5password = md5($password);
        // Preparamos de nuevo sql y query poara introducirle los parametros
        $sql = "INSERT INTO usuarios (nombre, password) VALUES (?, ?)";
        $query = $db->prepare($sql);
        $query->bind_param("ss", $usuario, $md5password); 
        $query->execute(); 
        // Si se ha insertado correctamente el usuario a la base de datos, nos devolverá "1" así que iniciaremos la sesión.
        if($query->affected_rows == 1) {
          session_start(); 
          $_SESSION['signup_success'] = true;
          header('Location: index.php'); 
        }
      }
    }
    // En el caso cotnrarios guardaremos el error, y se saldrá con un método del boopstrap.
    catch(Exception $e) {
      $error = $e->getMessage(); 
    }
  }
  include 'lib/head.php';
  include 'lib/header.php';
?>
<!-- Form correspondiente al sing up -->
<div class="container">
  <ol class="breadcrumb">
    <li><a href="index.php">Inicio</a></li>
    <li class="active">Registrate</li>
  </ol>
  <div class="row">
    <div class="col-lg-6 col-lg-offset-3">
      <h1 class="text-center">Registrate</h1>
      <?php if(isset($error) && !empty($error)): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Oups!</strong> <?php echo $error; ?>
        </div>
      <?php endif; ?>
      <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label>Introduce tu nombre de usuario</label><br />
        <input type="text" name="username" required /><br />
        <label>Introduce tu contraseña</label><br />
        <input type="password" name="pass" required /><br />
        <label>Repite tu contraseña</label><br />
        <input type="password" name="repass" required /><br />
        <input class="btn btn-default" type="submit" value="Registrarse" />
      </form>
    </div>
  </div>
</div>

