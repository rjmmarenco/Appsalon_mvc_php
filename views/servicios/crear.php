
<H1 class="nombre-pagina">
    NUEVO SERVICIO
</H1>
<p class="descripcion-pagina">
    Llena todos los campos para Agregar el Servicio
</p>

<?php 
   // include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>
<form action="/servicios/crear" method="POST" class="formulario">
    <?php include_once __DIR__ .'/formulario.php';  ?>
    <input type="submit" class="boton" value="Guardar Servicio">
</form>