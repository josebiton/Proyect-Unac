<?php
namespace App\Models;
use CodeIgniter\Model;
class SucursalesModel extends Model{
    protected $table='sucursales';
    protected $primaryKey='idsucursal';
    protected $returnType='array';
    protected $allowedFields =[
      'su_nom', 
      'encargado', 
      'tienda_id', 
      'direc', 
      'celu', 
      'correo', 
      'h_inicio',
      'h_cierre', 
      'estado'
    ];

    public function getSucursales(){
        return $this->db->table('sucursales su')
        ->Where('su.estado',1)
        ->join('tiendas t','su.tiendas_id = t.id_datos')
        ->get()->getResultArray();
    }
 
    public function getId($id){
        return $this->db->table('sucursales su')
        ->Where('su.idsucursal',$id)
        ->Where('su.estado',1)
        ->join('tiendas t','su.tienda_id = t.id_datos')
        ->get()->getResultArray();
    }

    public function getTiendas(){
        return $this->db->table('tiendas t')
        ->Where('t.estado',1)
        ->get()->getResultArray();
    }

}