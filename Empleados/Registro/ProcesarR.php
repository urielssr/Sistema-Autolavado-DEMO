<?php
require '../../conexion/conexion_be.php';
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCompleto = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    
    // Genera claveempleado y contraseña
    $claveEmpleado = generarClaveEmpleado($nombreCompleto);
    $contrasena = generarContrasena();

    try {
        // Inserta los datos en la base de datos
        $stmt = $connection->prepare("INSERT INTO empleados (nombrecompleto, correo, claveempleado, contrasena) VALUES (?, ?, ?, ?)");

        $stmt->execute([$nombreCompleto, $correo, $claveEmpleado, $contrasena]);

        // Envía el correo electrónico
        enviarCorreo($correo, $nombreCompleto, $claveEmpleado, $contrasena);

        // Cierre de la conexión
        $stmt = null;
        $conn = null;

        // Alerta y redireccionamiento si la creación de la cuenta es exitosa
        echo '<script>alert("Cuenta creada exitosamente. Revisa tu correo para obtener tus credenciales."); window.location.href = "../Logeo/LogeoE.php";</script>';
        exit;
    } catch (PDOException $e) {
        // Alerta y mensaje de error si hay un problema al crear la cuenta
        echo '<script>alert("Hubo un error al crear la cuenta. Por favor, intenta nuevamente."); window.location.href = "ResgitroE.php";</script>';
        exit;
    }
}

function generarClaveEmpleado($nombreCompleto) {
    $iniciales = '';
    $palabras = explode(' ', $nombreCompleto);
    foreach ($palabras as $palabra) {
        $iniciales .= substr($palabra, 0, 1);
    }
    return strtoupper($iniciales) . '-' . uniqid();
}

function generarContrasena() {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
}

function enviarCorreo($correo, $nombreCompleto, $claveEmpleado, $contrasena) {
    $mail = new PHPMailer(true);
    try {
        // Configurar PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Reemplaza con la dirección de tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'bubblequotessoporte@gmail.com';  // Reemplaza con tu dirección de correo
        $mail->Password = 'xbnw vdyw egiv wugx';  // Reemplaza con tu contraseña
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tucorreo@gmail.com', 'Soporte');
        $mail->addAddress($correo, $nombreCompleto);
        $mail->CharSet = 'UTF-8';

        // Configura el contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Tu cuenta de empleado ha sido creada';
        $mail->Body    = "Hola $nombreCompleto,<br>Tu cuenta de empleado ha sido creada exitosamente.<br>Clave de empleado: $claveEmpleado<br>Contraseña: $contrasena<br>¡Gracias por unirte!";
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Envía el correo
        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo: " . $e->getMessage();
    }
}
?>
