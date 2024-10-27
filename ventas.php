<?php
session_start();

// Verifica si el usuario tiene el puesto de 'root' o 'cajera'
if (!isset($_SESSION['puesto']) || ($_SESSION['puesto'] !== 'root' && $_SESSION['puesto'] !== 'cajera')) {
    header("Location: login.php"); // Redirige si no es root o cajera
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = '183492765';
$nombre_base_datos = 'farmacia';

$conn = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar mensaje de error
$error_message = "";

// Manejar la eliminación de una venta
if (isset($_POST['eliminar'])) {
    $id_venta = $_POST['id_venta'];

    // Confirmación antes de eliminar
    echo "<script>
        if (confirm('¿Realmente quieres eliminar esta venta?')) {
            window.location.href = 'ventas.php?eliminar_id=' + $id_venta;
        }
    </script>";
}

// Manejar la adición de una nueva venta
if (isset($_POST['agregar'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_usuario = $_POST['id_usuario'];
    $fecha_venta = $_POST['fecha_venta'];
    $total = $_POST['total'];

    // Validar campos
    if (empty($id_cliente) || empty($id_usuario) || empty($fecha_venta) || empty($total)) {
        $error_message = "Error: Por favor, completa todos los campos.";
    } else {
        $sql_insert = "INSERT INTO ventas (id_cliente, id_usuario, fecha_venta, total) VALUES ('$id_cliente', '$id_usuario', '$fecha_venta', '$total')";
        if ($conn->query($sql_insert) === TRUE) {
            $error_message = "Venta agregada con éxito.";
        } else {
            $error_message = "Error al agregar la venta: " . $conn->error;
        }
    }
}

// Manejar la eliminación a través de la URL
if (isset($_GET['eliminar_id'])) {
    $id_venta = $_GET['eliminar_id'];
    $sql_delete = "DELETE FROM ventas WHERE id_venta='$id_venta'";
    if ($conn->query($sql_delete) === TRUE) {
        $error_message = "Venta eliminada con éxito.";
    } else {
        $error_message = "Error al eliminar la venta: " . $conn->error;
    }
}

// Obtener la lista de ventas
$sql = "SELECT * FROM ventas";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - Panel <?php echo ($_SESSION['puesto'] === 'root') ? 'Root' : 'Cajera'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Sección de Ventas</h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <h3>Agregar Nueva Venta</h3>
    <form action="" method="post">
        <label for="id_cliente">ID Cliente:</label>
        <input type="text" id="id_cliente" name="id_cliente" required>

        <label for="id_usuario">ID Usuario:</label>
        <input type="text" id="id_usuario" name="id_usuario" required>

        <label for="fecha_venta">Fecha de Venta:</label>
        <input type="date" id="fecha_venta" name="fecha_venta" required>

        <label for="total">Total:</label>
        <input type="text" id="total" name="total" required>

        <input type="submit" name="agregar" value="Agregar Venta" class="button">
    </form>

    <h3>Lista de Ventas</h3>
    <table>
        <tr>
            <th>ID Venta</th>
            <th>ID Cliente</th>
            <th>ID Usuario</th>
            <th>Fecha de Venta</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_venta']; ?></td>
                    <td><?php echo $row['id_cliente']; ?></td>
                    <td><?php echo $row['id_usuario']; ?></td>
                    <td><?php echo $row['fecha_venta']; ?></td>
                    <td><?php echo $row['total']; ?></td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="id_venta" value="<?php echo $row['id_venta']; ?>">
                            <input type="submit" name="eliminar" value="Eliminar" class="button">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No hay ventas registradas.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Botón para regresar al inicio del panel -->
    <?php if ($_SESSION['puesto'] === 'root'): ?>
        <a href="root.php" class="button">Regresar al Panel de Administración - Root</a>
    <?php else: ?>
        <a href="cajera.php" class="button">Regresar al Inicio - Cajera</a>
    <?php endif; ?>

</body>
</html>

