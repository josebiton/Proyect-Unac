<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\SuperAdminModel;
use App\Models\UsuariosModel;

class Auth extends BaseController
{

    public function index()
    {
        helper(['form']);
        echo view('login');
    } 

    public function loginAcc()
    {
        $session = session();
        $userModel = new SuperAdminModel();

        $user = $this->request->getVar('usuario');
        $password = $this->request->getVar('contrasena');
        
        $data = $userModel->where('usuario', $user)->first();
        
        if($data){
            $pass = $data['contrasena'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $ses_data = [
                    'id_sa' => $data['id_sa'],
                    'nombre' => $data['nombre'],
                    'usuario' => $data['usuario'],
                    'tipo' => $data['tipo'],
                    'acceso' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/panel');

            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/login');
            }
        }else{
            $session->setFlashdata('msg', 'Usuario no existe.');
            return redirect()->to('/login');
        }
    }

    public function salir()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/'));
    }

       public function loginlocal()
    {
        // Obtiene los datos del formulario de inicio de sesión
        $nombre_usuario = $this->request->getPost('nombre_usuario');
        $contrasena = $this->request->getPost('contrasena');
        $token = $this->request->getPost('token'); // Obtiene el token del fronten
        // Verifica las credenciales del usuario
        $userModel = new UsuariosModel();
        $user = $userModel->where('nombre_usuario', $nombre_usuario)->first();
        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // Credenciales válidas
            // Puedes almacenar la información del usuario en la sesión o generar un token de acceso, según tus necesidades
            return $this->response->setJSON(['success' => true, 'message' => 'Autenticación exitosa', 'nombre_usuario' => $user['nombre_usuario']]);
        } else {
            // Credenciales inválidas
            return $this->response->setJSON(['success' => false, 'message' => 'Credenciales inválidas']);
        }
    }


//     public function loginlocal()
// {
//     // Obtiene los datos del formulario de inicio de sesión
//     $user = $this->request->getPost('user');
//     $password = $this->request->getPost('password');
//     $token = $this->request->getPost('token'); // Obtiene el token del frontend

//     // Verifica las credenciales del usuario
//     $userModel = new UsuariosModel();
//     $user = $userModel->where('user', $user)->first();

//     if ($user && password_verify($password, $user['password'])) {
//         // Credenciales válidas

//         // Obtén el nombre del usuario desde la tabla 'personas'
//       //  $personaModel = new PersonaModel();
//        // $persona = $personaModel->find($user['persona_id']); // Suponiendo que 'persona_id' es la llave foránea en la tabla 'users'

//         if ($persona) {
//             $nombreUsuario = $persona['nombres'];

//             // Puedes almacenar la información del usuario en la sesión o generar un token de acceso, según tus necesidades

//             return $this->response->setJSON(['success' => true, 'message' => 'Autenticación exitosa', 'nombreUsuario' => $nombreUsuario]);
//         } else {
//             // No se encontró la persona correspondiente al usuario
//             return $this->response->setJSON(['success' => false, 'message' => 'Error al obtener el nombre del usuario']);
//         }
//     } else {
//         // Credenciales inválidas
//         return $this->response->setJSON(['success' => false, 'message' => 'Credenciales inválidas']);
//     }
// }


}



