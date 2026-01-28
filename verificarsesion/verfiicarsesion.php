<?php
// Verifica si la sesión está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    

    echo "<div class='centrado'>Hola, $usuario. ¡Estás actualmente en sesión!</div>";
} else {
    echo "<div class='centrado'>No has iniciado sesión. Por favor, inicia sesión <a href='login.php'>aquí</a>.</div>";
}
?>
