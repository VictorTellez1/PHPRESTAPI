<?php

namespace Controllers;

use Model\Cliente;


class ClientesController{
    public static function registro(){
        $errores=[];
        if($_SERVER['REQUEST_METHOD']==="POST"){
            $cliente=new Cliente;
          
            $cliente->sincronizar($_POST);
            $errores=$cliente->validarProyecto();
            if(!empty($errores)){
                $json=array(
                    "status"=>404,
                    "detalle"=>$errores
                );
                echo json_encode($json,true);
                return;    
            } 
            //Validar que el email no este repetido
            $resultado=Cliente::where('email',$cliente->email);
            if(!empty($resultado)){
                $json=array(
                    "status"=>404,
                    "detalle"=>"El correo ya esta siendo usado, pruebe con otro"
                );
                echo json_encode($json,true);
                return; 
            }
             //Generar credenciales del cliente
             $id_cliente = str_replace("$", "a", crypt($cliente->nombre.$cliente->apellido.$cliente->email, '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
	
		    $llave_secreta = str_replace("$", "o", crypt($cliente->email.$cliente->apellido.$cliente->nombre, '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
            
            //Generar el cliente
            $cliente->id_cliente=$id_cliente;
            $cliente->llave_secreta=$llave_secreta;
            $cliente->created_at=date('Y-m-d h:i:s');

            //Meterlo a la base de datos
            $resultado=$cliente->guardar();
            if($resultado){
                $json=array(
                    "status"=>202,
                    "detalle"=>"Guarde las siguiente crendeciales",
                    "id_cliente"=>$id_cliente,
                    "llave_secreta"=>$llave_secreta
                );
                echo json_encode($json,true);
                return; 
            }


        }
    }
    
        

    
}