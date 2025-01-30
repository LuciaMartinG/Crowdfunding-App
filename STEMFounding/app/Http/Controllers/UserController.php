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


    // Buscar el usuario en la base de datos
    $user = User::find($id);


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

    public function updateBalance(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'id' => 'required|exists:users,id', // Asegura que el ID exista en la tabla de usuarios
            'amount' => 'required|numeric|min:0', // El monto debe ser un número positivo
            'transaction_type' => 'required|in:deposit,withdrawal', // Solo permite "deposit" o "withdrawal"
        ]);

        // Obtener el usuario por ID
        $user = User::find($request->input('id'));

        // Lógica para el depósito o retiro
        $amount = $request->input('amount');
        if ($request->input('transaction_type') === 'deposit') {
            $user->balance += $amount; // Sumar al balance
        } elseif ($request->input('transaction_type') === 'withdrawal') {
            if ($user->balance < $amount) {
                return response()->json(['error' => 'Insufficient balance for withdrawal.'], 400);
            }
            $user->balance -= $amount; // Restar del balance
        }

        // Guardar los cambios
        $user->save();

        return $user;

    }

    public function toggleBan(Request $request)
{
    // Obtener un solo usuario por su ID
    $user = User::find($request->id);

    // Alternar el estado de baneado del usuario
    if ($user->banned) {
        $user->banned = false;  // Desbanear
        $message = 'Usuario desbaneado correctamente.';
    } else {
        $user->banned = true;   // Bannear
        $message = 'Usuario baneado correctamente.';
    }

    $user->save();  // Guardar el estado actualizado del usuario

    return $user;


}

public function listUsers(Request $request)
{
    $banned = $request->input('banned');

    $userList = User::when(!is_null($banned), function ($query) use ($banned) {
            return $query->where('banned', $banned);
        })
        ->where('role', '!=', 'admin') // Excluir administradores
        ->paginate(10);

    return view('userList', [
        'userList' => $userList,
    ]);
}


}