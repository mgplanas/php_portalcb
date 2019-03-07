<?php
 include("../../conexion.php");
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './Exception.php';
require './PHPMailer.php';
require './SMTP.php';

//get elements from database
$query_today_tasks = "SELECT t.titulo, t.descripcion, t.dia, t.hora, t.persona, p.nombre, p.apellido, p.email, t.grupo, g.nombre as ngrupo
                        FROM tarea as t 
                        LEFT JOIN persona as p on t.persona = p.id_persona
                        LEFT JOIN grupo as g on t.grupo = g.id_grupo
                        WHERE t.borrado='0' and t.estado='0' and t.dia=date(now())";
$query = mysqli_query($con, $query_today_tasks) or die('Query failed: ' . mysql_error());

while ( $row = mysqli_fetch_assoc($query))  {

$toAddress = $row['email'];
$toApellido = $row['apellido'];
$toSubject = "Tareas programadas del día: " . $row['dia'];
$toBody = '
<html><body>
<div class=WordSection1>
<p class=MsoNormal>Estimado Alfredo, en el día de hoy tiene programada la
siguiente tarea:</p>

<p class=MsoNormal><b style="mso-bidi-font-weight:normal"><u>Titulo:  <o:p></o:p></u></b>'.$row['titulo'].'</p>

<p class=MsoNormal><b style="mso-bidi-font-weight:normal"><u>Descripción:<o:p></o:p></u></b>'.$row['descripcion'].'</p>

<p class=MsoNormal><b style="mso-bidi-font-weight:normal"><u>Horario:<o:p></o:p></u></b>'.$row['hora'].'</p>

<p class=MsoNormal>Por favor, dar seguimiento a la misma en el <a href = "https://usi.arsat.com.ar/controlsv2/pages/tareas.php">Portal USI</a></p>

<p class=MsoNormal>Muchas gracias</p>
<p class=MsoNormal>SOC ARSAT</p>
</div>
</body></html>';
    
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 2;                                 // // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = '192.168.26.25;192.168.26.26;192.168.26.27';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'notificaciones_soc';                 // SMTP username
        $mail->Password = 'notif1256';                           // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('notificaciones_soc@arsat.com.ar', 'Notificaciones SOC');
        $mail->addAddress($toAddress, $toApellido);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $toSubject;
        $mail->Body    = $toBody;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>