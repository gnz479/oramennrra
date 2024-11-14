<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{    

    //funcion para llamar script de envio de correos
    public function sendEmail($destinatario){
        $url = 'https://tciconsultoria.com/send_emails/send_email_for_droplet.php?destinatario='.$destinatario; // Asegúrate de que sea la URL correcta

        try {
            $response = Http::get($url);
            // Verifica si la solicitud fue exitosa
            \Log::info('Email status: ' . $response);
           
        } catch (\Exception $e) {
            \Log::error('Excepción al enviar el email: ' . $e->getMessage());
        }
    }

    //funcion para obtener las tareas y notificar por correo.
    public function getTasksToNotificacion(){
        $tasks = $this->getQuickBase();
        if(count($tasks)){
            foreach($tasks as $index => $item){
                if($item->task_asigned_to != ''){
                   $this->sendEmail($item->task_asigned_to);
                }
            }
        }
    }
}
