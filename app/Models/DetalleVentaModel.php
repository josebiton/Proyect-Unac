<?php 
namespace App\Models; 

use CodeIgniter\Model;

class DetalleVentaModel extends Model{
    protected $table      = 'detallesventa';
    protected $primaryKey = 'iddetalles_venta';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'estado'
    ];

    public function getDetalleVenta(){
        return $this->db->table('detallesventa i')
        ->where('i.estado',1)
        ->join('productos pr','i.id_producto = pr.idproducto')
        ->get()->getResultArray();
    }

    public function getId($id){
        return $this->db->table('detallesventa i')
        ->Where('i.iddetalles_venta', $id)
        ->where('i.estado', 1)
        ->join('productos pr','i.id_producto = pr.idproducto')
        ->get()->getResultArray();
      }

    public function getProductos(){
        return $this->db->table('productos pr')
        ->where('pr.estado',1)
        ->get()->getResultArray();
    }


}