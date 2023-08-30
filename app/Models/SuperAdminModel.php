<?php

namespace App\Models;

use CodeIgniter\Model;

class SuperAdminModel extends Model
{
    protected $table      = 'superadmin';
    protected $primaryKey = 'id_sa';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre', 'apellido', 'usuario', 'correo_electronico', 'contrasena', 'perfil', 'tipo', 'estado'];


    public function getSuperAdmin($usuario, $contrasena)
    {
        return $this->where('usuario', $usuario)
                    ->where('contrasena', $contrasena)
                    ->first();
    }
}    
