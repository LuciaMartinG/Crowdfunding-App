<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    function updateRoleUser(Request $request){ // $request me permite acceder a los datos de la petición, similar $_POST

        $id = $request->input('id');

        $user = User::find($id);

        $user->role = $request->input('role');
       

        $user->save();

        return $user;

    }

    public function updateUser(Request $request)
{
    // Obtener el ID del usuario desde el request
    $id = $request->input('id');

    // Validar los datos de la solicitud
    $validatedData = $request->validate([
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255|unique:users,email,' . $id, // Validar el correo único excepto para el usuario mismo
        'password' => 'nullable|string|min:8', // Validar la contraseña, si es proporcionada
        'photo' => 'nullable|string|max:2048', // Validar la foto, si es proporcionada
    ]);

    // Buscar el usuario en la base de datos
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }

    // Actualizar los datos del usuario
    $user->name = $request->input('name', $user->name);  // Si no se proporciona un nuevo nombre, se mantiene el actual
    $user->email = $request->input('email', $user->email); // Si no se proporciona un nuevo correo, se mantiene el actual

    // Actualizar la foto si se proporciona
    $user->photo = $request->input('photo', $user->photo); // Si no se proporciona una nueva foto, se mantiene la actual

    // Actualizar la contraseña solo si se proporciona
    if ($request->has('password') && !empty($request->input('password'))) {
        $user->password = bcrypt($request->input('password')); // Encriptar la contraseña si se proporciona una nueva
    }

    // Guardar los cambios
    $user->save();

    return $user;
}

    
    
    
}
