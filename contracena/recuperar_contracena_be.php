<?php
// Incluir los archivos necesarios de PHPMailer
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario aquí
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $token = mysqli_real_escape_string($conexion, $_POST['token']);

    // Verificar si el correo electrónico existe en la base de datos
    $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado && mysqli_num_rows($resultado) == 1) {
        // Actualizar el token de recuperación en la base de datos
        $actualizar_query = "UPDATE usuarios SET token_recuperacion = '$token' WHERE correo = '$correo'";
        $actualizar_resultado = mysqli_query($conexion, $actualizar_query);

        if ($actualizar_resultado) {
            // Configurar PHPMailer
            $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Reemplaza con la dirección de tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'bubblequotessoporte@gmail.com';  // Reemplaza con tu dirección de correo
        $mail->Password = 'xbnw vdyw egiv wugx';  // Reemplaza con tu contraseña
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

            // Configurar el correo
            $mail->setFrom('tucorreo@gmail.com', 'Soporte');
            $mail->addAddress($correo);
            $mail->CharSet = 'UTF-8';  // Establece el conjunto de caracteres a UTF-8
            $mail->Subject = 'Recuperación de Contraseña';
            $mail->Body = 'Haz clic en este enlace para restablecer tu contraseña: http://hostnet.sytes.net//AUTOLAVADO/contracena/restablecer_contracena_be.php?correo=' . urlencode($correo) . '&token=' . urlencode($token);
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // Enviar el correo
            if ($mail->send()) {
                // Redirigir al usuario a una página de confirmación
                header("Location: confirmacion_envio.php");
                exit;
            } else {
                echo '<p style="color: red;">Error al enviar el correo: ' . $mail->ErrorInfo . '</p>';
            }
        } else {
            echo '<p style="color: red;">Error al actualizar el token en la base de datos: ' . mysqli_error($conexion) . '</p>';
        }
    } else {
        echo '<script>';
        echo 'alert("Correo electrónico no encontrado en la base de datos. Por favor, verifique el correo.");';
        echo 'window.location.href = "../Logeo/Login.php";';
        echo '</script>';
    }
}

mysqli_close($conexion);
?>
