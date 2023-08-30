<?php 
namespace App\Models;

use CodeIgniter\Model;

class InventariosModel extends Model{
    protected $table      = 'inventarios';
    protected $primaryKey = 'idinventario';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'id_producto',
        'id_proveedor',
        'cantidad',
        'fecha',
        'estado'
    ];


    public function getInventarios(){
        return $this->db->table('inventarios i')
        ->where('i.estado',1)
        ->join('productos pr','i.id_producto = pr.idproducto')
        ->join('proveedores pe','i.id_proveedor = pe.idproveedor')
        ->get()->getResultArray();
    }

    public function getId($id){
        return $this->db->table('inventarios i')
        ->Where('i.idinventario', $id)
        ->where('i.estado', 1)
        ->join('productos pr','i.id_producto = pr.idproducto')
        ->join('proveedores pe','i.id_proveedor = pe.idproveedor')
        ->get()->getResultArray();
      }

    public function getProductos(){
        return $this->db->table('productos pr')
        ->where('pr.estado',1)
        ->get()->getResultArray();
    }

    public function getProveedores(){
        return $this->db->table('proveedores pe')
        ->where('pe.estado',1)
        ->get()->getResultArray();
    }

}