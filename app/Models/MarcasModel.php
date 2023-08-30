<?php
namespace App\Models;
use CodeIgniter\Model;
class MarcasModel extends Model{
    protected $table='marcas';
    protected $primaryKey='idmarca';
    protected $returnType='array';
    protected $allowedFields =['nombre_marca','estado'];

}