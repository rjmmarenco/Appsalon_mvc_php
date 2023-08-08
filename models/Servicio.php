<?php
namespace Model;

class Servicio extends ActiveRecord{
    // base de datos 
    protected static $tabla='servicios';
    protected static $columnasDB=['id','nombre','precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args=[]){
        $this->id=$args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->precio=$args['precio'] ?? '0';
    }
    public function validar(){
        if(!$this->nombre){
            self::$alertas['error'][]='El Nombre del servicio es obligatorio';
        }
        if(!$this->precio){
            self::$alertas['error'][]='El Precio del servicio es obligatorio';
        }else if(!is_numeric( $this->precio) ){
            self::$alertas['error'][]='El Precio debe ser un numero valido';
        }else if($this->precio<=0){
            self::$alertas['error'][]='El Precio debe ser un numero mayor que cero';
        }
        return self::getAlertas();
    }
    
}