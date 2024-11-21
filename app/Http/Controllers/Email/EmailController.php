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
    public function sendEmail($jsonToSend){
        $url = 'https://tciconsultoria.com/send_emails/send_email_for_droplet.php?json='.urlencode($jsonToSend); // Asegúrate de que sea la URL correcta

        try {
            $response = Http::get($url);
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
                    $jsonString = (string) $item->json; // Convertir el contenido a string
                    $jsonArray = json_decode($jsonString, true);

                    $dataToSend = [
                        "destinatario" => $item->task_asigned_to,
                        "record_id" => $item->record_id_,
                        "details" => $jsonArray
                    ];

                    $jsonToSend = json_encode($dataToSend);

                    // dd($item, $jsonArray);
                    // $this->sendEmail($item->task_asigned_to, $item->record_id_);
                    $this->sendEmail($jsonToSend);
                    break;
                }
            }
        }
    }
    

    //funcion para poner tarea completada
    public function taskCompleted($record_id){
        $response = $this->setQuickBase($record_id);
       
        return view('upload_files.task_complete', ['title' => 'File uploaded successfully',
        'text' => 'Task completed correctly.',
        'status' => 'success']);
    }
}
