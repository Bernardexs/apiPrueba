<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\personadas;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'nombre' =>'required',
                'cedula' =>'required',
                'direccion' =>'required',
                'fechaNacimiento' =>'required',
                'idRol' =>'required'





               
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Existen campos vacios',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $persona = personadas::create([
                'nombre' => $request->nombre,
                'cedula' => $request->cedula,
                'direccion' => $request->direccion,
                'fechaNacimiento' => $request->fechaNacimiento,
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'idPersona' => $persona->id,
                'idRol' => $request->idRol,


            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function listUser(){
        $user=User::all();
        return response()->json([
            'status' => true,
            'usuarios' => $user,

        ],200);
    }
    public function destroy(Request $request, $id)
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Buscar el registro en la base de datos
        $registro = User::find($id);

        // Verificar si el registro existe
        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        $registro->delete();

        return response()->json(['message' => 'Registro eliminado correctamente'], 200);
    }
    public function update(Request $request, $id)
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Buscar el registro en la base de datos
        $registro = User::find($id);

        // Verificar si el registro existe
        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
            // Agrega aquí las reglas de validación para los campos que desees actualizar
        ]);

        // Actualizar el registro con los nuevos datos
        $registro->name = $request->input('name');
        $registro->email = $request->input('email');
        $registro->password = $request->input('password');
        // Actualiza aquí los demás campos que deseas actualizar

        $registro->save();

        return response()->json(['message' => 'Registro actualizado correctamente'], 200);
    }
    public function show($id)
    {
        // Buscar el registro en la base de datos por su ID
        $registro = User::find($id);

        // Verificar si el registro existe
        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        return response()->json(['data' => $registro], 200);
    }

}


