<?php
session_start();

// Verifica si el usuario tiene el puesto de 'root', 'gerente' o 'almacenista'
if (!isset($_SESSION['puesto']) || ($_SESSION['puesto'] !== 'root' && $_SESSION['puesto'] !== 'gerente' && $_SESSION['puesto'] !== 'almacenista')) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "183492765", "farmacia");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Agregar venta (solo si el usuario es root)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar']) && $_SESSION['puesto'] === 'root') {
    $id_cliente = $_POST['id_cliente'];
    $id_usuario = $_POST['id_usuario'];
    $fecha_venta = $_POST['fecha_venta'];
    $total = $_POST['total'];

    $query = "INSERT INTO ventas (id_cliente, id_usuario, fecha_venta, total) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iisd", $id_cliente, $id_usuario, $fecha_venta, $total);
    $stmt->execute();
    $stmt->close();
}

// Eliminar venta (solo si el usuario es root)
if (isset($_GET['eliminar']) && $_SESSION['puesto'] === 'root') {
    $id_venta = $_GET['eliminar'];
    $query = "DELETE FROM ventas WHERE id_venta = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $stmt->close();
}

// Consulta para obtener la lista de ventas
$query = "SELECT * FROM ventas";
$resultado = $conexion->query($query);

// Consulta para obtener la lista de proveedores (para almacenista)
$query_proveedores = "SELECT * FROM proveedores";
$resultado_proveedores = $conexion->query($query_proveedores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Ventas</title>
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

        .delete-button {
            background-color: #d9534f;
        }

        .delete-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <h2>Administración de Ventas</h2>

    <!-- Formulario para agregar una nueva venta (solo visible si el usuario es root) -->
    <?php if ($_SESSION['puesto'] === 'root'): ?>
        <form method="POST" action="">
            <input type="number" name="id_cliente" placeholder="ID Cliente" required>
            <input type="number" name="id_usuario" placeholder="ID Usuario" required>
            <input type="date" name="fecha_venta" required>
            <input type="number" step="0.01" name="total" placeholder="Total de la venta" required>
            <button type="submit" name="agregar" class="button">Agregar Venta</button>
        </form>
    <?php endif; ?>

    <!-- Tabla para mostrar las ventas -->
    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>ID Cliente</th>
                <th>ID Usuario</th>
                <th>Fecha de Venta</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar cada venta en la tabla
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_venta'] . "</td>";
                    echo "<td>" . $fila['id_cliente'] . "</td>";
                    echo "<td>" . $fila['id_usuario'] . "</td>";
                    echo "<td>" . $fila['fecha_venta'] . "</td>";
                    echo "<td>" . $fila['total'] . "</td>";
                    if ($_SESSION['puesto'] === 'root') {
                        echo "<td><a href='?eliminar=" . $fila['id_venta'] . "' class='button delete-button'>Eliminar</a></td>";
                    } else {
                        echo "<td>Sin permisos</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No se encontraron ventas</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Tabla para mostrar los proveedores (solo visible si el usuario es almacenista) -->
    <?php if ($_SESSION['puesto'] === 'almacenista'): ?>
        <h2>Lista de Proveedores</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Proveedor</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar cada proveedor en la tabla
                if ($resultado_proveedores->num_rows > 0) {
                    while ($fila_proveedor = $resultado_proveedores->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $fila_proveedor['id_proveedor'] . "</td>";
                        echo "<td>" . $fila_proveedor['nombre'] . "</td>";
                        echo "<td>" . $fila_proveedor['telefono'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontraron proveedores</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Botón para regresar al inicio según el puesto del usuario -->
    <?php if ($_SESSION['puesto'] === 'root'): ?>
        <a href="root.php" class="button">Regresar al Panel de Administración - Root</a>
    <?php elseif ($_SESSION['puesto'] === 'gerente'): ?>
        <a href="gerente.php" class="button">Regresar al Panel de Administración - Gerente</a>
    <?php elseif ($_SESSION['puesto'] === 'almacenista'): ?>
        <a href="almacenista.php" class="button">Regresar al Panel del Almacenista</a>
    <?php endif; ?>

    <?php
    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>


