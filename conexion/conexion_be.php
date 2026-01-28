<?php

$host = "localhost";      // Cambia esto al host de tu base de datos PostgreSQL
$port = 5432;             // Cambia esto al puerto de tu base de datos PostgreSQL
$dbname = "Autolavado";    // Cambia esto al nombre de tu base de datos
$user = "postgres";     // Cambia esto a tu nombre de usuario
$password = "123"; // Cambia esto a tu contraseña


try {
    $connection = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
