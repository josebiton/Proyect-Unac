<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table      = 'ventas';
    protected $primaryKey = 'idventa';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'id_usuario',
        'id_cliente',
        'fecha_venta',
        'total',
        'estado'
    ];


    public function getVenta()
    {
        return $this->db->table('ventas v')
            ->Where('c.estado', 1)
            ->join('usuarios u', 'v.id_usuario = u.idusuario')
            ->join('clientes c', 'v.id_cliente = c.idcliente')

            ->get()->getResultArray();
    }

    public function getId($id)
    {
        return $this->db->table('ventas v')
            ->Where('v.idventa', $id)
            ->Where('v.estado', 1)
            ->join('usuarios u', 'v.id_usuario = u.idusuario')
            ->join('clientes c', 'v.id_cliente = c.idcliente')
            ->get()->getResultArray();
    }

    public function getClientes()
    {
        return $this->db->table('clientes c')
            ->Where('c.estado', 1)
            ->get()->getResultArray();
    }

    public function getUsuarios()
    {
        return $this->db->table('usuarios u')
            ->Where('u.estado', 1)
            ->get()->getResultArray();
    }
}
