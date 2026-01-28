<?php
session_start();
session_destroy();
header('Location: ../Logeo/Login.php'); // Redirige al usuario a la página de inicio de sesión
exit;
?>
