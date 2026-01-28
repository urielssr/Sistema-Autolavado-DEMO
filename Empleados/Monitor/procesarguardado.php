<?php
// Incluir el archivo de conexión a PostgreSQL
include('../../conexion/conexion_be.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los datos del formulario
    $nuevaFecha = $_POST['nuevaFecha'];
    $idCita = $_POST['idCita'];

    // Divide la nueva fecha en día y mes
    list($nuevoAnio, $nuevoMes, $nuevoDia) = explode('-', $nuevaFecha);

    try {
        // Verifica si la nueva fecha ya existe
        $stmtVerificar = $connection->prepare("SELECT COUNT(*) FROM citas WHERE dia = :nuevoDia AND mes = :nuevoMes");
        $stmtVerificar->bindParam(":nuevoDia", $nuevoDia, PDO::PARAM_INT);
        $stmtVerificar->bindParam(":nuevoMes", $nuevoMes, PDO::PARAM_INT);
        $stmtVerificar->execute();
        $count = $stmtVerificar->fetchColumn();
        $stmtVerificar->closeCursor();

        // Si la fecha ya existe, muestra una alerta y redirige de vuelta al monitor
        if ($count > 0) {
            echo "<script>alert('La fecha seleccionada ya existe. Por favor, elige otra.'); window.location.href = 'monitor.php';</script>";
            exit();
        }

        // Actualiza la cita en la base de datos (solo día y mes)
        $stmt = $connection->prepare("UPDATE citas SET dia = :nuevoDia, mes = :nuevoMes WHERE id = :idCita");
        $stmt->bindParam(":nuevoDia", $nuevoDia, PDO::PARAM_INT);
        $stmt->bindParam(":nuevoMes", $nuevoMes, PDO::PARAM_INT);
        $stmt->bindParam(":idCita", $idCita, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();

        // Redirige al usuario de vuelta al monitor después de procesar la edición
        header("Location: monitor.php");
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    } finally {
        // Cierra la conexión
        $connection = null;
    }
}
?>
