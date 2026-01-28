<?php
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

include('../verificarsesion/verfiicarsesion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilologeado.css">
    <title>Tu Autolavado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .calendario {
            margin-top: 20px;
        }
        .calendario table {
            width: 100%;
            border-collapse: collapse;
        }
        .calendario th, .calendario td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .calendario caption {
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .ocupado {
            background-color: #f2f2f2; /* Color gris claro para días con cita */
        }
        .disponible {
            background-color: #ccffcc; /* Color verde claro para días sin cita */
        }
        .leyenda {
            margin-top: 20px;
            font-size: 0.9em;
        }
        .leyenda span {
            display: inline-block;
            margin-right: 20px;
        }
        .leyenda .ocupado {
            background-color: #f2f2f2;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .leyenda .disponible {
            background-color: #ccffcc;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .select-mes {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
            background-color: #f2f2f2;
        }
        .boton-mes {
            background-color: #4CAF50; /* Color verde */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .boton-mes:hover {
            background-color: #45a049; /* Cambio de color al pasar el mouse */
        }
       
        /* Estilo para el contenedor del mapa */
        #mapa {
            width: 100%;
            height: 300px;
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
            margin-bottom: 20px; /* Espacio entre el mapa y el botón */
        }
        /* Estilo para el botón de cómo llegar */
        #como-llegar-btn {
            display: block;
            width: 100%;
            max-width: 200px; /* Ancho máximo para evitar que el botón sea demasiado grande */
            margin: 0 auto; /* Centrar el botón horizontalmente */
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50; /* Color verde */
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        #como-llegar-btn:hover {
            background-color: #45a049; /* Cambio de color al pasar el mouse */
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="../img/logo_2.1.png" alt="Tu Logo">
    </div>
    <nav>
        <ul>
            <li><a href="../Agenda/agenda.php">Agenda</a></li>
            <li><a href="#" id="cerrarSesion" class="boton">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>

<section class="contenido">
    <div class="imagen-fondo">
        <img src="../img/dims.jpg" alt="Imagen de Fondo">
        <div class="texto">
            <span class="linea1">PRESERVANDO LA</span>
            <span class="linea2">BELLEZA DE TU VEHÍCULO,</span>
            <span class="linea3"> LAVADA TRAS LAVADA.</span>
            <button id="agendaCitaBtn" class="boton">Agenda Cita</button>
        </div>
    </div>
</section>
<!-- Contenedor del mapa -->
<div id="mapa"></div>

<!-- Botón de cómo llegar -->
<button id="como-llegar-btn">Cómo Llegar</button>

<!-- Script de inicialización del mapa -->
<script>
    function initMap() {
        var ubicacion = {lat: 18.453528, lng: -96.357231};
        var mapa = new google.maps.Map(
            document.getElementById('mapa'), {zoom: 15, center: ubicacion});
        var marcador = new google.maps.Marker({position: ubicacion, map: mapa});

        // Agregar evento click al botón
        document.getElementById('como-llegar-btn').addEventListener('click', function() {
            // Abrir Google Maps con la ubicación actual del autolavado
            window.open('https://www.google.com/maps/dir/?api=1&destination=' + ubicacion.lat + ',' + ubicacion.lng);
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXih5O-v7Jgt7q14G9kxKcfENtZImhqJ4&callback=initMap" async defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Obtener todos los días del calendario
        const dias = document.querySelectorAll('.calendario td');
        
        dias.forEach(function(dia) {
            dia.addEventListener('click', function() {
                // Verifica si el día está disponible
                if (dia.classList.contains('disponible')) {
                    const diaSeleccionado = dia.textContent;
                    const mesSeleccionado = document.getElementById('mes').value;
                    
                    // Muestra un mensaje de confirmación para agendar la cita
                    const confirmacion = confirm(`¿Quieres agendar una cita para el día ${diaSeleccionado} del mes ${mesSeleccionado}?`);

                    if (confirmacion) {
                        // Redirige al formulario de agenda con el día seleccionado
                        window.location.href = `../Agenda/agenda.php?dia=${diaSeleccionado}&mes=${mesSeleccionado}`;
                    }
                } else {
                    // Si el día está ocupado, mostrar un mensaje informativo
                    alert("Este día ya está ocupado. Por favor, elige otro día.");
                }
            });
        });
    });
</script>
<div class="calendario">
    <form method="get" action="">
        <label for="mes" class="select-mes">Selecciona un mes:</label>
        <select name="mes" id="mes" class="select-mes">
            <?php
            $mesActual = date('n');
            for ($i = 1; $i <= 12; $i++) {
                $selected = ($i == $mesActual) ? 'selected' : '';
                echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="boton-mes">Consultar</button>
    </form>

    <table>
        <caption>Calendario de Disponibilidad</caption>
        <thead>
            <tr>
                <th>Lun</th>
                <th>Mar</th>
                <th>Mie</th>
                <th>Jue</th>
                <th>Vie</th>
                <th>Sab</th>
                <th>Dom</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener mes seleccionado
            $mes = isset($_GET['mes']) ? $_GET['mes'] : date('n');

            // Obtener el primer día del mes y el número de días en el mes
            $primerDiaMes = new DateTime(date('Y') . "-$mes-01");
            $diasEnMes = $primerDiaMes->format('t');

            // Obtener citas de la base de datos
            $citas = [];
            $stmt = $conexion->query("SELECT dia FROM citas WHERE mes = $mes"); // Solo citas de este mes
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $citas[] = $row['dia'];
            }

            // Limitar citas por día (máximo 10 citas por día)
            $limiteCitas = 10;
            $ocupadosPorDia = [];
            foreach ($citas as $dia) {
                if (!isset($ocupadosPorDia[$dia])) {
                    $ocupadosPorDia[$dia] = 1;
                } else {
                    $ocupadosPorDia[$dia]++;
                }
            }

            $dia = 1;
            $contador = 0;
            while ($dia <= $diasEnMes) {
                echo "<tr>";
                for ($i = 1; $i <= 7; $i++) {
                    if ($contador < $primerDiaMes->format('N') - 1 || $dia > $diasEnMes) {
                        echo "<td>&nbsp;</td>";
                    } else {
                        $clase = 'disponible';
                        if (isset($ocupadosPorDia[$dia]) && $ocupadosPorDia[$dia] >= $limiteCitas) {
                            $clase = 'ocupado';
                        }
                        echo "<td class='$clase'>$dia</td>";
                        $dia++;
                    }
                    $contador++;
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="leyenda">
    <span class="ocupado"></span> Días ocupados
    <span class="disponible"></span> Días disponibles
</div>

<script>
    document.getElementById('cerrarSesion').addEventListener('click', function() {
        // Redirige al usuario a la página que cerrará la sesión
        window.location.href = 'cerrar_sesion.php';
    });
    document.getElementById('agendaCitaBtn').addEventListener('click', function() {
    // Redirige al usuario a la página de agenda (cambia 'ruta_de_tu_pagina' con la ruta correcta)
    window.location.href = '../Agenda/agenda.php';
});
</script>

<!-- El resto de tu contenido va aquí -->

</body>
</html>
