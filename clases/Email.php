<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;


class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($nombre, $email, $token)
    {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
      
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('apsalon@gmail.com');
        $mail->addAddress('cuenta@appsalon.com','appsalon.com');
        $mail->Subject="Confirma tu cuenta";
        $mail->isHTML(true);
        $mail->CharSet='UTF8';

        $contenido="<html>";
        $contenido.="<p><strong>Hola  ". $this->nombre ."</strong> Has creado tu cuenta en APP Salon,
        solo debes confirmar tu cuenta presionando en el siguiente enlace..... </p>";
        $contenido.="<p>Presiona aqui: <a href='" .$_ENV['APP_URL'] . "/confirmar-cuenta?token=". $this->token ."'> Confirmar Cuenta</a></p>";
        $contenido.="<p> Si tu no solicitaste esta cuenta, puedes ingnorar el mensaje </p>";
        $contenido.="</html>";

        $mail->Body=$contenido;

        $mail->send();
    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('apsalon@gmail.com');
        $mail->addAddress('cuenta@appsalon.com','appsalon.com');
        $mail->Subject="Restablecer Contraseña";
        $mail->isHTML(true);
        $mail->CharSet='UTF8';

        $contenido="<html>";
        $contenido.="<p><strong>Hola  ". $this->nombre ."</strong> has solicitado restablecer tu password en APP Salon,
        sigue el siguiente enlace dando click para proceder...... </p>";
        $contenido.="<p>Presiona aqui: <a href='". $_ENV['APP_URL'] ."/restablecer?token=". $this->token ."'> Restablecer tu Clave</a></p>";
        $contenido.="<p> Si tu no solicitaste esta cuenta, puedes ingnorar el mensaje </p>";
        $contenido.="</html>";

        $mail->Body=$contenido;

        $mail->send();
    }
}
