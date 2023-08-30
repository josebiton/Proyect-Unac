<?php

namespace App\Controllers;
use App\Models\ProductosModel;
use App\Models\RegistrosModel;

class Producto1 extends BaseController
{

    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
        // var_dump($registro); die; 
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value['cliente_id'].':'.$value['llave_secreta'])){
         
                        $datos = array(
                            "codigo" => $request->getVar("codigo"),
                            "nombre_producto" => $request->getVar("nombre_producto"),
                            "descripcion" => $request->getVar("descripcion"),
                            "precio" => $request->getVar("precio"),
                            "stock" => $request->getVar("stock"),
                            "id_categoria" => $request->getVar("id_categoria"),
                            "id_unidad" => $request->getVar("id_unidad"),
                            "id_marca" => $request->getVar("id_marca"),
                            "imagen_url" => $request->getFile("imagen_url"),
                            "sucursales_idsucursal" => $request->getVar("sucursales_idsucursal"),
                            "sucursales_tiendas_id" => $request->getVar("sucursales_tiendas_id")
                        );
                            $imagen = $datos['imagen_url'];   
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
                                "id_categoria" => 'required|integer',
                                "id_unidad" => 'required|integer',
                                "id_marca" => 'required|integer',
                                "sucursales_idsucursal" => 'required|integer',
                                "sucursales_tiendas_id" => 'required|integer'
                           
                            ]);


                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalles"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $newName = $imagen->getRandomName();
                                $datos = array(
                                    "codigo" => $datos["codigo"],
                                    "nombre_producto" => $datos["nombre_producto"],
                                    "descripcion" => $datos["descripcion"],
                                    "precio" => $datos["precio"],
                                    "stock" => $datos["stock"],
                                    "id_categoria" => $datos["id_categoria"],
                                    "id_unidad" => $datos["id_unidad"],
                                    "id_marca" => $datos["id_marca"],
                                    "sucursales_idsucursal" => $datos["sucursales_idsucursal"],
                                    "sucursales_tiendas_id" => $datos["sucursales_tiendas_id"],
                                    "imagen_url" => $newName                                
                                );
                                $model = new ProductosModel();
                                $producto = $model->insert($datos);
                                $data = array(
                                    "Status"=>200,
                                    "Detalles"=>"Registro existoso"
                                );
                               
                                //Crear carpeta
                                $nombreCarpeta = array(
                                    1 => "public/".$datos['categoria'],
                                    2 => "public/".$datos['categoria'].'/Productos',
                                    3 => "public/".$datos['categoria'].'/Logo'

                                );
                                foreach( $nombreCarpeta as $carpeta){
                                if (!is_dir($carpeta)) {
                                    
                                    if (mkdir($carpeta)) {
                                        $info = "Carpeta creada correctamente.";
                                    } else {
                                        $info = "Error al crear la carpeta.";
                                    }
                                } else {
                                    $info = "La carpeta ya existe.";
                                }

                                };
                                //aqui
                                //Subir archivo

                                if ($imagen->isValid() && !$imagen->hasMoved()) {
                                    // Directorio de destino
                                    $uploadPath = './public/'.$datos['categoria']."/imagen";
                        
                                    // Generar un nombre de archivo único
                        
                                    // Mover el archivo al directorio de destino
                                    $imagen->move($uploadPath, $newName);
                        
                                    // Enviar una respuesta JSON con la ubicación del archivo
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Archivo subido correctamente',
                                        'imagen$imagen_path' => base_url($uploadPath."/".$newName)
                                    ];
                        
                                    $up = $this->response->setJSON($response);
                                } else {
                                    // Si hay un error en la carga del archivo
                                    $response = [
                                        'status' => 'error',
                                        'message' => $imagen->getErrorString()
                                    ];
                                  $up =  $this->response->setJSON($response);
                                }
                                 
                                return json_encode($data, true);
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
                            $uploadPath = './public/' . $datos['categoria'] . "/imagen";
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
    


    
}
