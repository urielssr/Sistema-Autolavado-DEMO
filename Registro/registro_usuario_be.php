<?php
include('../conexion/conexion_be.php');

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCompleto = $_POST['nombre_completo'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Encripta la contraseña
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

    // Intenta realizar la conexión
    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        exit; // Sale del script si hay un error de conexión
    }

    // Consulta preparada para insertar datos en la base de datos
    $sql = "INSERT INTO usuarios (nombre_completo, usuario, correo, contrasena) VALUES (?, ?, ?, ?)";
    $query = $conn->prepare($sql);
    $query->bindParam(1, $nombreCompleto);
    $query->bindParam(2, $usuario);
    $query->bindParam(3, $correo);
    $query->bindParam(4, $contrasena_encriptada);

    if ($query->execute()) {
        // Cerrar la conexión
        $conn = null;

        // Muestra una alerta con JavaScript
        echo '<script>alert("Usuario creado exitosamente. Serás redirigido al inicio de sesión."); window.location.href = "../Logeo/Login.php";</script>';
        exit; // Evita que se procese el HTML después de manejar el formulario
    } else {
        echo "Error al insertar usuario: " . print_r($query->errorInfo(), true);
    }

    // Cerrar la conexión
    $conn = null;
    exit; // Evita que se procese el HTML después de manejar el formulario
}
?>
