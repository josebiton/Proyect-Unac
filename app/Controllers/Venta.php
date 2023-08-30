<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\RegistrosModel;

class Venta extends BaseController
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
                    $model = new VentaModel();
                    $resultado = $model->getVenta();
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
                            "Detalles" => "no hay ventas"
                        );
                    }
                    return json_encode($data, true);
                } else {
                    $data = array(
                        "Status" => 404,
                        "Detalles" => "El token es incorrecto "
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
                    $model = new VentaModel();
                    $resultado = $model->getId($id);
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
                        "id_usuario" => $request->getVar("id_usuario"),
                        "id_cliente" => $request->getVar("id_cliente"),
                        "fecha_venta" => $request->getVar("fecha_venta"),
                        "total" => $request->getVar("total")

                    );
                    if (!empty($datos)) {
                        $validation->setRules([
                            "id_usuario" => 'required|integer',
                            "id_cliente" => 'required|integer',
                            "fecha_venta" => 'required|date',
                            "total" => 'required|decimal'

                        ]);
                        $validation->withRequest($this->request)->run();
                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = array("Status" => 404, "Detalle" => $errors);
                            return json_encode($data, true);
                        } else {
                            $datos = array(
                                "id_usuario" => $datos["id_usuario"],
                                "id_cliente" => $datos["id_cliente"],
                                "fecha_venta" => $datos["fecha_venta"],
                                "total" => $datos["total"]
                            );
                            $model = new VentaModel();
                            $curso = $model->insert($datos);
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
                            "id_usuario" => 'required|integer',
                            "id_cliente" => 'required|integer',
                            "fecha_venta" => 'required|date',
                            "total" => 'required|decimal'
                        ]);
                        $validation->withRequest($this->request)->run();
                        if ($validation->getErrors()) {
                            $errors = $validation->getErrors();
                            $data = array("Status" => 404, "Detalle" => $errors);
                            return json_encode($data, true);
                        } else {
                            $model = new VentaModel();
                            $resultado = $model->find($id);
                            if (is_null($resultado)) {
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "ventas no existe"
                                );
                                return json_encode($data, true);
                            } else {
                                $datos = array(
                                    "id_usuario" => $datos["id_usuario"],
                                    "id_cliente" => $datos["id_cliente"],
                                    "fecha_venta" => $datos["fecha_venta"],
                                    "total" => $datos["total"]

                                );
                                $model = new VentaModel();
                                $resultado  = $model->update($id, $datos);
                                $data = array(
                                    "Status" => 200,
                                    "Detalles" => "ventas actualizadas"
                                );
                                return json_encode($data, true);
                            }
                        }
                    } else {
                        $data = array(
                            "Status" => 404,
                            "Detalles" => "tienes errores "
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
                    $model = new VentaModel();
                    $resultado = $model->where('estado', 1)->find($id);
                    if (!empty($resultado)) {
                        $datos = array("estado" => 0);
                        $resultado = $model->update($id, $datos);
                        $data = array(
                            "status" => 200,
                            "Detalles" => "se ha eliminado las ventas"
                        );
                    } else {
                        $data = array(
                            "status" => 404,
                            "Detalles" => "No hay ventas que eliminar, bien!!"
                        );
                    }
                    return json_encode($data, true);
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
}
