<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function store(){
        $user = new User();
        $user->name = 'prueba';
        $user->email = 'prueba@tci.com';
        $user->password = bcrypt('test1234'); // Encripta la contraseña
        $user->save();

        \Log::info('Usuario guardado correctamente');
    }
}
