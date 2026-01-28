<?php
include 'conexion_be.php';

// Verifica si los índices 'correo' y 'token' existen en $_GET
$correo = isset($_GET['correo']) ? $_GET['correo'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

$query = "SELECT * FROM usuarios WHERE correo = ? AND token_recuperacion = ?";
$statement = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($statement, "ss", $correo, $token);
mysqli_stmt_execute($statement);

$resultado = mysqli_stmt_get_result($statement);

if (!$resultado) {
    die('Error en la consulta a la base de datos: ' . mysqli_error($conexion));
}

if (mysqli_num_rows($resultado) == 1) {
    // El token es válido, permitir al usuario establecer una nueva contraseña
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nueva_contrasena = $_POST['nueva_contrasena'];

        // Validar la nueva contraseña (puedes agregar más validaciones según tus requisitos)

        $contrasena_cifrada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

        $actualizar_query = "UPDATE usuarios SET contrasena = ?, token_recuperacion = NULL WHERE correo = ?";
        $actualizar_statement = mysqli_prepare($conexion, $actualizar_query);
        mysqli_stmt_bind_param($actualizar_statement, "ss", $contrasena_cifrada, $correo);
        $actualizar_resultado = mysqli_stmt_execute($actualizar_statement);

        if ($actualizar_resultado) {
            // Muestra una alerta con el mensaje de éxito
            echo '<script>';
            echo 'alert("Contraseña actualizada con éxito. Ahora puedes iniciar sesión con tu nueva contraseña.");';
            echo 'window.location.href = "../Logeo/Login.php";'; // Redirige al usuario a la página RegistroyLogin.php después de hacer clic en Aceptar
            echo '</script>';
        }  else {
            echo '<p style="text-align: center; color: red;">Error al actualizar la contraseña en la base de datos: ' . mysqli_error($conexion) . '</p>';
        }
    } else {
        // Muestra el formulario solo si el método de solicitud es POST
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Restablecer Contraseña</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <h1>Restablecer Contraseña</h1>
            <div class="contenedor__olvido-contrasena">
            <form action="" method="post">
                <label for="nueva_contrasena">Nueva Contraseña:</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
                <button type="submit">Guardar Nueva Contraseña</button>
            </form>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    echo '<p style="text-align: center; color: red;">Token no válido. Por favor, siga el enlace proporcionado en su correo electrónico.</p>';
}

mysqli_close($conexion);
?>
