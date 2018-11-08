<?php
  session_start();
  include 'lib/head.php';
?>
<!-- Crearemos el documento con los div u otros elementos que necesitemos. -->
<div class="container">
  <div class="row titulo-principal">
    <div class="col-lg-12 text-center">
      <h1>A3 - TODO List</h1>
    </div>
  </div>
  <div class="row text-center inicio-links">
      <!-- Ejecutará el siguiente php si estan los valores de sesión correspondientes -->
    <?php if(isset($_SESSION) && !empty($_SESSION['usuario']['id']) && !empty($_SESSION['usuario']['nombre'])): ?>
      <div class="col-lg-6">
        <a href="tasklist.php">Mis Tareas</a>
      </div>
      <div class="col-lg-6">
        <a href="lib/logout.php">LOG OUT</a>
      </div>
    <?php else: ?>
      <div class="col-lg-6">
        <a href="login.php">LOG IN</a>
      </div>
      <div class="col-lg-6">
        <a href="signup.php">Registrate</a>
      </div>
    <?php endif; ?>
  </div>
</div>