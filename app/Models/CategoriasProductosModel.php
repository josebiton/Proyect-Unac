<?php
namespace App\Models;
use CodeIgniter\Model;
class CategoriasProductosModel extends Model{
    protected $table='categoriasproductos';
    protected $primaryKey='idcategoria';
    protected $returnType='array';
    protected $allowedFields =['nombre_categoria', 'estado'];

}