<?php
// Configuración de la base de datos
$host = "localhost";
$port = 5432;
$dbname = "Autolavado";
$user = "postgres";
$password = "123";

try {
    // Crear conexión
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    // Configurar PDO para que lance una excepción si hay un error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $claveempleado = $_POST['claveempleado'];
        $contrasena = $_POST['contrasena'];

        // Obtén la contraseña desde la base de datos
        $query = $conn->prepare("SELECT contrasena, correo FROM empleados WHERE claveempleado = ?");
        $query->execute([$claveempleado]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row && $contrasena === $row['contrasena']) {
            // Contraseña correcta, inicia sesión 
            session_start();
            $_SESSION['claveempleado'] = $claveempleado; // Guarda el nombre de usuario en la sesión
            $_SESSION['correo'] = $row['correo']; // Guarda el correo en la sesión

            // Sesión iniciada correctamente, redirige al usuario a la página monitor.php
            header("Location: ../Monitor/monitor.php");
            exit(); // Asegura que el script se detenga después de la redirección
        } else {
            // Clave de empleado no encontrada o contraseña incorrecta
            echo "<script>alert('Clave de empleado no encontrada o contraseña incorrecta'); window.location.href = 'logeoE.php';</script>";
        }
    }
} catch (PDOException $e) {
    // Error en la conexión
    die("Conexión fallida: " . $e->getMessage());
}
?>
