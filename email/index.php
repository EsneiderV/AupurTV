<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';




function recuperarClave($nombre,$clave,$correo)
{
    $mail = new PHPMailer();
    $mail->isSMTP();                                            //Send using SMTP
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->SMTPSecure = 'tls';                                 //Enable SMTP authentication
    $mail->SMTPAuth   = true; 
    $mail->Username   = 'johanfg32@gmail.com';                     //SMTP username
    $mail->Password   = 'bdqmlmjnzqxblhrp';                               //SMTP password


    //Recipients
    $mail->setFrom('johanfg32@gmail.com', 'Aupur TV');
    $mail->addAddress($correo);     //Add a recipient

    //Content
    $mail->isHTML(true);   
    $html = '<h1>Correo de recuperación de contraseña</h1>';
    $html.= '<p>Hola '.$nombre.' esta es su nueva contraseña:'.$clave.' </p>';
    $html.= '<p>Le sugerimos cambiarla lo mas proto posible que tenga un feliz dia</p>';            //Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Recuperación de contraseña';
    $mail->Body    = ($html);

    $mail->send();
}




// function recuperarClave($nombre,$clave)
// {
  

// //Crear una instancia de PHPMailer
// $mail = new PHPMailer();
// //Definir que vamos a usar SMTP
// $mail->IsSMTP();
// //Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
// // 0 = off (producción)
// // 1 = client messages
// // 2 = client and server messages
// $mail->SMTPDebug  = 0;
// //Ahora definimos gmail como servidor que aloja nuestro SMTP
// $mail->Host       = 'smtp.gmail.com';
// //El puerto será el 587 ya que usamos encriptación TLS
// $mail->Port       = 587;
// //Definmos la seguridad como TLS
// $mail->SMTPSecure = 'tls';
// //Tenemos que usar gmail autenticados, así que esto a TRUE
// $mail->SMTPAuth   = true;
// //Definimos la cuenta que vamos a usar. Dirección completa de la misma
// $mail->Username   = "johanfg32@gmail.com";
// //Introducimos nuestra contraseña de gmail
// $mail->Password   = "bdqmlmjnzqxblhrp";
// //Definimos el remitente (dirección y, opcionalmente, nombre)
// $mail->SetFrom('johanfg32@gmail.com', 'Mi nombre');
// //Esta línea es por si queréis enviar copia a alguien (dirección y, opcionalmente, nombre)
// // $mail->AddReplyTo('replyto@correoquesea.com','El de la réplica');
// //Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
// $mail->AddAddress('neiderurrego221203@gmail.com', 'El Destinatario');
// //Definimos el tema del email
// $mail->Subject = 'Esto es un correo de prueba';
// //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
// // $mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
// //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
// $mail->Body    = ('Hola');
// $mail->AltBody = 'This is a plain-text message body';
// //Enviamos el correo
// if(!$mail->Send()) {
//   echo "Error: " . $mail->ErrorInfo;
// } else {
//   echo "Enviado!";
// }


// }








