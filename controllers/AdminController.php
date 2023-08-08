<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        if (!isset($_SESSION)) {
            session_start();
        }
        isAdmin();
        
        // consultar la base de datos
        $fecha=$_GET['fecha'] ?? date('Y-m-d');
        $fechas=explode('-',$fecha);
        if(!checkdate($fechas[1],$fechas[2],$fechas[0])){
            header('Location: /404');
        }


        $consulta="select ";
        $consulta.=" c.citaid as id,";
        $consulta.=" c2.hora,";
        $consulta.=" concat(u.nombre ,' ', u.apellido) as cliente,";
        $consulta.=" u.email,";
        $consulta.=" u.telefono,";
        $consulta.=" s.nombre as servicio,"; 
        $consulta.=" s.precio";
        $consulta.=" from citaservicios c ";
        $consulta.=" inner join servicios s on s.id =c.servicioid ";
        $consulta.=" inner join citas c2 on c.citaid=c2.ID ";
        $consulta.=" inner join usuarios u on c2.usuarioid =u.id";
        $consulta.=" where c2.fecha='{$fecha}' ";

       // $sp="usp_citas_csv_por_fecha";
       // $citas=AdminCita::executeCommand($sp,$fecha);
       
       
        $citas=AdminCita::SQL($consulta);
        $alertas=[];
        if(count($citas)===0){
            $alertas['error'][]='No hay citas en la Fecha Seleccionada';
        }

        $nombre=$_SESSION['nombre'] ?? '';
        $router->render('admin/index',[
            'nombre'=>$nombre,
            'citas'=>$citas,
            'fecha'=>$fecha,
            'alertas'=>$alertas
        ]);
        
    }
}