<?php
namespace App\Models;
use CodeIgniter\Model;
class UnidadesMedidaModel extends Model{
    protected $table='unidadesmedida';
    protected $primaryKey='idunidad';
    protected $returnType='array';
    protected $allowedFields =['nombre_unidad', 'abreviatura',  'estado'];

}