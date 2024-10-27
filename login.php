<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        form {
            background-color: #007BFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            color: white;
        }

        h2 {
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
            background-color: #0056b3;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #004494;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form action="procesar_login.php" method="post">
        <h2>Iniciar sesión</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required>
        
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required>
        
        <label for="puesto">Selecciona tu puesto:</label>
        <select name="puesto" required>
            <option value="gerente">gerente</option>
            <option value="cajera">cajera</option>
            <option value="almacenista">almacenista</option>
            <option value="root">root</option>
        </select>
        
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html> 