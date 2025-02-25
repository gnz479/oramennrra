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
    public function sendEmail($jsonToSend, $typeEmail = null){
        $url = 'https://tciconsultoria.com/send_emails/send_email_for_droplet.php?json='.urlencode($jsonToSend).'&typeEmail='.$typeEmail; // Asegúrate de que sea la URL correcta

        try {
            $response = Http::get($url);
            \Log::info('Email status: ' . $response);
        } catch (\Exception $e) {
            \Log::error('Excepción al enviar el email: ' . $e->getMessage());
        }
    }

    //funcion para obtener las tareas y notificar por correo.
    public function getTasksToNotificacion(){
        $db = 'budxyyjmm'; //tabla de tareas
        $query = '{12.EX.false} AND {8.XCT.""}';
        $clist = '3.8.16.20.19.21.17';

        $tasks = $this->getQuickBase($db, $query, $clist);
        if(count($tasks)){
            foreach($tasks as $index => $item){
                if($item->task_asigned_to != ''){
                    $jsonString = (string) $item->json; // Convertir el contenido a string
                    $jsonArray = json_decode($jsonString, true);
                    $jsonToSend = $this->dataToSend($item->task_asigned_to, $item->record_id_, $jsonArray);

                    $this->sendEmail($jsonToSend);
                    break;
                }
            }
        }
    }

    //funcion para poner tarea completada
    public function taskCompleted($record_id){
        $db = 'budxyyjmm';
        $response = $this->setQuickBase($record_id, $db);
       
        return view('upload_files.task_complete', ['title' => 'File uploaded successfully',
        'text' => 'Task completed correctly.',
        'status' => 'success']);
    }

    //funcion para obtener las tareas y notificar por correo.
    public function taskAdded(Request $request){
        $json = base64_decode($request->input('json'));
        $decodedJson = json_decode($json, true);
        $destinatario = $request->input('destinatario');

        $jsonToSend = $this->dataToSend($destinatario, $decodedJson['taskId'], $decodedJson, true);
        dd($jsonToSend);
        $this->sendEmail($jsonToSend, true);
    }

    //funcion para la data que se mandara al email
    function dataToSend($destinatario = null, $record_id = null, $json, $typeAdd = null){
        if($destinatario && $record_id && count($json)){
                $dataToSend = [
                    "destinatario" => 'ing.gonzalo@tciconsultoriamx.onmicrosoft.com', //destinatario de pruebas
                    // "destinatario" => $typeAdd ? $destinatario: json_decode(json_encode($destinatario), true)[0], 
                    "record_id" => $typeAdd ? $record_id: json_decode(json_encode($record_id), true)[0], 
                    "details" => $json,
            ];

            $jsonToSend = json_encode($dataToSend, JSON_FORCE_OBJECT);
            return $jsonToSend;
        }
    }
}
