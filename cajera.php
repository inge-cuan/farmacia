<?php
session_start();

// Verifica si el usuario está logueado y es cajera
if (!isset($_SESSION['puesto']) || $_SESSION['puesto'] !== 'cajera') {
    header("Location: login.php");
    exit();
}

// Cerrar sesión
if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cajera</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            margin: 0;
            padding: 50px;
        }

        .button {
            background-color: #007BFF;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin: 10px;
            font-size: 16px;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Bienvenido al Panel de Cajera</h1>
    <p>Seleccione una opción:</p>

    <a href="clientes.php" class="button">Clientes</a>
    <a href="ventas.php" class="button">Ventas</a>
    <a href="productos.php" class="button">Productos</a>

    <form method="post">
        <input type="submit" name="cerrar_sesion" class="button" value="Cerrar Sesión">
    </form>
</body>
</html>
