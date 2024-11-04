<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController extends Controller
{
    //envio de correos automaticos con crontab
    public function sendEmail()
    {
        // Datos de la cuenta de correo utilizada para enviar v�a SMTP
        $smtpHost =    "smtp.mandrillapp.com";  // Dominio alternativo brindado en el email de alta
        $smtpUsuario = "tcidev.soporte@gmail.com";  // Mi cuenta de correo
        $smtpClave =   "QbsIpI8b1PQb7BYtQ9NJbA";  // Mi contra // Mi contrase�a

        $email_remitente = "notificadorplus@tciconsultoria.com";
        $email_destinatario = "developergm479@gmail.com";

        $mail = new PHPMailer();

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = $smtpHost;         // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUsuario;     // Usuario SMTP
            $mail->Password = $smtpClave;            // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Tipo de encriptación
            $mail->Port = 587;                            // Puerto SMTP (puede variar)
    
            // Configuración del remitente y destinatario
            $mail->setFrom($email_remitente, 'Tu Nombre o Empresa');
            $mail->addAddress($email_destinatario, 'Nombre del Destinatario');
    
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Asunto del Correo';
            $mail->Body    = 'Este es el <b>contenido HTML</b> del correo';
            $mail->AltBody = 'Este es el contenido en texto plano para clientes que no soportan HTML';
    
            // Enviar el correo
            $status = $mail->send();
            \Log::info('Email. '. $status);
            // echo 'El mensaje se ha enviado correctamente';
        } catch (Exception $e) {
            // echo "El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}";
            \Log::info("El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}");
        }
    }

    public function sendEmail2()
    {
        // Datos de la cuenta de correo utilizada para enviar v�a SMTP
        $smtpHost =    "smtp.mandrillapp.com";  // Dominio alternativo brindado en el email de alta
        $smtpUsuario = "tcidev.soporte@gmail.com";  // Mi cuenta de correo
        $smtpClave =   "QbsIpI8b1PQb7BYtQ9NJbA";  // Mi contra // Mi contrase�a

        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->CharSet = "utf-8";
    
        // VALORES A MODIFICAR //
        $mail->Host = $smtpHost;
        $mail->Username = $smtpUsuario;
        $mail->Password = $smtpClave;
        $mail->From = "notificadorplus@tciconsultoria.com"; // Email desde donde env�o el correo.
        $mail->FromName = 'Nombre empresa';
        $mail->AddAddress('developergm479@gmail.com'); // Esta es la direcci�n a donde enviamos los datos del formulario
        $mail->Subject = 'Titulo de email'; // Este es el titulo del email.

        $mensajeHtml = nl2br('Mensaje del correo.');
        $nombre = 
        $mail->Body = "
        <html>
            <body>
            <p>Estimado(@):  {Nombre destinatario}</p>
            <p>Espero que se encuentre bien.</p>
            <p>{Mensaje de correo}</p>
            </body>
        </html>
        <br />"; // Texto del email en formato HTML
    
        $mail->AltBody = "{Mensaje de correo} \n\n "; // Texto sin formato HTML
        // FIN - VALORES A MODIFICAR //
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    
       
        // $mail->addStringAttachment($pdfdoc, $data['nombre_archivo']); 

        $status = $mail->Send();
        \Log::info('Email.'. $status);
        // return $status;
    }

    
}
