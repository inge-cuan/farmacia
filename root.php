<?php
session_start();

// Verifica si el usuario tiene el puesto de 'root'
if (!isset($_SESSION['puesto']) || $_SESSION['puesto'] !== 'root') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Root</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .button {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            width: 200px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .logout {
            background-color: #d9534f;
        }

        .logout:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Panel de Administración - Root</h2>

        <!-- Botones para cada sección -->
        <a href="usuario.php" class="button">Usuario</a>
        <a href="proveedores.php" class="button">Proveedores</a>
        <a href="clientes.php" class="button">Clientes</a>
        <a href="ventas.php" class="button">Ventas</a>
        <a href="productos.php" class="button">Productos</a>
        <a href="compras.php" class="button">Compras</a>

        <!-- Botón de Cerrar sesión -->
        <a href="logout.php" class="button logout">Cerrar sesión</a>
    </div>
</body>
</html>

