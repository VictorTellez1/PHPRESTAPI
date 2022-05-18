<?php

namespace Model;
use Model\ActiveRecord;
class Curso extends ActiveRecord{
    protected static $tabla='cursos';
    protected static $columnasDB=['id','titulo','descripcion','instructor','imagen','precio'
    ,'id_creador','created_at','updated_at'];

    public function __construct($args=[])
    {
        $this->id=$args['id'] ?? null;
        $this->titulo=$args['titulo'] ?? '';
        $this->descripcion=$args['descripcion'] ?? '';
        $this->instructor=$args['instructor'] ?? '';
        $this->imagen=$args['imagen'] ?? '';
        $this->precio=$args['precio'] ?? '';
        $this->id_creador=$args['id_creador'] ?? '';
        $this->created_at=$args['created_at'] ?? '';
        $this->updated_at=$args['updated_at'] ?? '';
    }
    public function validar(){
        if(!$this->titulo){
            self::$alertas['error'][]='El titulado del curso es obligatorio';

        }
        if(!$this->descripcion){
            self::$alertas['error'][]='La descripcion del curso es obligatorio';
            
        }
        if(!$this->imagen){
            self::$alertas['error'][]='La imagen del curso es obligatorio';
            
        }
        if(!$this->precio){
            self::$alertas['error'][]='El precio del curso es obligatorio';
            
        }
        if(!is_numeric($this->precio)){
            self::$alertas['error'][]='El precio del curso tiene que ser numerico';
        }
        return self::$alertas;
    }
    public function logeado(){
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
            return $_SERVER['PHP_AUTH_USER'];
        }
    }
    public function comprobarNombre($curso){
        $resultado=Curso::where('titulo',$curso->titulo);
        if($resultado){
            $json=array(
                "status"=>404,
                "detalle"=>"El titulo que colocaste ya esta en uso, intenta con otro"
            );
            echo json_encode($json,true);
            return true;
        }
        
        
    }
    public function comprobaciones($curso){
        //Comprobar que el usuario este logeado
        $resultado=$curso->logeado();
        if(!$resultado){
                $json=array(
                    "status"=>404,
                    "detalle"=>"Necesita estar autenticado para ver esa informacion"
                );
                echo json_encode($json,true);
                return;
            }
            //Comprobar que exista el usuario
            $consulta=Cliente::where('id_cliente',$resultado);
            if(!$consulta){
                $json=array(
                    "status"=>404,
                    "detalle"=>"El usuario que usaste no existe, registrate"
                );
                echo json_encode($json,true);
                return;
            }
            //Comprobar su password
            $a=($_SERVER['PHP_AUTH_PW']);
            $b=($consulta->llave_secreta);
            if(!($a===$b)){
                $json=array(
                    "status"=>404,
                    "detalle"=>"El password es incorrecto,vuelva a intentarlo"
                );
                echo json_encode($json,true);
                return;
            }
            return true;
    }
    public function validarId(){
        if(empty($_SERVER["QUERY_STRING"])){
                
            $json=array(
                "status"=>404,
                "detalle"=>"Necesita agregar un ID de la siguiente forma ?={valor}"
            );
            echo json_encode($json,true);
            return;
        }
        $id=(explode("=",$_SERVER["QUERY_STRING"]));
        $id=$id[1];
        $id=filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            $json=array(
                "status"=>404,
                "detalle"=>"Valor no valido para ID"
            );
            echo json_encode($json,true);
            return;
        }
        $resultado=Curso::where('id',$id);
        if(!$resultado){
            $json=array(
                "status"=>404,
                "detalle"=>"ID no encontrado, prueba con otro"
            );
            echo json_encode($json,true);
            return;
        }
        return $resultado;
    }
    public function comprobarIdIgual(){

    }
    

}