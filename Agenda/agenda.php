<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Citas</title>
    <link rel="stylesheet" href="agenda.css">
</head>
<body>

<?php include('../verificarsesion/verfiicarsesion.php'); ?>
    <div class="container">
    <a href="../logeado/logeado.php" class="logo">
        <img src="../img/logo_2.1.png" alt="Tu Logo">
    </a>
        <h1>Agenda de Citas</h1>
        
        <!-- Formulario de Agendar Cita -->
        <form action="agendar_cita.php" method="post">
            <label for="mes">Mes:</label>
            <select id="mes" name="mes" onchange="actualizarDias()" required>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>

            <label for="dia">Día:</label>
            <select id="dia" name="dia" required>
                <!-- Los días se actualizarán dinámicamente con JavaScript -->
            </select>

            <label for="nota">Nota:</label>
            <textarea id="nota" name="nota" rows="4" required></textarea>

            <label for="servicio">Servicio:</label>
            <select id="servicio" name="servicio" required>
                <option value="lavadoExterior">Lavado Exterior</option>
                <option value="lavadoInterior">Lavado Interior</option>
                <option value="lavadoCompleto">Lavado Completo</option>
            </select>

            <button type="submit">Agendar Cita</button>
        </form>
    </div>

    <script>
        function actualizarDias() {
            const mesSeleccionado = document.getElementById('mes').value;
            const diasSelect = document.getElementById('dia');

            // Limpiar opciones actuales
            diasSelect.innerHTML = '';

            // Obtener el número de días para el mes seleccionado
            const diasEnMes = new Date(2022, mesSeleccionado, 0).getDate();

            // Crear nuevas opciones de días
            for (let dia = 1; dia <= diasEnMes; dia++) {
                const opcion = document.createElement('option');
                opcion.value = dia;
                opcion.text = dia;
                diasSelect.appendChild(opcion);
            }
        }

        // Llamar a la función una vez al cargar la página para mostrar los días iniciales
        actualizarDias();
    </script>
</body>
</html>
