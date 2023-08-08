<?php 
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
use MVC\Router;

class APIController{
    public static function index(){
       $servicio=Servicio::all();
       echo json_encode($servicio);
    }
    public static function guardar(){
        //almacena la cita y devuelve el id
        $cita=new Cita($_POST);
        $rpta=$cita->guardar();
    
        $id=$rpta['id'];

        $idServicios=explode(",",$_POST['servicios']);     /// se extraen los servicios y se ponen en variable
        // se obtienen los servicios 1,2,3,4,.....

        foreach($idServicios as $idservicio ){
            $args=[
                'citaid'=>$id,
                'servicioid'=>$idservicio
            ]; // se crea la estructura y se manda a la clase
            $citaServicio=new CitaServicio($args);
            $citaServicio->guardar();
        }
        //almacena las cita y el servicio
        echo json_encode(['resultado'=>$rpta]);
    }
    public static function eliminar( Router $router){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id=$_POST['id'];
            $cita=Cita::find($id);
            $cita->eliminar();
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
    }
}