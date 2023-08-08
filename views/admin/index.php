<h1 class="nombre-pagina">
    Panel de Administracion
</h1>

<?php
include_once __DIR__ . '/../templates/barra.php';
?>
<h2>Buscar Citas</h2>
<?php 
        include_once __DIR__ ."/../templates/alertas.php";
?>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>"/>
        </div>
    </form>
</div>
<div class="citas-admin">
    <ul class="citas">
        <?php
        $idCita = 0;

        foreach ($citas as $key => $cita) {
            if ($idCita !== $cita->id) {
                $total=0;
        ?>
                <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                    <p>Email: <span><?php echo $cita->email; ?></span></p>
                    <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>
                    <H2>Servicios Contratados de la cita Nro:  <?php echo $cita->id; ?></H2>
            <?php
            }  // FIN DE IF
            $idCita = $cita->id;
            ?>
                <p class="servicio"><?php echo $cita->servicio ." ". 
                $cita->precio; 
                $total+=$cita->precio;
                ?></p>
                <?php 
                    $actual=$cita->id;
                    $proximo=$citas[$key+1]->id ?? 0;
                   if(esUltimo($actual,$proximo)){
                     ?>
                     <p class="total">Total:<span>$<?php echo $total ?></span></p>
                     <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                     </form>
                     <?php
                   }
        } // FIN DE FOREACH
            ?>
    </ul>
</div>
<?php 
    $script="<script src='build/js/buscador.js'></script>";
?>