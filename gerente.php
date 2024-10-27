<?php
session_start();

// Verifica si el usuario tiene el puesto de 'gerente'
if (!isset($_SESSION['puesto']) || $_SESSION['puesto'] !== 'gerente') {
    header("Location: login.php"); // Redirige si no es gerente
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Gerente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column; /* Cambia la dirección del flex para alinear los elementos verticalmente */
        }

        .container {
            text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido al Panel de Gerente</h2>
        <a href="usuario.php" class="button">Usuario</a>
        <a href="compras.php" class="button">Compras</a>
        <a href="proveedores.php" class="button">Proveedores</a>
        <a href="logout.php" class="button">Cerrar Sesión</a> <!-- Botón de cerrar sesión -->
    </div>
</body>
</html>
