<?php
namespace App\Models;
use CodeIgniter\Model;
class ProductosModel extends Model{
    protected $table='productos';
    protected $primaryKey='idproducto';
    protected $returnType='array';
    protected $allowedFields =[
                'codigo', 
                'nombre_producto', 
                'descripcion', 
                'precio', 
                'stock',
                'imagen_url',
                'id_categoria',
                'id_unidad',
                'estado',
                'id_marca',
                'sucursales_Idsucursal',
                'sucursales_tiendas_id',];
   

    public function getProductos(){
        return $this->db->table('productos pr')
        ->Where('pr.estado',1)
        ->join('categoriasproductos cp','pr.id_categoria = cp.idcategoria')
        ->join('unidadesmedida um','pr.id_unidad = um.idunidad')
        ->join('marcas m','pr.id_marca = m.idmarca')
        ->join('sucursales s','pr.sucursales_Idsucursal = s.idsucursal')
        ->join('tiendas t','pr.sucursales_tiendas_id = t.id_datos')
        ->get()->getResultArray();
    }

    public function getId($id){
      return $this->db->table('productos pr')
      ->Where('pr.idproducto',$id)
      ->Where('pr.estado', 1)
      ->join('categoriasproductos cp','pr.id_categoria = cp.idcategoria')
      ->join('unidadesmedida um','pr.id_unidad = um.idunidad')
      ->join('marcas m','pr.id_marca = m.idmarca')
      ->join('sucursales s','pr.sucursales_Idsucursal = s.idsucursal')
      ->join('tiendas t','pr.sucursales_tiendas_id = t.id_datos')
      ->get()->getResultArray();
    }
    public function getCategoriasProductos(){
        return $this->db->table('categoriasproductos cp')
        ->Where('cp.estado',1)
        ->get()->getResultArray();
    }

    public function getUnidadesMedida(){
        return $this->db->table('unidadesmedida um')
        ->Where('um.estado',1)
        ->get()->getResultArray();
    }

    public function getMarcas(){
        return $this->db->table('marcas m ')
        ->Where('m.estado',1)
        ->get()->getResultArray();
    }

    public function getSucursales(){
        return $this->db->table('sucursales s')
        ->Where('s.estado',1)
        ->get()->getResultArray();
    }

    public function getTiendas(){
        return $this->db->table('tiendas t')
        ->Where('t.estado',1)
        ->get()->getResultArray();
    }
}