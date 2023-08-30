<?php
namespace App\Controllers;

use App\Models\UnidadesMedidaModel;
use App\Models\RegistrosModel;


class UnidadesMedida extends BaseController{
    // public function index(){
    //     $request = \Config\Services::request();
    //     $validation = \Config\Services::validation();
    //     $headers = $request->getHeaders();
    //     $model = new RegistrosModel();
    //     $registro = $model->where('estado', 1)->findAll();
        
    //     foreach($registro as $key=>$value){
    //         if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
    //             if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
    //                 $model = new UnidadesMedidaModel();
    //                 $resultado = $model->where('estado', 1)->findAll();
    //                 if(!empty($resultado)){
    //                     $data = array(
    //                         "status" => 200,
    //                         "total de registros"=>count($resultado),
    //                         "Detalles" => $resultado
    //                     );
    //                 }
    //                 else{
    //                     $data = array(
    //                         "status" => 200,
    //                         "total de registros"=>count($resultado),
    //                         "Detalles" => "no hay registros"
    //                     );
    //                 }
    //                 return json_encode($data, true);
    //             }
    //             else{
    //                 $data = array(
    //                     "Status"=>404,
    //                     "Detalles"=>"El token es incorrecto o tu código está mal -_-"
    //                 );
    //             }
    //         }
    //         else{
    //             $data = array(
    //                 "Status"=>404,
    //                 "Detalles"=>"No posee autorización"
    //             );
    //         }
    //     }
    //     return json_encode($data, true);
    // }

public function index(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new UnidadesMedidaModel();
                    $unidad = $model->where('estado', 1)->findAll();
                  
                    if(!empty($unidad)){
                        $data = array(
                            "status" => 200,
                            "total de registros"=>count($unidad),
                            "Detalles" => $unidad
                     
                        );
                    }
                    else{
                        $data = array(
                            "status" => 200,
                            "total de registros"=>count($unidad),
                            "Detalles" => "no hay registros"
                        );
                    }
                    return json_encode($data, true);
                    
                    
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto o tu código está mal -_-"
                    );
                }
                
            }
            
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);  
    }
    
    
    


    public function show ($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new UnidadesMedidaModel();
                    $resultado = $model->where('estado', 1)->find($id);
                    if(!empty($resultado)){
                        $data = array(
                            "status" => 200,
                            "Detalles" => $resultado
                        );
                    }
                    else{
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay registro o tu código está mal -_-"
                        );
                    }
                    return json_encode($data, true);
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto o tu código está mal -_-"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }

    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                        $datos = array(
                            "nombre_unidad"=>$request->getVar("nombre_unidad"),
                            "abreviatura"=>$request->getVar("abreviatura")
                            
                        );
                        if(!empty($datos)){
                            $validation->setRules([
                                "nombre_unidad"=>'required|string|max_length[255]',
                                "abreviatura"=>'required|string|max_length[10]'
                               
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalle"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $datos = array(
                                    "nombre_unidad"=>$datos["nombre_unidad"],
                                    "abreviatura"=>$datos["abreviatura"]
                                   
                                );
                                $model = new UnidadesMedidaModel();
                                $resultado = $model->insert($datos);
                                $data = array(
                                    "Status"=>200,
                                    "Detalle"=>"Registro existoso"
                                );
                                return json_encode($data, true);
                            }
                        }
                        else{
                            $data = array(
                                "Status"=>404,
                                "Detalle"=>"Registro con errores"
                            );
                            return json_encode($data, true);
                        }
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }

    public function update($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                        $datos = $this->request->getRawInput();
                        if(!empty($datos)){
                            $validation->setRules([
                                "nombre_unidad"=>'required|string|max_length[50]',
                                "abreviatura"=>'required|string|max_length[10]'
                                
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalle"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $model = new UnidadesMedidaModel();
                                $resultado = $model->find($id);
                                if(is_null($resultado)){
                                    $data = array(
                                        "Status"=>404, 
                                        "Detalles"=>"registro no existe"
                                    );
                                    return json_encode ($data, true);
                                }else{
                                    $datos = array(
                                        "nombre_unidad"=>$datos["nombre_unidad"],
                                        "abreviatura"=>$datos["abreviatura"]
                                        
                                    );
                                    $model = new UnidadesMedidaModel();
                                    $resultado  = $model->update($id, $datos);
                                    $data = array(
                                        "Status"=>200, 
                                        "Detalles"=>"datos actualizados"
                                    );
                                    return json_encode ($data, true);
                                }
                            }
                        }
                        else{
                            $data = array(
                                "Status"=>404,
                                "Detalles"=>"Registro con errores"
                            );
                            return json_encode($data, true);
                        }
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }

    public function delete($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new UnidadesMedidaModel();
                    $resultado = $model->where('estado',1)->find($id);
                    if(!empty($resultado)){
                        $datos = array("estado"=>0);
                        $resultado = $model->update($id, $datos);
                        $data = array(
                            "status" => 200,
                            "Detalles" => "se ha eliminado el registro"
                        );
                    }
                    else{
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay registros o tu código está mal -_-"
                        );
                    }
                    return json_encode($data, true);
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto o tu código está mal -_-"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }
    
}