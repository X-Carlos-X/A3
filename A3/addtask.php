<?php
  include 'lib/conectar.php';
  session_start();
  // Este $_POST se hará si los campos correspondientes no estan vacios.
  if(isset($_POST) && !empty($_POST['nombre']) && !empty($_POST['descripcion']) && !empty($_POST['fechaEntrega'])) {
      // Con las siguientes variables recogeremos los siguientes campos:
    $nombre = htmlspecialchars($_POST['nombre']); 
    $descripcion = htmlspecialchars($_POST['descripcion']); 
    $fechaEntrega = $_POST['fechaEntrega']; 
          // La siguiente $_SESSION solo se hará si esta ya se creo o no existe.
    if(isset($_SESSION) && !empty($_SESSION['usuario']['id'])) {
      $usuarioId = $_SESSION['usuario']['id'];
      // El siguiente "$sql" lo utilizaremos para cúando especifiquemos una tarea. El símbolo "?" corresponderia a un parámetro.
      $sql = "INSERT INTO tareas (nombre, descripcion, fecha_creacion, fecha_entrega, usuario) VALUES (?, ?, CURDATE(), ?, ?)"; 
      try {
        // Ahora hay que preparar los query e indicar los valores que vamos a insertar.
        $query = $db->prepare($sql); 
        $query->bind_param("sssi", $nombre, $descripcion, $fechaEntrega, $usuarioId);
        $query->execute(); 
        // Si insertamos una tasklist luego haremos que nos lleve a la tasklist y ahi podremos ver nuestras tareas.
        if($query->affected_rows == 1) {
          header('Location: tasklist.php');
        }
      }
      // Si no el mensaje de error.
      catch(Exception $e) {
        $error = $e->getMessage();
      }
    }
  }
  include 'lib/head.php';
  include 'lib/header.php';
?>
<!-- Pagina del usuario logueado con sus tareas para añadir. -->
<div class="container">
  <ol class="breadcrumb">
    <li><a href="index.php">Inicio</a></li>
    <li><a href="tasklist.php">Tareas de <?php echo $_SESSION['usuario']['nombre']; ?></a></li>
    <li class="active">Tarea nueva</li>
  </ol>
  <div class="row">
    <div class="col-lg-6 col-lg-offset-3">
      <h1 class="text-center">Tarea nueva</h1>
      <!-- Mostrar la alerta ERROR en caso de que falle. He usado la etiqueta 
      strong ya que en el manual del boopstrap que descargué lo recomienda usar solamente en errores -->
      <?php if(isset($error)): ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>ERROR</strong> <?php echo $error; ?>.
        </div>
      <!-- Se creará un form para enviar la tarea -->
      <?php endif; ?>
      <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Titulo de la tarea</label><br />
        <input type="text" name="nombre" required /><br />
        <label>Mi tarea...</label><br />
        <textarea name="descripcion" placeholder="Escribe aqui la descripcion de la tarea..." required></textarea><br />
        <label>Entregar en...</label><br />
        <input type="date" name="fechaEntrega" required /><br /><br />
        <input class="btn btn-success" type="submit" value="Crear tarea" />
      </form>
    </div>
  </div>
</div>