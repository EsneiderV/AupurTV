<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';




function recuperarClave($nombre,$clave)
{
    $mail = new PHPMailer(true);
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'johanfg32@gmail.com';                     //SMTP username
    $mail->Password   = 'gdfkiwazztxacrnt';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


    //Recipients
    $mail->setFrom('johanfg32@gmail.com', 'Aupur TV');
    $mail->addAddress('neiderurrego221203@gmail.com');     //Add a recipient

    //Content
    $mail->isHTML(true);   
    $html = '<h1>Correo de recuperación de contraseña</h1>';
    $html.= '<p>Hola '.$nombre.' esta es su nueva contraseña:'.$clave.' </p>';
    $html.= '<p>Le sugerimos cambiarla lo mas proto posible que tenga un feliz dia</p>';            //Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Recuperación de contraseña';
    $mail->Body    = $html;

    $mail->send();
}


