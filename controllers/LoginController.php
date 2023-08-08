<?php
namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){

        $alertas=[];
        $auth=new Usuario();

        if($_SERVER['REQUEST_METHOD']==='POST'){

            $auth=new Usuario($_POST);
            $alertas=$auth->validarLogin();
           

            if(empty($alertas)){
                $usuario=Usuario::where('email',$auth->email);
                
                if($usuario){
                    // verificar passwor
                    $rpta=$usuario->comprobarClave($auth->password);
               

                    if($rpta){
                        // crear la sesion
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['nombre']=$usuario->nombre;
                        $_SESSION['apellido']=$usuario->apellido;
                        $_SESSION['login']=true;
                        if($usuario->admin==='1'){
                            //debuguear('Es Admin');
                            $_SESSION['admin']=$usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                           // debuguear('Es Cliente');
                           header('Location: /cita');
                        }
                    }
                }else{
                    Usuario::setAlerta('error','El usuario '. $auth->email .' no se Encontrado');
                }

            }
        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas'=>$alertas,
            'auth'=>$auth
        ]);
    
    }

    public static function logout(){
        session_start();
        $_SESSION=[];
        header('Location: /');
    }
   
    public static function forgotten(Router $router){

        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarEmail();
            if(empty($alertas)){
                $usuario=Usuario::where('email',$auth->email);
                
                if($usuario && $usuario->confirmado=='1') {
                    // el correo exisrte y esta confirmado
                    $usuario->crearToken();
                    $usuario->guardar();

                    // enviar el email
                    $email=new Email($usuario->nombre,$usuario->email,$usuario->token);
                    $email->enviarInstrucciones();

                    // poner alerta
                    Usuario::setAlerta('exito','REVISA TU EMAIL, se envio correo de restablecer');


                }else{
                    Usuario::setAlerta('error','Usuario no existe o no esta confirmado');
                }
            }
           
        }
        $alertas=Usuario::getAlertas();

        $router->render('auth/forgotten-pwd',[
            'alertas'=>$alertas
        ]);
    
    
    }
    
    public static function recuperarPassword(Router $router){
        
        $alertas=[];
        $error=false;
        $token=s($_GET['token']) ?? "";
        
        // buscar usuario con su token
        $usuario=Usuario::where('token',$token);
       
        if(empty($usuario) || (is_null($usuario))){
            Usuario::setAlerta('error','Token no valido');
            $error=true;
        }
       
        if($_SERVER['REQUEST_METHOD']==='POST'){
            // SE GUARDA EN NUEVO PASSWORD
            $password=new Usuario($_POST);
            $alertas=$password->validarPassword();
            if(empty($alertas)){
                $usuario->password=null;
                $usuario->password=$password->password;
                $usuario->hashPassword();
                $usuario->token=null;
                $resultado=$usuario->guardar();
                if($resultado){
                    header('Location: /');   // redireccionar para login
                }else{
                    //ocurrio un error
                }
            }
        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/recuperarPassword',[
            'alertas'=>$alertas,
            'error'=> $error
        ]);

    }
    
    public static function crear(Router $router){
        $usuario= new Usuario;
        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validarNuevaCuenta();

            if(empty($alertas)){
                // validar si el usuario ya existe  mandarlo a recuperar su clave
                // la cuenta ya existe...
                $resultado=$usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas=Usuario::getAlertas();
                }else {
                    //hashear el password
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    
                    //enviar email con el token
                    $email= new Email($usuario->nombre,$usuario->email,$usuario->token);
                    $email->enviarConfirmacion();
                    $resultado=$usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }

                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function confirmar(Router $router){
        $alertas=[];
        $token=$_GET['token'];
        $token=s($token);
        $usuario=Usuario::where('token',$token);
  
        if(empty($usuario)){
            // Mostrar Mensaje de Error;
            Usuario::setAlerta('error','Token no valido');
        }else{
            // modificar el usuario a confirmado;
            Usuario::setAlerta('exito','Token valido');
            $usuario->confirmado=1;
            $usuario->token='';
            $usuario->guardar();
        }
        $alertas=Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
}