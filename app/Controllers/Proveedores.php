<?php

namespace App\Controllers;

use App\Models\ProveedoresModel;
use App\Models\RegistrosModel;

class Proveedores extends BaseController
{

    public function index()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();

        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $model = new ProveedoresModel();
                    $resultado = $model->where('estado', 1)->findAll();
                    if (!empty($resultado)) {
                        $data = array(
                            "status" => 200,
                            "total de registros" => count($resultado),
                            "Detalles" => $resultado
                        );
                    } else {
                        $data = array(
                            "status" => 200,
                            "total de registros" => count($resultado),
                            "Detalles" => "no hay registros"
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
                    $model = new ProveedoresModel();
                    $resultado = $model->where('estado', 1)->find($id);
                    if (!empty($resultado)) {
                        $data = array(
                            "status" => 200,
                            "Detalles" => $resultado
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
                        "nombre_proveedor" => $request->getVar("nombre_proveedor"),
                        "direccion" => $request->getVar("direccion"),
                        "ruc" => $request->getVar("ruc"),
                        "razon_social" => $request->getVar("razon_social"),
                        "correo_proveedor" => $request->getVar("correo_proveedor"),
                        "telefono_proveedor" => $request->getVar("telefono_proveedor"),
                        "descripcion" => $request->getVar("descripcion"),
                    );

                    if (!empty($datos)) {
                        $validation->setRules([
                            "nombre_proveedor" => 'required|string|max_length[255]',
                            "direccion" => 'required|string|max_length[255]',
                            "ruc" => [
                                'label' => 'ruc',
                                'rules' => 'required|integer|max_length[11]|is_unique[proveedores.ruc]',
                                'errors' => [
                                    'is_unique' => 'El {field} ya está registrado. Por favor, ingrese otro ruc.',
                                ],
                            ],
                            "razon_social" => 'required|string|max_length[255]',
                            "correo_proveedor" => 'required|string|max_length[255]',
                            "telefono_proveedor" => 'required|max_length[9]',
                            "descripcion" => 'required|string|max_length[255]'
                        ]);

                        $validation->withRequest($this->request)->run();
                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = array("Status" => 404, "Detalle" => $errors);
                            return json_encode($data, true);
                        } else {
                            $datos = array(
                                "nombre_proveedor" => $datos["nombre_proveedor"],
                                "direccion" => $datos["direccion"],
                                "ruc" => $datos["ruc"],
                                "razon_social" => $datos["razon_social"],
                                "correo_proveedor" => $datos["correo_proveedor"],
                                "telefono_proveedor" => $datos["telefono_proveedor"],
                                "descripcion" => $datos["descripcion"]

                            );
                            $model = new ProveedoresModel();
                            $resultado = $model->insert($datos);
                            $data = array(
                                "Status" => 200,
                                "Detalle" => "Registro existoso"
                            );
                            return json_encode($data, true);
                        }
                    } else {
                        $data = array(
                            "Status" => 404,
                            "Detalle" => "Registro con errores"
                        );
                        return json_encode($data, true);
                    }
                } else {
                    $data = array(
                        "Status" => 404,
                        "Detalles" => "El token es incorrecto"
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

    public function update($id)
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('estado', 1)->findAll();

        foreach ($registro as $key => $value) {
            if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
                if ($request->getHeader('Authorization') == 'Authorization: Basic ' . base64_encode($value['cliente_id'] . ':' . $value['llave_secreta'])) {
                    $datos = $this->request->getRawInput();
                    if (!empty($datos)) {
                        $validation->setRules([
                            "nombre_proveedor" => 'required|string|max_length[255]',
                            "direccion" => 'required|string|max_length[255]',
                            "ruc" => [
                                'label' => 'ruc',
                                'rules' => 'required|integer|max_length[11]|is_unique[proveedores.ruc]',
                                'errors' => [
                                    'is_unique' => 'El {field} ya está registrado. Por favor, ingrese otro ruc.',
                                ],
                            ],
                            "razon_social" => 'required|string|max_length[255]',
                            "correo_proveedor" => 'required|string|max_length[255]',
                            "telefono_proveedor" => 'required|integer',
                            "descripcion" => 'required|string|max_length[255]'
                        ]);
                        $validation->withRequest($this->request)->run();
                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = array("Status" => 404, "Detalle" => $errors);
                            return json_encode($data, true);
                        } else {
                            $model = new ProveedoresModel();
                            $resultado = $model->find($id);
                            if (is_null($resultado)) {
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "registro no existe"
                                );
                                return json_encode($data, true);
                            } else {
                                $datos = array(
                                    "nombre_proveedor" => $datos["nombre_proveedor"],
                                    "direccion" => $datos["direccion"],
                                    "ruc" => $datos["ruc"],
                                    "razon_social" => $datos["razon_social"],
                                    "correo_proveedor" => $datos["correo_proveedor"],
                                    "telefono_proveedor" => $datos["telefono_proveedor"],
                                    "descripcion" => $datos["descripcion"]

                                );
                                $model = new ProveedoresModel();
                                $resultado  = $model->update($id, $datos);
                                $data = array(
                                    "Status" => 200,
                                    "Detalles" => "datos actualizados"
                                );
                                return json_encode($data, true);
                            }
                        }
                    } else {
                        $data = array(
                            "Status" => 404,
                            "Detalles" => "Registro con errores"
                        );
                        return json_encode($data, true);
                    }
                } else {
                    $data = array(
                        "Status" => 404,
                        "Detalles" => "El token es incorrecto"
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
                    $model = new ProveedoresModel();
                    $resultado = $model->where('estado', 1)->find($id);
                    if (!empty($resultado)) {
                        $datos = array("estado" => 0);
                        $resultado = $model->update($id, $datos);
                        $data = array(
                            "status" => 200,
                            "Detalles" => "se ha eliminado el registro"
                        );
                    } else {
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay registros o tu código está mal -_-"
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
}
