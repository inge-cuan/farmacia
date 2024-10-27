<?php
session_start();

// Verificar si el usuario es gerente o root
if (!isset($_SESSION['puesto']) || ($_SESSION['puesto'] !== 'gerente' && $_SESSION['puesto'] !== 'root')) {
    echo "Acceso restringido. Solo el gerente o root pueden acceder a esta sección.";
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = '183492765';
$nombre_base_datos = 'farmacia';

$conn = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Agregar usuario
if (isset($_POST['agregar'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $contraseña = $conn->real_escape_string($_POST['contraseña']);
    $puesto = $conn->real_escape_string($_POST['puesto']);
    $telefono = $conn->real_escape_string($_POST['telefono']);

    $sql = "INSERT INTO usuario (nombre, apellido, contraseña, puesto, telefono) VALUES ('$nombre', '$apellido', '$contraseña', '$puesto', '$telefono')";
    if ($conn->query($sql) === TRUE) {
        echo "Usuario agregado correctamente.";
    } else {
        echo "Error al agregar usuario: " . $conn->error;
    }
}

// Eliminar usuario
if (isset($_POST['eliminar'])) {
    $id_usuario = $conn->real_escape_string($_POST['id_usuario']);
    $sql = "DELETE FROM usuario WHERE id_usuario='$id_usuario'";
    if ($conn->query($sql) === TRUE) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar usuario: " . $conn->error;
    }
}

// Consultar usuarios
$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            max-width: 700px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Puesto</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_usuario']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['apellido']; ?></td>
                <td><?php echo $row['puesto']; ?></td>
                <td><?php echo $row['telefono']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">
                        <input type="submit" name="eliminar" class="button" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Agregar Nuevo Usuario</h2>
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
            <label>Contraseña:</label>
            <input type="password" name="contraseña" required>
        </div>
        <div class="form-group">
            <label>Puesto:</label>
            <input type="text" name="puesto" required>
        </div>
        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono" required>
        </div>
        <input type="submit" name="agregar" class="button" value="Agregar Usuario">
    </form>

    <!-- Botón de regreso al inicio del root -->
    <?php if ($_SESSION['puesto'] === 'root'): ?>
        <a href="root.php" class="button">Inicio del Panel - Root</a>
    <?php else: ?>
        <a href="gerente.php" class="button">Regresar al Panel de Administración - Gerente</a>
    <?php endif; ?>
    
</div>
</body>
</html>

<?php
$conn->close();
?>
