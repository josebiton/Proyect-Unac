<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProveedoresModel extends Model{
    protected $table      = 'proveedores';
    protected $primaryKey = 'idproveedor';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'nombre_proveedor',
        'direccion',
        'ruc',
        'razon_social',
        'correo_proveedor',
        'telefono_proveedor',
        'descripcion',
        'estado'
    ];
    
}