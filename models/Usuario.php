<?php

namespace Model;

class Usuario extends ActiveRecord{
    // Base de datos
    protected static $tabla="usuarios";
    protected static $columnasDB=['id','nombre','apellido','email','password',
    'telefono','admin','confirmado','token'];

    // definicion de campos
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($arg=[]){

        $this->id=$arg['id'] ?? null;
        $this->nombre=$arg['nombre'] ?? '';
        $this->apellido=$arg['apellido'] ?? '';
        $this->email=$arg['email'] ?? '';
        $this->password=$arg['password'] ?? '';
        $this->telefono=$arg['telefono'] ?? '';
        $this->admin=$arg['admin'] ?? '0';
        $this->confirmado=$arg['confirmado'] ?? '0';
        $this->token=$arg['token'] ?? '';
    }
    
    // Mensaje para la validacion de la cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][]='El Nombre del cliente es Obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][]='El Apellido del cliente es Obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El email del cliente es Obligatorio';
        }
        if((!$this->password) or (strlen($this->password)<6)){
            self::$alertas['error'][]='El password del cliente es Obligatorio con al menos 6 Caracteres';
        }

        return self::$alertas;
    }

    public function existeUsuario(){
        $query="select * from ". self::$tabla ." as t where t.email= '". $this->email ."' limit 1";
        $resultado=self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][]='El Usuario ya esta registrado';
        }
        return $resultado;
        
    }
    public function hashPassword(){
        $this->password=password_hash($this->password,PASSWORD_BCRYPT);
    }
    public function crearToken(){
        $this->token=uniqid();
    }
    public function validarLogin(){
        if(empty($this->email)){
            self::setAlerta('error','El email es Obligatorio');
        }

        if(empty($this->password)){
            self::setAlerta('error','El password es Obligatorio');
        }
        
        return self::getAlertas();
    }

    public function validarEmail(){
        
        if(empty($this->email)){
            self::setAlerta('error','El email es Obligatorio');
        }
        return self::getAlertas();
    }


    public function comprobarClave($password){
        $resultado=password_verify($password,$this->password);
        $rpta=false;
  

        if($resultado){
            if(!$this->confirmado){
                Usuario::setAlerta('error','Usuario pendiente de confirmacion');
                $rpta=false;
            }else{
                $rpta=true;
            }
        }else {
            $rpta=false;
            Usuario::setAlerta('error','Claves no coinciden, Password Incorrecto');
        }
        return $rpta;
    }

    public function validarPassword(){
        if(is_null($this->password) || empty($this->password)){
            self::$alertas['error'][]='El Password es Obligatorio';
        }else if(strlen($this->password)<6){
            self::$alertas['error'][]='El password al menos debe ser de 6 Caracteres';
        }
        return self::$alertas;

    }

}