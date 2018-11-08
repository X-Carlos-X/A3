<?php
  session_start(); 
  // Con la sesión iniciada y si hay un usuario en ella, guardamos el id del usuario y su nombre.
  if(isset($_SESSION) && !empty($_SESSION['usuario'])) {
    $usuarioId = $_SESSION['usuario']['id']; 
    $usuario = $_SESSION['usuario']['nombre']; 
  } else {
    echo '<h1>Debes iniciar sesión</h1>';
    die; // Nota: Utilizamos el die que es parecido a un break pero más enfocado a php. De esta manera detendremos el script
  }
  include 'lib/conectar.php';
  include 'lib/head.php';
  include 'lib/header.php';
  try {
     // Volvemos hacer sql y el query con los parámetors correspondientes.
    $sql = "SELECT id_tarea, nombre, descripcion, acabado, fecha_creacion, fecha_entrega FROM tareas WHERE usuario = ? ORDER BY fecha_creacion DESC"; // query de las tareas
    $query = $db->prepare($sql); 
    $query->bind_param("i", $usuarioId); 
    $query->execute(); 
    $query->store_result(); 
  }
  catch(Exception $e) {
    echo $e->getMessage();
  }
?>
<!-- El formulario correspondiente al tasklist -->
<div class="container">
  <ol class="breadcrumb">
    <li><a href="index.php">Inicio</a></li>
    <li class="active">Tareas de <?php echo $usuario; ?></li>
  </ol>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <a class="btn btn-default" href="addtask.php" style="margin-bottom: 10px;s"><span class="glyphicon glyphicon-plus"></span> Añadir nueva tarea</a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <?php if($query->num_rows > 0): ?>
        <?php $query->bind_result($idtarea, $nombre, $descripcion, $isAcabada, $fecha_creacion, $fecha_entrega); ?>
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Fecha de creacion</th>
              <th>Fecha de entrega</th>
              <th>Estado</th>
              <th>Opciones</th>
            </tr>
            <?php while($query->fetch()): ?>
              <tr>
                <td><?php echo $nombre; ?></td>
                <td><p class="descripcion"><?php echo $descripcion; ?></p></td>
                <?php $fechaCreacion = new DateTime($fecha_creacion); ?>
                <td><?php echo $fechaCreacion->format('d-m-Y'); ?></td>
                <?php $fechaEntrega = new DateTime($fecha_entrega); ?>
                <td><?php echo $fechaEntrega->format('d-m-Y'); ?></td>
                <?php if(!$isAcabada): ?>
                  <td>
                    <form action="lib/doaction.php" method="post">
                      <label>En curso</label>
                      <input type="hidden" name="action" value="1" />
                      <input type="hidden" name="id" value="<?php echo $idtarea; ?>" />
                      <button class="btn btn-default" type="submit">
                        <span class="glyphicon glyphicon-flag"></span> Acabar tarea
                      </button>
                    </form
                  </td>
                <?php else: ?>
                  <td>Terminado</td>
                <?php endif; ?>
                <td class="text-center">
                  <form action="lib/doaction.php" method="post">
                    <input type="hidden" name="action" value="2" />
                    <input type="hidden" name="id" value="<?php echo $idtarea; ?>" />
                    <button class="btn btn-danger" type="submit">
                      <span class="glyphicon glyphicon-trash"></span>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </table>
        </div>
      <?php else: ?>
        <h1 class="text-center">NO HAY TAREAS(</h1>
        <p class="text-center"><a href="addtask.php">Crear Tarea</a></p>
      <?php endif; ?>
    </div>
  </div>
</div>
