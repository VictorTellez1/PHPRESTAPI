<?php

namespace Controllers;

use Model\Cliente;
use Model\Curso;


class CursosController{
    public static function index(){
        $curso=new Curso;
        $resultado=$curso->comprobaciones($curso);
        if($resultado){
            $resultado=Curso::all();
            $json=array(
                "status"=>202,
                "detalle"=>$resultado
            );
            echo json_encode($json,true);
            return;
        }
        
    }
    public static function crear(){
        if($_SERVER['REQUEST_METHOD']==="POST"){

            $curso=new Curso;
            $cliente=new Cliente;
            $resultado=$curso->comprobaciones($curso);
            if($resultado){
                $curso->sincronizar($_POST);
                $cliente=$cliente->identificarUsuario($_SERVER['PHP_AUTH_USER']);
                $resultado=$curso->validar();
                if(!empty($resultado)){
                    $json=array(
                        "status"=>404,
                        "detalle"=>$resultado
                    );
                    echo json_encode($json,true);
                    return;
                }
                $resultado=$curso->comprobarNombre($curso);
                if($resultado){
                    $curso->instructor=$cliente->nombre." ".$cliente->apellido;
                    $curso->id_creador=$cliente->id;
                    $curso->created_at=date('Y-m-d h:i:s');
                    $curso->updated_at=date('Y-m-d h:i:s');

                    $resultado=$curso->guardar();
                    if($resultado){
                        $json=array(
                            "status"=>202,
                            "detalle"=>"Creado con exito"
                        );
                        echo json_encode($json,true);
                        return;
                    }
                }
                
                
            } 
        }

    }
    public static function mostrar(){
        $curso=new Curso;
        $cliente=new Cliente;
        $resultado=$curso->comprobaciones($curso);
        if($resultado){
            $resultado=$curso->validarId();
            if($resultado){
                $json=array(
                    "status"=>202,
                    "detalle"=>$resultado
                );
                echo json_encode($json,true);
                return;
            }
        }
        

    }
    public static function actualizar(){
        $curso=new Curso;
        $cliente=new Cliente;
        $resultado=$curso->comprobaciones($curso);
        if($resultado){
            if($_SERVER['REQUEST_METHOD']=="PUT"){
                $datos=[];
                $curso=$curso->validarId();
              
                if(!empty($curso)){
                    
                    $titulo=trim($curso->titulo);
                //Validar los datos
                    parse_str(file_get_contents('php://input'),$datos);
                    $curso->sincronizar($datos);
                    $resultado=$curso->validar();
                    if(!empty($resultado)){
                        $json=array(
                            "status"=>404,
                            "detalle"=>$resultado
                        );
                        echo json_encode($json,true);
                        return;
                    }
                    ;
                    if(!(trim($curso->titulo)===($titulo))){
                        $resultado=$curso->comprobarNombre($curso);
                    }
                    
                    //Comprobar que el que quiera actualizar sea el mismo que lo creo
                if(empty($resultado)){
                        $cliente=$_SERVER['PHP_AUTH_USER'];
                        $cliente=Cliente::where('id_cliente',$cliente);
                        if(!($cliente->id==$curso->id_creador)){
                            $json=array(
                                "status"=>404,
                                "detalle"=>"No puede modificar proyectos que no son tuyos"
                            );
                            echo json_encode($json,true);
                            return;
                        }
                        $respuesta=$curso->guardar();
                        if($respuesta){
                            $json=array(
                                "status"=>202,
                                "detalle"=>"Proyecto Modificado con exito"
                            );
                            echo json_encode($json,true);
                            return;
                        }
                }
               }
            }
        }
    }
    public static function delete(){
        $curso=new Curso;
        $cliente=new Cliente;
        $resultado=$curso->comprobaciones($curso);
        if($resultado){
            if($_SERVER['REQUEST_METHOD']=="DELETE"){
                $curso=$curso->validarId();
                if(!empty($curso)){
                    $cliente=$_SERVER['PHP_AUTH_USER'];
                    $cliente=Cliente::where('id_cliente',$cliente);
                    if(!($cliente->id==$curso->id_creador)){
                        $json=array(
                            "status"=>404,
                            "detalle"=>"No puede modificar proyectos que no son tuyos"
                        );
                        echo json_encode($json,true);
                        return;
                    }
                    $resultado=$curso->eliminar();
                    if($resultado){
                        $json=array(
                            "status"=>202,
                            "detalle"=>"Eliminado con exito"
                        );
                        echo json_encode($json,true);
                        return; 
                    }
                }  

            }


        }
    }

}