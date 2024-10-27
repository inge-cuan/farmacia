<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el puesto de almacenista
if (!isset($_SESSION['puesto']) || $_SESSION['puesto'] !== 'almacenista') {
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
    <title>Inicio Almacenista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin: 10px;
            display: inline-block;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Bienvenido, Almacenista</h1>

    <div>
        <a href="proveedores.php" class="button">Proveedores</a>
        <a href="productos.php" class="button">Productos</a>
        <a href="compras.php" class="button">Compras</a>
    </div>

    <form method="post" style="margin-top: 20px;">
        <input type="submit" name="cerrar_sesion" class="button" value="Cerrar Sesión">
    </form>
</body>
</html>
