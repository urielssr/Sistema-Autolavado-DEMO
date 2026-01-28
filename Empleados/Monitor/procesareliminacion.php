<?php
// Incluir el archivo de conexión a PostgreSQL
include('../../conexion/conexion_be.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $idCita = $_GET['id'];

    // Eliminar la cita de la base de datos
    $stmt = $connection->prepare("DELETE FROM citas WHERE id = :idCita");
    $stmt->bindParam(":idCita", $idCita, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();

    // Cierra la conexión
    $connection = null;

    // Respuesta de éxito (puedes personalizar según tus necesidades)
    echo "Cita eliminada correctamente";
    exit();
}
?>
