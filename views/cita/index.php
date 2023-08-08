<H1 CLASS="nombre-pagina">Crear Nueva Cita</H1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos a continuacion</p>

<?php 
    include_once __DIR__ .'/../templates/barra.php';
?>

<div id="app">
    <nav class="tabs">
        <button id="boton-1" class="actual" type="button" data-paso="1">Servicios</button>
        <button id="boton-2" type="button" data-paso="2">Informacion Cita</button>
        <button id="boton-3" type="button" data-paso="3">Resumen</button>
    </nav>
    
    <div id="paso-1" div="paso-1" class="seccion"> 
        <h2>Servicios</h2>
         <p class="text-center">Elige los Servicios a continuacion</p>
        <div class="listado-servicio" id="servicio"></div>
    </div>

    <div id="paso-2" div="paso-2" class="seccion paso2err"> 
        <h2>Tus Datos y Cita</h2>
         <p class="text-center">Coloca tus datos y fecha de tu cita</p>
         <div class="errorPaso2"></div>
        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input id="txtnombre" type="text" placeholder="Tu Nombre" value="<?php echo $nombre; ?>" disabled/>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input id="txtfecha" type="date" min="<?php echo date('Y-m-d',strtotime('+1 day')) ?>"/>
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input id="txthora" type="time" min="<?php echo date('H-m') ?>" />
            </div>
            <input type="hidden" id="idCliente" value="<?php echo $id; ?>">

        </form>
    </div>
    
    <div id="paso-3" div="paso-3" class="seccion contenido-resumen"> 
        <h2>Resumen</h2>
         <p class="text-center">Verifica que la informacion sea correcta</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button>
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>
</div>
<?php 
    $script="
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";
?>