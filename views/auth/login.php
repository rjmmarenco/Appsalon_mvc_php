<H1 class="nombre-pagina">Login</H1>
<p class="descripcion-pagina">Inicia session con tus datos</p>

<?php 
    include_once __DIR__ ."/../templates/alertas.php";
 
?>

<form class="formulario" method="POST" action="/">
    <DIV class="campo">
        <label for="email">Email : </label>
        <input type="email" id="email" placeholder="Tu email" name="email" value="<?php echo s($auth->email); ?>">
    </DIV>
    <div class="campo">
        <label for="password">Password : </label>
        <input type="password" id="password" placeholder="Tu Password" name="password">
    </div>

    <input type="submit" class="boton" value="Iniciar Sesion">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una!!!</a>
    <a href="/forgotten">Olvide mi Contraseña</a>
</div>