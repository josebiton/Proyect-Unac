<?php
namespace App\Controllers;
use App\Models\ProductosModel;
use App\Models\RegistrosModel;
class Productos extends BaseController{
    public function index(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
                    $model = new ProductosModel();
                    $producto = $model->getProductos();
                  
                    if(!empty($producto)){
                        $data = array(
                            "status" => 200,
                            "total de registros"=>count($producto),
                            "Detalles" => $producto
                     
                        );
                    }
                    else{
                        $data = array(
                            "status" => 200,
                            "total de registros"=>count($producto),
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
                    $model = new ProductosModel();
                    $producto = $model->getId($id);
                    if(!empty($producto)){
                        $data = array(
                            "status" => 200,
                            "Detalles" => $producto
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
    } //ejecutado

    public function create()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        
        $data = [
            "Status" => 404,
            "Detalles" => "No se pudo procesar la solicitud"
        ];
        
        foreach ($registro as $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $datos = [
                        "codigo" => $request->getVar("codigo"),
                        "nombre_producto" => $request->getVar("nombre_producto"),
                        "descripcion" => $request->getVar("descripcion"),
                        "precio" => $request->getVar("precio"),
                        "stock" => $request->getVar("stock"),
                      //  "url_imagen" => $request->getVar("url_imagen"),
                        "id_categoria" => $request->getVar("id_categoria"),
                        "id_unidad" => $request->getVar("id_unidad"),
                        "id_marca" => $request->getVar("id_marca"),
                        "sucursales_idsucursal" => $request->getVar("sucursales_idsucursal"),
                        "sucursales_tiendas_id" => $request->getVar("sucursales_tiendas_id")

                    ];
    
                    if (!empty($datos)) {
                        $validation->setRules([
                            "codigo" => [
                                'label' => 'Código',
                                'rules' => 'required|integer|max_length[50]|is_unique[productos.codigo]',
                                'errors' => [
                                    'is_unique' => 'El {field} ya está en uso. Por favor, ingrese un código único.',
                                ],
                            ],
                            "nombre_producto" => 'required|string|max_length[100]',
                            "descripcion" => 'required|string|max_length[255]',
                            "precio" => 'required|decimal',
                            "stock" => 'required|integer|max_length[50]',
                            "id_categoria" => 'required|integer',
                            "id_unidad" => 'required|integer',
                            "id_marca" => 'required|integer',
                            "sucursales_idsucursal" => 'required|integer',
                            "sucursales_tiendas_id" => 'required|integer'
                        ]);
    
                        $validation->withRequest($this->request)->run();
                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = [
                                "Status" => 404,
                                "Detalle" => $errors
                            ];
                            return json_encode($data, true);
                        } else {
                            $imagen = $request->getFile("imagen_url");
                            $newName = $imagen->getRandomName();
    
                            // Procesar y validar la imagen adjunta
                            $uploadPath = './public/'. $datos['id_categoria'] . "/imagen";
                            if (!$this->validate([
                                'imagen_url' => [
                                    'uploaded[imagen_url]',
                                    'mime_in[imagen_url,image/jpg,image/jpeg,image/png]',
                                    'max_size[imagen_url,1024]',
                                ]
                            ])) {
                                $data = [
                                    "Status" => 404,
                                    "Detalle" => $this->validator->getErrors()
                                ];
                                return json_encode($data, true);
                            }
    
                            // Crear directorio si no existe
                            if (!is_dir($uploadPath)) {
                                if (!mkdir($uploadPath, 0777, true)) {
                                    $data = [
                                        "Status" => 404,
                                        "Detalle" => "Error al crear la carpeta de imágenes."
                                    ];
                                    return json_encode($data, true);
                                }
                            }
    
                            // Mover la imagen al directorio de destino
                            $imagen->move($uploadPath, $newName);
    
                            $datos["imagen_url"] = $newName;
    
                            // Continuar con el procesamiento y almacenamiento del registro
    
                            $model = new ProductosModel();
                            $producto = $model->insert($datos);
                            $data = [
                                "Status" => 200,
                                "Detalle" => "Registro exitoso"
                            ];
                            return json_encode($data, true);
                        }
                    } else {
                        $data = [
                            "Status" => 404,
                            "Detalles" => "Registro con errores"
                        ];
                        return json_encode($data, true);
                    }
                } else {
                    $data = [
                        "Status" => 404,
                        "Detalles" => "El token es incorrecto"
                    ];
                }
            } else {
                $data = [
                    "Status" => 404,
                    "Detalles" => "No posee autorización"
                ];
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
                                "codigo" => [
                                    'label' => 'Código',
                                    'rules' => 'required|integer|max_length[50]|is_unique[productos.codigo]',
                                    'errors' => [
                                        'is_unique' => 'El {field} ya está en uso. Por favor, ingrese un código único.',
                                    ],
                                ],
                                "nombre_producto" => 'required|string|max_length[100]',
                                "descripcion" => 'required|string|max_length[255]',
                                "precio" => 'required|decimal',
                                "stock" => 'required|integer|max_length[50]',
                                "imagen_url" => 'required|string|max_length[255]',
                                "id_categoria" => 'required|integer',
                                "id_unidad" => 'required|integer',
                                "id_marca" => 'required|integer'
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalle"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $model = new ProductosModel();
                                $producto = $model->find($id);
                                if(is_null($producto)){
                                    $data = array(
                                        "Status"=>404, 
                                        "Detalles"=>"registro no existe"
                                    );
                                    return json_encode ($data, true);
                                }else{
                                    $datos = array(
                                        "codigo"=>$datos["codigo"],
                                        "nombre_producto"=>$datos["nombre_producto"],
                                        "descripcion"=>$datos["descripcion"],
                                        "precio"=>$datos["precio"],
                                        "stock"=>$datos["stock"],
                                        "imagen_url"=>$datos["imagen_url"],
                                        "id_categoria"=>$datos["id_categoria"],
                                        "id_unidad"=>$datos["id_unidad"],
                                        "id_marca"=>$datos["id_marca"]
                                    );
                                    $model = new ProductosModel();
                                    $producto  = $model->update($id, $datos);
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
                    $model = new ProductosModel();
                    $producto = $model->where('estado',1)->find($id);
                    if(!empty($producto)){
                        $datos = array("estado"=>0);
                        $producto = $model->update($id, $datos);
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