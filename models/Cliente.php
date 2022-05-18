<?php

namespace Model;
use Model\ActiveRecord;
class Cliente extends ActiveRecord{
    protected static $tabla='clientes';
    protected static $columnasDB=['id','nombre','apellido','email','id_cliente','llave_secreta','created_at'];

    public function __construct($args=[])
    {
        $this->id=$args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->apellido=$args['apellido'] ?? '';
        $this->email=$args['email'] ?? '';
        $this->id_cliente=$args['id_cliente'] ?? '';
        $this->llave_secreta=$args['llave_secreta'] ?? '';
        $this->created_at=$args['created_at'] ?? '';
    }
    public function validarProyecto(){
        if(!$this->nombre){
            self::$alertas['error'][]='El nombre del cliente es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][]='El apellido del cliente es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El email del cliente es obligatorio';
        }
        if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$this->email))
        {
            self::$alertas['error'][]='EIntroducir un formato de correo valido';
        }
        return self::$alertas;
    }
    public function identificarUsuario($user){
        $resultado=Cliente::where('id_cliente',$user);
        return $resultado;
    }
}