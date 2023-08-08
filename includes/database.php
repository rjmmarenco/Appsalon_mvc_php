<?php

function conectarDB(): mysqli
{
   
   /* $servidor = 'localhost';
    $usuario = 'rmarenco';
    $password = 'rm123';
    $database = 'appsalon';
*/

    $cnn = null;
    // $db = new mysqli('localhost', 'root', '', 'bienesraices_crud');
    $db=new mysqli($_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        $_ENV['DB_NAME']);
    
        $db->set_charset('utf8');


    if (!$db) {
        echo "Error: No se pudo conectar a MySQL.";
        echo "errno de depuración: " . mysqli_connect_errno();
        echo "error de depuración: " . mysqli_connect_error();
        exit;
    }

    return $db;
}
