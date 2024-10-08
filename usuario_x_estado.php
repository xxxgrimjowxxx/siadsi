<?php
// Incluir el archivo de conexión
include('conexion.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . $_SESSION['nombre'];

// Inicializar las variables
$informe_usuarios = '';
$mensaje_error = '';

// Verificar si se ha enviado el formulario
if (isset($_POST['buscar'])) {
    $estado = $_POST['estado'];

    // Ajustar la consulta según la selección del usuario
    if ($estado == "todos") {
        $consulta = "SELECT identificacion, nombre, apellido, estado FROM usuarios"; // Incluye 'identificacion'
    } else {
        $consulta = "SELECT identificacion, nombre, apellido, estado FROM usuarios WHERE estado = '$estado'"; // Incluye 'identificacion'
    }

    $resultado = mysqli_query($conn, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Construir tabla de informe
        $informe_usuarios .= "<h2>Informe de Usuarios</h2>";
        $informe_usuarios .= "<table>";
        $informe_usuarios .= "<tr><th>Identificación</th><th>Nombre</th><th>Apellido</th><th>Estado</th></tr>"; // Añadir columna Identificación

        // Mostrar los datos de los usuarios según el estado seleccionado
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $informe_usuarios .= "<tr>";
            $informe_usuarios .= "<td>" . $fila['identificacion'] . "</td>"; // Mostrar Identificación
            $informe_usuarios .= "<td>" . $fila['nombre'] . "</td>";
            $informe_usuarios .= "<td>" . $fila['apellido'] . "</td>";
            $informe_usuarios .= "<td>" . $fila['estado'] . "</td>";
            $informe_usuarios .= "</tr>";
        }

        $informe_usuarios .= "</table>";
    } else {
        $mensaje_error = "No se encontraron usuarios en el estado seleccionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Usuarios</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
         body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    min-height: 100vh;
    overflow-y: auto; /* Habilitar el desplazamiento vertical */
    padding: 20px;
}        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        h1, h2 {
            color: #FF0000;
        }
        h1 {
            margin-bottom: 0.5rem;
        }
        h2 {
            margin-bottom: 1rem;
        }
        label {
            font-weight: bold;
            color: #FF0000;
        }
        select {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #FF0000;
            border-radius: 4px;
            appearance: none;
            background-color: #fff;
            cursor: pointer;
        }
        input[type="submit"] {
            background-color: #FF0000;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }
        input[type="submit"]:hover {
            background-color: #CC0000;
        }
        .results {
            margin-top: 1rem;
            background-color: #f9f9f9;
            padding: 1rem;
            border-radius: 4px;
            text-align: left;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .back-button {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #CC0000;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            color: #333;
            font-size: 0.9rem;
        }
        /* Acrónimo y su significado */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
    border-collapse: collapse; /* Elimina el espacio entre los bordes de las celdas */
    width: 100%; /* La tabla ocupará todo el ancho disponible */
}

table, th, td {
    border: 1px solid red; /* Bordes de 1px de grosor y color negro para la tabla, encabezados y celdas */
}

th, td {
    padding: 8px; /* Espacio interno de 8px en las celdas y encabezados */
    text-align: left; /* Alineación del texto a la izquierda en las celdas */
}

th {
    background-color: #f2f2f2; /* Fondo gris claro para los encabezados */
}

    </style>
</head>
<body>
    <!-- Encabezado del sistema -->
    <div class="header">
        <h1>SIADSI</h1>
        <p><span>Sistema de Administración de Sistemas</span></p>
    </div>

    <!-- Contenedor para el formulario de búsqueda -->
    <div class="container">
        <h2>Búsqueda de Usuarios por Estado</h2>

        <!-- Formulario para seleccionar el estado -->
        <form method="POST" action="">
            <label for="estado">Seleccionar Estado:</label>
            <select name="estado" id="estado" required>
            <option value="" selected>Selecciona una opción</option>    
            <option value="todos">Todos</option>
                <option value="Activo">Activos</option>
                <option value="Inactivo">Inactivos</option>
            </select>
            <input type="submit" name="buscar" value="Buscar">
        </form>
    </div>

    <!-- Mostrar resultados de la búsqueda -->
    <div class="results">
        <?php
        if ($informe_usuarios) {
            echo $informe_usuarios;
        } elseif ($mensaje_error) {
            echo "<div class='error'>$mensaje_error</div>";
        }
        ?>
    </div>

    <!-- Botón para volver al menú -->
    <a href="menuinformes.php" class="back-button">Volver</a>

    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
