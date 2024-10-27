<?php
session_start();

// Verifica si el usuario tiene el puesto de 'root', 'cajera' o 'almacenista'
if (!isset($_SESSION['puesto']) || ($_SESSION['puesto'] !== 'root' && $_SESSION['puesto'] !== 'cajera' && $_SESSION['puesto'] !== 'almacenista')) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "183492765", "farmacia");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Agregar producto
if (isset($_POST['agregar'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $precio = $conexion->real_escape_string($_POST['precio']);
    $stock = $conexion->real_escape_string($_POST['stock']);
    
    $sql = "INSERT INTO productos (nombre, precio, stock) VALUES ('$nombre', '$precio', '$stock')";
    if ($conexion->query($sql) === TRUE) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar producto: " . $conexion->error;
    }
}

// Eliminar producto
if (isset($_POST['eliminar'])) {
    $id_producto = $conexion->real_escape_string($_POST['id_producto']);
    $sql = "DELETE FROM productos WHERE id_producto='$id_producto'";
    if ($conexion->query($sql) === TRUE) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar producto: " . $conexion->error;
    }
}

// Consulta para obtener los productos
$query = "SELECT id_producto, nombre, precio, stock FROM productos";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
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
    </style>
</head>
<body>
    <h2>Productos</h2>

    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <?php if ($_SESSION['puesto'] === 'root' || $_SESSION['puesto'] === 'almacenista'): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar cada producto en la tabla
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_producto'] . "</td>";
                    echo "<td>" . $fila['nombre'] . "</td>";
                    echo "<td>" . $fila['precio'] . "</td>";
                    echo "<td>" . $fila['stock'] . "</td>";
                    if ($_SESSION['puesto'] === 'root' || $_SESSION['puesto'] === 'almacenista') {
                        echo "<td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id_producto' value='" . $fila['id_producto'] . "'>
                                    <input type='submit' name='eliminar' class='button' value='Eliminar' onclick=\"return confirm('¿Realmente quieres eliminar el producto?');\">
                                </form>
                              </td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron productos</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if ($_SESSION['puesto'] === 'root' || $_SESSION['puesto'] === 'almacenista'): ?>
        <h2>Agregar Nuevo Producto</h2>
        <form method="post">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Precio:</label>
                <input type="text" name="precio" required>
            </div>
            <div class="form-group">
                <label>Stock:</label>
                <input type="number" name="stock" required>
            </div>
            <input type="submit" name="agregar" class="button" value="Agregar Producto">
        </form>
    <?php endif; ?>

    <!-- Botón para regresar al inicio del panel de la cajera -->
    <?php if ($_SESSION['puesto'] === 'cajera'): ?>
        <a href="cajera.php" class="button">Regresar al Inicio - Cajera</a>
    <?php endif; ?>

    <!-- Botón para regresar al inicio del panel del root -->
    <?php if ($_SESSION['puesto'] === 'root'): ?>
        <a href="root.php" class="button">Volver al Panel Inicial - Root</a>
    <?php endif; ?>

    <!-- Botón para regresar al inicio del panel del almacenista -->
    <?php if ($_SESSION['puesto'] === 'almacenista'): ?>
        <a href="almacenista.php" class="button">Regresar al Inicio - Almacenista</a>
    <?php endif; ?>

    <?php
    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>
