<?php 
namespace App\Models;
use CodeIgniter\Model;
class TiendasModel extends Model{
    protected $table = 'tiendas';
    protected $primaryKey = 'id_datos';
    protected $returnType = 'array';
    protected $allowedFields = [
        'r_social',
        'ruc',
        'gerente',
        'direccion',
        'telefono',
        'email',
        'mision',
        'vision',
        'banner',
        'hora_inicio',
        'hora_cierre',
        'logo',
        'password',
        'estado'
    ];

}