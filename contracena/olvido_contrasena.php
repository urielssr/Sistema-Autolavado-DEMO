<!-- olvido_contrasena.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Agrega tus estilos si es necesario -->
</head>
<body>
    <h1>Recuperar Contraseña</h1>
    <div class="contenedor__olvido-contrasena">
        <form action="recuperar_contracena_be.php" method="post">
            <h2>Ingresa tu correo electrónico</h2>
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
            <button type="submit">Enviar Instrucciones</button>
            <input type="hidden" name="token" value="<?php echo uniqid(); ?>">
        </form>
    </div>
</body>
</html>
