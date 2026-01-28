<?php
session_start();

// Configuración de la conexión a la base de datos
$host = "localhost";
$port = 5432;
$dbname = "Autolavado";
$user = "postgres";
$password = "123";

// Crear conexión
try {
    $conexion = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

// Variable para almacenar mensajes de alerta
$alerta = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $dia = $_POST["dia"];
    $mes = $_POST["mes"];
    $nota = $_POST["nota"];
    $servicio = $_POST["servicio"];

    // Obtener el nombre de usuario y correo desde la sesión
    $usuario = $_SESSION['usuario'];
    $correo = $_SESSION['correo'];

    // Verificar cuántas citas ya están agendadas para este día y mes
    $consulta = $conexion->prepare("SELECT COUNT(*) FROM citas WHERE dia = ? AND mes = ?");
    $consulta->bindParam(1, $dia);
    $consulta->bindParam(2, $mes);
    $consulta->execute();
    $numeroCitas = $consulta->fetchColumn();

    // Verificar si el número de citas es mayor o igual a 10
    if ($numeroCitas >= 10) {
        // Si ya hay 10 citas, mostrar un mensaje de alerta
        $alerta = "El día $dia ya ha alcanzado el límite de citas. Por favor, selecciona otro día.";
        echo '<script>alert("'.$alerta.'"); window.location.href = "agenda.php";</script>';
    } else {
        // Preparar la consulta para insertar la cita
        $stmt = $conexion->prepare("INSERT INTO citas (dia, mes, nota, usuario, correo, servicio) VALUES (?, ?, ?, ?, ?, ?)");

        try {
            $stmt->bindParam(1, $dia);
            $stmt->bindParam(2, $mes);
            $stmt->bindParam(3, $nota);
            $stmt->bindParam(4, $usuario);
            $stmt->bindParam(5, $correo);
            $stmt->bindParam(6, $servicio);

            // Ejecutar la inserción de la cita
            if ($stmt->execute()) {
                // Cita agendada con éxito, enviar correo y mostrar alerta
                $alerta = "Cita agendada con éxito";
                // Configuración y envío del correo (igual que ya lo tienes)
                // ...
                echo '<script>alert("'.$alerta.'"); window.location.href = "../logeado/logeado.php";</script>';
            } else {
                echo "Error al agendar la cita";
            }
        } catch (PDOException $e) {
            // Si hubo algún error en la ejecución, mostrar un mensaje genérico
            echo "Error: " . $e->getMessage();
        }
    }

    // Cerrar la conexión y liberar recursos
    $stmt = null;
    $conexion = null;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>