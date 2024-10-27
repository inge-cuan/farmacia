<?php
session_start(); // Inicia la sesión

// Verificar si el usuario está logueado
if (isset($_SESSION['usuario'])) {
    // Destruir la sesión
    session_unset(); // Libera todas las variables de sesión
    session_destroy(); // Destruye la sesión

    // Redirigir a la página de inicio de sesión
    header("Location: login.php"); // Cambia 'login.php' por el nombre de tu página de inicio de sesión
    exit();
} else {
    // Si no hay sesión activa, redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
?>
