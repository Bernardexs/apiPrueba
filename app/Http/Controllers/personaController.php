<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\personadas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class personaController extends Controller
{
    public function mostrar(){
      
        $datos = DB::table('personadas')
            ->join('users', 'personadas.id', '=', 'users.idPersona')
            ->join('roles', 'users.idRol', '=', 'roles.id')
            ->select('users.name','roles.rol', 'personadas.*')->get();
            return response()->json(['data' => $datos], 200);
            
        
        

    }
    public function changeNameAndPassword(Request $request, $id)
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Buscar el registro en la base de datos
        $user = User::find($id);

        // Verificar si el usuario existe
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $request->validate([
            'name' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        // Verificar si la contraseña actual es correcta
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'Contraseña actual incorrecta'], 401);
        }

        // Cambiar el nombre y la contraseña del usuario
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json(['message' => 'Nombre y contraseña cambiados correctamente'], 200);
    }

}
