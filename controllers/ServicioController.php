<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAdmin();

        $servicios=Servicio::all();
        $alertas=[];

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'alertas'=>$alertas,
            'servicios'=>$servicios
        ]);
    }


    public static function crear(Router $router)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAdmin();
        $servicio=new Servicio();
        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas=$servicio->validar();
            if(empty($alertas)){
                $servicio->guardar();
                $alertas=[];
                $alertas['exito'][]='Servicio Guardado correctamente';
                $router->render('servicios/index',[
                    'nombre' => $_SESSION['nombre'],
                    'alertas'=>$alertas
                ]);
                //header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]);



    }

    public static function actualizar(Router $router)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAdmin();
        $id=$_GET['id'];
        if(!is_numeric($id)){
            return;
        }
        $servicio= Servicio::find($id);
        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas=$servicio->validar();
            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]);

    }

    public static function eliminar(Router $router)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        isAdmin();
        $servicio=new Servicio();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id=$_POST['id'];
            if(!is_numeric($id)) return;
            $servicio=Servicio::find($id);
            
            $servicio->eliminar();
            $alertas=Servicio::getAlertas();
            $servicios=Servicio::all();
            $nombre='';

            if(!empty($alertas)){
                $router->render('/servicios/index', [
                    'alertas'=>$alertas,
                    'nombre'=>$nombre,
                    'servicios'=>$servicios
                ]);
            }else{
                header('Location: /servicios');
            }
        }
    }
}
