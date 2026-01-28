<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Iniciar Sesión - Autolavado</title>
</head>
<body>

<div class="login-container">
    <a href="../INDEX/index.html" class="logo">
        <img src="../img/logo_2.1.png" alt="Tu Logo">
    </a>
    <h2>Iniciar Sesión</h2>
    <form action="login_usuario_be.php" method="post">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="usuario" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="contrasena" required>

        <button type="submit">Ingresar</button>
    </form>

    <div class="forgot-password">
        <a href="../contracena/olvido_contrasena.php">¿Olvidaste tu contraseña?</a>
    </div>

    <div class="or">
        <p>O</p>
    </div>

    <div class="create-account">
        <a href="../Registro/Registro.php">¿No tienes cuenta?, Crea una</a>
    </div>
</div>

</body>
</html>
