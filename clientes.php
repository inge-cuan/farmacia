<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['puesto'])) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "183492765", "farmacia");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Agregar cliente
if (isset($_POST['agregar'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    
    $sql = "INSERT INTO clientes (nombre, apellido, direccion, telefono) VALUES ('$nombre', '$apellido', '$direccion', '$telefono')";
    if ($conexion->query($sql) === TRUE) {
        echo "Cliente agregado correctamente.";
    } else {
        echo "Error al agregar cliente: " . $conexion->error;
    }
}

// Eliminar cliente
if (isset($_POST['eliminar'])) {
    $id_cliente = $conexion->real_escape_string($_POST['id_cliente']);
    $sql = "DELETE FROM clientes WHERE id_cliente='$id_cliente'";
    if ($conexion->query($sql) === TRUE) {
        echo "Cliente eliminado correctamente.";
    } else {
        echo "Error al eliminar cliente: " . $conexion->error;
    }
}

// Consulta para obtener los clientes
$query = "SELECT id_cliente, nombre, apellido, direccion, telefono FROM clientes";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h2>Clientes</h2>

    <table>
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar cada cliente en la tabla
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_cliente'] . "</td>";
                    echo "<td>" . $fila['nombre'] . "</td>";
                    echo "<td>" . $fila['apellido'] . "</td>";
                    echo "<td>" . $fila['direccion'] . "</td>";
                    echo "<td>" . $fila['telefono'] . "</td>";
                    echo "<td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id_cliente' value='" . $fila['id_cliente'] . "'>
                                <input type='submit' name='eliminar' class='button' value='Eliminar'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No se encontraron clientes</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Agregar Nuevo Cliente</h2>
    <form method="post">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label>Apellido:</label>
            <input type="text" name="apellido" required>
        </div>
        <div class="form-group">
            <label>Dirección:</label>
            <input type="text" name="direccion" required>
        </div>
        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono" required>
        </div>
        <input type="submit" name="agregar" class="button" value="Agregar Cliente">
    </form>

    <!-- Botones de navegación según el puesto -->
    <?php if ($_SESSION['puesto'] === 'cajera'): ?>
        <a href="cajera.php" class="button">Volver al Panel Inicial - Cajera</a>
    <?php elseif ($_SESSION['puesto'] === 'root'): ?>
        <a href="root.php" class="button">Volver al Panel Inicial - Root</a>
    <?php endif; ?>

    <?php
    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>
