<?php
// Configuración de la conexión a PostgreSQL (ajusta los valores según tu configuración)
$host = "localhost";
$port = "5432";  // Puerto predeterminado de PostgreSQL
$dbname = "Autolavado";
$user = "postgres";
$password = "123";

// Crear conexión
$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

// Verificar la conexión
if ($conn === false) {
    die("Conexión fallida");
}

// Obtener las citas de la base de datos
$sql = "SELECT * FROM citas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="monitor.css">
    <title>Ver Citas - Autolavado</title>
    <style>
        .logo img {
            max-width: 100px; /* Ajusta el valor según tus necesidades */
            height: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="../../INDEX/index.html" class="logo">
        <img src="../../img/logo_2.1.png" alt="Tu Logo">
    </a>
    <h2>Lista de Citas</h2>

    <!-- Tabla para mostrar las citas -->
    <table>
        <tr>
            <th>ID</th>
            <th>Día</th>
            <th>Mes</th>
            <th>Nota</th>
            <th>Cliente</th>
            <th>Correo</th>
            <th>Servicio</th>
        </tr>

        <?php
        // Mostrar las citas en la tabla
        if ($result !== false) {
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["dia"] . "</td>";
                echo "<td>" . $row["mes"] . "</td>";
                echo "<td>" . $row["nota"] . "</td>";
                echo "<td>" . $row["usuario"] . "</td>";
                echo "<td>" . $row["correo"] . "</td>";
                echo "<td>" . $row["servicio"] . "</td>";
                echo "<td><button onclick='abrirModal(" . $row["id"] . ")'>Editar</button></td>";
                echo "<td><button onclick='eliminarCita(" . $row["id"] . ")'>Eliminar</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No hay citas registradas.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
<script>
    // Función para abrir el modal de edición
    function abrirModal(id) {
        // Establece el ID de la cita en el campo oculto del formulario
        document.getElementById('idCita').value = id;
        // Muestra el modal
        document.getElementById('editarModal').style.display = 'block';
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('editarModal').style.display = 'none';
    }

    // Función para guardar la edición
    function guardarEdicion() {
        // Aquí puedes procesar la edición, por ejemplo, enviar una solicitud AJAX al servidor
        // Luego cierra el modal
        cerrarModal();
    }

    function eliminarCita(id) {
        var confirmacion = confirm("¿Seguro que deseas eliminar la cita con ID: " + id + "?");

        if (confirmacion) {
            // Hacer una solicitud AJAX para eliminar la cita
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Manejar la respuesta del servidor, por ejemplo, mostrar una alerta
                    alert(xhr.responseText);
                    // Recargar la página después de la eliminación
                    location.reload();
                }
            };

            // Configurar y enviar la solicitud
            xhr.open("GET", "procesareliminacion.php?id=" + id, true);
            xhr.send();
        }
    }
</script>
<!-- Modal para editar citas -->
<div id="editarModal" class="modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.5);">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2 style="color: white;">Editar Cita</h2>
        <!-- Formulario de edición -->
        <form id="formularioEditar" method="post" action="procesarguardado.php">
            <label for="nuevaFecha" style="color: white;">Nueva Fecha:</label>
            <input type="date" id="nuevaFecha" name="nuevaFecha" required>
            <!-- Agrega un campo oculto para enviar el ID de la cita -->
            <input type="hidden" id="idCita" name="idCita">
            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<?php
// Cerrar la conexión (no es necesario con PDO)
$conn = null;
?>
