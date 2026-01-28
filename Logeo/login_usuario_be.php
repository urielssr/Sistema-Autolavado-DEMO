<?php
include('../conexion/conexion_be.php');

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Intenta realizar la conexión
    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
        echo "Conexión exitosa";
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        exit; // Sale del script si hay un error de conexión
    }

    // Obtén la contraseña encriptada desde la base de datos
    $query = $conn->prepare("SELECT contrasena, correo FROM usuarios WHERE usuario = ?");
    $query->bindParam(1, $usuario);
    $query->execute();
    $query->bindColumn('contrasena', $contrasena_encriptada);
    $query->bindColumn('correo', $correo);
    
    if ($query->fetch(PDO::FETCH_ASSOC)) {
        // Verifica la contraseña usando password_verify()
        if (password_verify($contrasena, $contrasena_encriptada)) {
            // Contraseña correcta, inicia sesión
            session_start();
            $_SESSION['usuario'] = $usuario;
            $_SESSION['correo'] = $correo;

            header("Location: ../logeado/logeado.php");
            exit();
        } else {
            echo "Nombre de usuario o contraseña incorrectos";
        }
    } else {
        echo "Nombre de usuario no encontrado";
    }

    // Cerrar la conexión
    $conn = null;
    exit; // Evita que se procese el HTML después de manejar el formulario
}
?>
