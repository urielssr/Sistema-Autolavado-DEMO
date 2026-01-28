<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registro - Autolavado</title>
    <script>
        function validarRegistro() {
            var nombreCompleto = document.getElementById('nombre_completo').value;
            var usuario = document.getElementById('usuario').value;
            var correo = document.getElementById('correo').value;
            var contrasena = document.getElementById('contrasena').value;
            var confirmarContrasena = document.getElementById('confirmar_contrasena').value;

            // Validar datos vacíos
            if (!nombreCompleto || !usuario || !correo || !contrasena || !confirmarContrasena) {
                alert("Todos los campos son obligatorios. Por favor, completa el formulario.");
                return false; // Evita el envío del formulario
            }

            // Validar nombre completo
            if (nombreCompleto.length > 50) {
                alert("El nombre completo no puede tener más de 50 caracteres.");
                return false; // Evita el envío del formulario
            }

            // Validar usuario
            if (usuario.length > 15) {
                alert("El nombre de usuario no puede tener más de 15 caracteres.");
                return false; // Evita el envío del formulario
            }

            // Validar contraseñas
            if (contrasena !== confirmarContrasena) {
                alert("Las contraseñas no coinciden. Por favor, inténtelo de nuevo.");
                return false; // Evita el envío del formulario
            }

            // Validar correo electrónico
            if (!validarCorreoElectronico(correo)) {
                alert("Ingrese un correo electrónico válido.");
                return false; // Evita el envío del formulario
            }

            // Envía el formulario si todas las validaciones son exitosas
            document.getElementById('registroForm').submit();
        }

        function validarCorreoElectronico(correo) {
            // Expresión regular para validar correo electrónico
            var regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return regexCorreo.test(correo);
        }
    </script>
</head>
<body>

<div class="register-container">
    <a href="../INDEX/index.html" class="logo">
        <img src="../img/logo_2.1.png" alt="Tu Logo">
    </a>
    <h2>Crear cuenta</h2>
    <form id="registroForm" onsubmit="return validarRegistro();" method="post" action="registro_usuario_be.php">
        <label for="nombre_completo">Nombre Completo:</label>
        <input type="text" id="nombre_completo" name="nombre_completo" required>

        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <label for="confirmar_contrasena">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>

        <button type="submit">Registrarse</button>
    </form>

    <div class="login-link">
        <p>¿Ya tienes una cuenta? <a href="../Logeo/Login.php">Inicia sesión</a></p>
    </div>
</div>

</body>
</html>
