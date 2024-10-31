<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\{EmailPhpMailer, EmailSmtp};

class EmailController extends Controller
{
    //envio de correos automaticos con crontab
    public function sendEmail()
    {
        \Log::info('Ruta ejecudata de manera correcta.');
    }
}
