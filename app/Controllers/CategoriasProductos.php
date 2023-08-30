<?php

namespace App\Controllers;

use App\Models\CategoriasProductosModel;
use App\Models\RegistrosModel;

class CategoriasProductos extends BaseController
{
    public function index()
    {
        $request = \Config\Services::request();
        $headers = $request->getHeaders();
        $validation = \Config\Services::validation();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();

        //$data = [];

        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $model = new CategoriasProductosModel();
                    $categoria = $model->where('estado', 1)->findAll();

                    if (!empty($categoria)) {
                        $data = array(
                            "status" => 200,
                            "total de registros" => count($categoria),
                            // "data" => $categoria
                            "Detalles" => $categoria
                        );
                    } else {
                        $data = array(
                            "status" => 200,
                            "total de registros" => 0,
                            "message" => "No hay registros"
                        );
                    }

                   // return $this->response->setJSON($data);
                   return json_encode($data, true);  

                } else {
                    $data = array(
                        "status" => 404,
                        "message" => "El token es incorrecto o tu código está mal -_-"
                    );
                }
            } else {
                $data = array(
                    "status" => 404,
                    "message" => "No posee autorización"
                );
            }
        }

       // return $this->response->setJSON($data);
       return json_encode($data, true);  

    }

    
    public function show($id)
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();
    
        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $model = new CategoriasProductosModel();
                    $categoria = $model->where('estado', 1)->find($id);
                    if (!empty($categoria)) {
                        $data = array(
                            "status" => 200,
                            "Detalles" => $categoria
                        );
                    } else {
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay registro o tu código está mal -_-"
                        );
                    }
                    return json_encode($data, true);
                } else {
                    $data = array(
                        "Status" => 404,
                        "Detalles" => "El token es incorrecto o tu código está mal -_-"
                    );
                }
            } else {
                $data = array(
                    "Status" => 404,
                    "Detalles" => "No posee autorización"
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

        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $datos = array(
                        "nombre_categoria" => $request->getVar("nombre_categoria")
                    );

                    if (!empty($datos)) {
                        $validation->setRules([
                            "nombre_categoria" => 'required|string|max_length[255]'
                        ]);

                        $validation->withRequest($this->request)->run();

                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = array("status" => 404, "message" => $errors);
                            return json_encode($data, true);
                        } else {
                            $datos = array(
                                "nombre_categoria" => $datos["nombre_categoria"]
                            );
                            $model = new CategoriasProductosModel();
                            $categoria = $model->insert($datos);
                            $data = array(
                                "status" => 200,
                                "message" => "Registro exitoso"
                            );
                            return json_encode($data, true);
                        }
                    } else {
                        $data = array(
                            "status" => 404,
                            "message" => "Registro con errores"
                        );
                        return json_encode($data, true);
                    }
                } else {
                    $data = array(
                        "status" => 404,
                        "message" => "El token es incorrecto"
                    );
                }
            } else {
                $data = array(
                    "status" => 404,
                    "message" => "No posee autorización"
                );
            }
        }

        return json_encode($data, true);
    }

    public function update($id)
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();

        $found = false;

        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $found = true;

                    $datos = $this->request->getRawInput();

                    if (!empty($datos)) {
                        $validation->setRules([
                            "nombre_categoria" => 'required|string|max_length[255]'
                        ]);

                        $validation->withRequest($this->request)->run();

                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = array("status" => 404, "message" => $errors);
                            return json_encode($data, true);
                        } else {
                            $model = new CategoriasProductosModel();
                            $categoria = $model->find($id);

                            if (is_null($categoria)) {
                                $data = array(
                                    "status" => 404,
                                    "message" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            } else {
                                $datos = array(
                                    "nombre_categoria" => $datos["nombre_categoria"]
                                );
                                $model = new CategoriasProductosModel();
                                $model->update($id, $datos);
                                $data = array(
                                    "status" => 200,
                                    "message" => "Datos actualizados"
                                );
                                return json_encode($data, true);
                            }
                        }
                    } else {
                        $data = array(
                            "status" => 404,
                            "message" => "Registro con errores"
                        );
                        return json_encode($data, true);
                    }
                }
            }
        }

        if (!$found) {
            $data = array(
                "status" => 404,
                "message" => "No posee autorización"
            );
            return json_encode($data, true);
        }

        $data = array(
            "status" => 404,
            "message" => "No hay registros o tu código está mal -_-"
        );
        return json_encode($data, true);
    }

    public function delete($id)
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();

        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $model = new CategoriasProductosModel();
                    $categoria = $model->where('estado', 1)->find($id);
                    if (!empty($categoria)) {
                        $model->delete($id);
                        $data = array(
                            "status" => 200,
                            "message" => "Se ha eliminado el registro"
                        );
                    } else {
                        $data = array(
                            "status" => 404,
                            "message" => "No hay registros o tu código está mal -_-"
                        );
                    }
                    return json_encode($data, true);
                } else {
                    $data = array(
                        "status" => 404,
                        "message" => "El token es incorrecto o tu código está mal -_-"
                    );
                }
            } else {
                $data = array(
                    "status" => 404,
                    "message" => "No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }
}