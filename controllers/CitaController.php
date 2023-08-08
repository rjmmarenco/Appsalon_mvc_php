<?php

namespace Controllers;

use MVC\Router;

class CitaController
{
    public static function index(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        }
        isAuth();
        $cliente = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
        $router->render('cita/index', [
            'nombre' => $cliente,
            'id' => $_SESSION['id']
        ]);
    }
}
