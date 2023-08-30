<?php
namespace App\Controllers;
use App\Models\TiendasModel;
use App\Models\RegistrosModel;
class Tiendas extends BaseController{
    public function index(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $superadmin = $model->where('estado', 1)->findAll();
        
        foreach($superadmin as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new TiendasModel();
                    $tend = $model->where('estado', 1)->findAll();
                    if(!empty($tend)){
                        $data = array(
                            "status" => 200,
                            "total de registros"=>count($tend),
                            "Detalles" => $tend
                        );
                    }
                    else{
                        $data = array(
                            "status" => 200,
                            "total de registros"=>count($tend),
                            "Detalles" => "no hay registros"
                        );
                    }
                    return json_encode($data, true);
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

    public function show ($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $superadmin = $model->where('estado', 1)->findAll();
        
        foreach($superadmin as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new TiendasModel();
                    $tend = $model->where('estado', 1)->find($id);
                    if(!empty($tend)){
                        $data = array(
                            "status" => 200,
                            "Detalles" => $tend
                        );
                    }
                    else{
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay registro"
                        );
                    }
                    return json_encode($data, true);
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

    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $superadmin = $model->where('estado', 1)->findAll();
        
        foreach($superadmin as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                        $datos = array(
                            "gerente"=>$request->getVar("gerente"),
                            "direccion"=>$request->getVar("direccion"),
                            "telefono"=>$request->getVar("telefono"),
                            "email"=>$request->getVar("email"),
                            "mision"=>$request->getVar("mision"),
                            "vision"=>$request->getVar("vision"),
                            "banner"=>$request->getVar("banner"),
                            "hora_inicio"=>$request->getVar("hora_inicio"),
                            "hora_cierre"=>$request->getVar("hora_cierre")
                        );
                        if(!empty($datos)){
                            $validation->setRules([
                                "r_social"=>'required|string|max_length[255]',
                                "ruc" =>'required|string|max_length[255]',
                                "gerente" =>'required|string|max_length[255]',
                                "direccion"=>'required|string|max_length[255]',
                                "telefono"=>'required|max_length[30]',
                                "email"=>'required|string|max_length[255]',
                                "mision"=>'required|string|max_length[500]',
                                "vision"=>'required|string|max_length[500]',
                                "banner"=>'required|string|max_length[500]',
                                "hora_inicio"=>'required|string|max_length[255]',
                                "hora_cierre"=>'required|string|max_length[255]'
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalle"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $datos = array(
                                    "r_social"=>$datos["r_social"],
                                    "ruc"=>$datos["ruc"],
                                    "gerente"=>$datos["gerente"],
                                    "direccion"=>$datos["direccion"],
                                    "telefono"=>$datos["telefono"],
                                    "email"=>$datos["email"],
                                    "mision"=>$datos["mision"],
                                    "vision"=>$datos["vision"],
                                    "banner"=>$datos["banner"],
                                    "hora_inicio"=>$datos["hora_inicio"],
                                    "hora_cierre"=>$datos["hora_cierre"]
                                );
                                $model = new TiendasModel();
                                $tend = $model->insert($datos);
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
        $superadmin = $model->where('estado', 1)->findAll();
        
        foreach($superadmin as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                        $datos = $this->request->getRawInput();
                        if(!empty($datos)){
                            $validation->setRules([
                                "r_social"=>'required|string|max_length[255]',
                                "ruc" => 'required|string|max_length[255]',
                                "gerente" => 'required|string|max_length[255]',
                                "direccion"=>'required|string|max_length[255]',
                                "telefono"=>'required|max_length[30]',
                                "email"=>'required|string|max_length[255]',
                                "mision"=>'required|string|max_length[500]',
                                "vision"=>'required|string|max_length[500]',
                                "banner"=>'required|string|max_length[500]',
                                "hora_inicio"=>'required|string|max_length[255]',
                                "hora_cierre"=>'required|string|max_length[255]'
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalle"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $model = new TiendasModel();
                                $tend = $model->find($id);
                                if(is_null($tend)){
                                    $data = array(
                                        "Status"=>404, 
                                        "Detalles"=>"registro no existe"
                                    );
                                    return json_encode ($data, true);
                                }else{
                                    $datos = array(
                                        "r_social"=>$datos["r_social"],
                                        "ruc"=>$datos["ruc"],
                                        "gerente"=>$datos["gerente"],
                                        "direccion"=>$datos["direccion"],
                                        "telefono"=>$datos["telefono"],
                                        "email"=>$datos["email"],
                                        "mision"=>$datos["mision"],
                                        "vision"=>$datos["vision"],
                                        "banner"=>$datos["banner"],
                                        "hora_inicio"=>$datos["hora_inicio"],
                                        "hora_cierre"=>$datos["hora_cierre"]
                                    );
                                    $model = new TiendasModel();
                                    $tend  = $model->update($id, $datos);
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
        $superadmin = $model->where('estado', 1)->findAll();
        
        foreach($superadmin as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new TiendasModel();
                    $tend = $model->where('estado',1)->find($id);
                    if(!empty($tend)){
                        $datos = array("estado"=>0);
                        $tend = $model->update($id, $datos);
                        $data = array(
                            "status" => 200,
                            "Detalles" => "se ha eliminado el registro"
                        );
                    }
                    else{
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay registros"
                        );
                    }
                    return json_encode($data, true);
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
    
}