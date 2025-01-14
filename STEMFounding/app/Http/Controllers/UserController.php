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
}
