<?php
session_start();

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

// Validar y procesar los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
    $apellido = isset($_POST['apellido']) ? $conn->real_escape_string($_POST['apellido']) : '';
    $contraseña = isset($_POST['contraseña']) ? $conn->real_escape_string($_POST['contraseña']) : '';
    $puesto = isset($_POST['puesto']) ? $conn->real_escape_string($_POST['puesto']) : '';

    // Verificar que no haya campos vacíos
    if (empty($nombre) || empty($apellido) || empty($contraseña) || empty($puesto)) {
        $error_message = "Error: Por favor, completa todos los campos.";
    } else {
        // Consultar la base de datos
        $sql = "SELECT * FROM usuario WHERE nombre='$nombre' AND apellido='$apellido' AND contraseña='$contraseña' AND puesto='$puesto'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Usuario encontrado, establecer sesión
            $_SESSION['usuario'] = $nombre;
            $_SESSION['puesto'] = $puesto;

            // Redirigir según el puesto
            switch ($puesto) {
                case 'gerente':
                    header("Location: gerente.php");
                    break;
                case 'cajera':
                    header("Location: cajera.php");
                    break;
                case 'almacenista':
                    header("Location: almacenista.php");
                    break;
                case 'root':
                    header("Location: root.php");
                    break;
                default:
                    $error_message = "Acceso restringido para el puesto de $puesto.";
                    break;
            }
            exit();
        } else {
            $error_message = "Credenciales incorrectas. Inténtalo de nuevo.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #007BFF; /* Color de fondo azul */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            color: white; /* Color del texto en el formulario */
        }

        h2 {
            color: white; /* Color blanco para el título */
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #0056b3; /* Color de fondo azul oscuro */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #004494; /* Azul más oscuro al pasar el ratón */
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        
        <!-- Mostrar mensaje de error si existe -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <label for="puesto">Puesto:</label>
            <select id="puesto" name="puesto" required>
                <option value="">Selecciona tu puesto</option>
                <option value="gerente">gerente</option>
                <option value="cajera">cajera</option>
                <option value="almacenista">almacenista</option>
                <option value="root">root</option>
            </select>
            
            <input type="submit" value="Iniciar sesión">
        </form>
    </div>
</body>
</html>
