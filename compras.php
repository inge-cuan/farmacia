<?php
session_start();

// Verifica si el usuario es gerente, root o almacenista
if (!isset($_SESSION['puesto']) || ($_SESSION['puesto'] !== 'gerente' && $_SESSION['puesto'] !== 'root' && $_SESSION['puesto'] !== 'almacenista')) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "183492765", "farmacia");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Agregar compra
if (isset($_POST['agregar'])) {
    $id_proveedor = $conexion->real_escape_string($_POST['id_proveedor']);
    $fecha_compra = $conexion->real_escape_string($_POST['fecha_compra']);
    $total = $conexion->real_escape_string($_POST['total']);

    $sql = "INSERT INTO compras (id_proveedor, fecha_compra, total) VALUES ('$id_proveedor', '$fecha_compra', '$total')";
    if ($conexion->query($sql) === TRUE) {
        echo "Compra agregada correctamente.";
    } else {
        echo "Error al agregar compra: " . $conexion->error;
    }
}

// Eliminar compra
if (isset($_POST['eliminar'])) {
    $id_compra = $conexion->real_escape_string($_POST['id_compra']);
    $sql = "DELETE FROM compras WHERE id_compra='$id_compra'";
    if ($conexion->query($sql) === TRUE) {
        echo "Compra eliminada correctamente.";
    } else {
        echo "Error al eliminar compra: " . $conexion->error;
    }
}

// Consulta para obtener las compras realizadas
$query = "SELECT id_compra, id_proveedor, fecha_compra, total FROM compras";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras Realizadas</title>
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
    <h2>Compras Realizadas</h2>

    <table>
        <thead>
            <tr>
                <th>ID Compra</th>
                <th>ID Proveedor</th>
                <th>Fecha de Compra</th>
                <th>Total</th>
                <?php if ($_SESSION['puesto'] === 'root' || $_SESSION['puesto'] === 'gerente'): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar cada compra en la tabla
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_compra'] . "</td>";
                    echo "<td>" . $fila['id_proveedor'] . "</td>";
                    echo "<td>" . $fila['fecha_compra'] . "</td>";
                    echo "<td>" . $fila['total'] . "</td>";
                    // Solo los puestos que pueden eliminar tienen la opción de hacerlo
                    if ($_SESSION['puesto'] === 'root' || $_SESSION['puesto'] === 'gerente') {
                        echo "<td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id_compra' value='" . $fila['id_compra'] . "'>
                                    <input type='submit' name='eliminar' class='button' value='Eliminar'>
                                </form>
                              </td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron compras</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if ($_SESSION['puesto'] === 'root' || $_SESSION['puesto'] === 'gerente' || $_SESSION['puesto'] === 'almacenista'): ?>
        <h2>Agregar Nueva Compra</h2>
        <form method="post">
            <div class="form-group">
                <label>ID Proveedor:</label>
                <input type="text" name="id_proveedor" required>
            </div>
            <div class="form-group">
                <label>Fecha de Compra:</label>
                <input type="date" name="fecha_compra" required>
            </div>
            <div class="form-group">
                <label>Total:</label>
                <input type="text" name="total" required>
            </div>
            <input type="submit" name="agregar" class="button" value="Agregar Compra">
        </form>
    <?php endif; ?>

    <!-- Botón para regresar al inicio del panel del root -->
    <?php if ($_SESSION['puesto'] === 'root'): ?>
        <a href="root.php" class="button">Volver al Panel Inicial - Root</a>
    <?php elseif ($_SESSION['puesto'] === 'gerente'): ?>
        <a href="gerente.php" class="button">Volver al Inicio del Panel - Gerente</a>
    <?php elseif ($_SESSION['puesto'] === 'almacenista'): ?>
        <a href="almacenista.php" class="button">Volver al Inicio del Panel - Almacenista</a>
    <?php endif; ?>

    <?php
    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>

