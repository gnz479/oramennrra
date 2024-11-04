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
    public function sendEmail(){
        $url = 'https://tciconsultoria.com/send_emails/send_email_for_droplet.php'; // Asegúrate de que sea la URL correcta

        try {
            $response = Http::get($url);

            // Verifica si la solicitud fue exitosa
            \Log::info('Email status: ' . $response);
           
        } catch (\Exception $e) {
            \Log::error('Excepción al enviar el email: ' . $e->getMessage());
        }
    }

    //funcion para mandar correos (Problemas con el servicio)
    public function sendEmail2()
    {

        $smtpHost =    "smtp.mandrillapp.com";  // Dominio alternativo brindado en el email de alta
        $smtpUsuario = "tcidev.soporte@gmail.com";  // Mi cuenta de correo
        $smtpClave =   "QbsIpI8b1PQb7BYtQ9NJbA";  // Mi contra // Mi contrase�a

        $email_remitente = "notificadorplus@tciconsultoria.com";
        $email_destinatario = "developergm479@gmail.com";

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.mandrillapp.com'; // Servidor SMTP del otro dominio
            // $mail->Host       = 'smtp.gmail.com'; // Servidor SMTP del otro dominio
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsuario; // Tu usuario en el dominio
            $mail->Password   = $smtpClave; // Contraseña de la cuenta
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587; // Puerto (usualmente 587 para TLS)
         
            // Remitente y destinatario
            $mail->setFrom('notificadorplus@tciconsultoria.com', 'Tu Nombre o Empresa');
            $mail->addAddress('angel@tciconsultoria.com', 'Nombre del Destinatario');
         
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Asunto del correo';
            $mail->Body    = 'Este es el contenido HTML del correo.';
            $mail->AltBody = 'Este es el contenido alternativo en texto plano.';
         
            $status = $mail->send();
            \Log::info('Email.'. $status);
        } catch (Exception $e) {
            \Log::info("El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}");
        }
    }
}
