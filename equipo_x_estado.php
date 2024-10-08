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
$informe_equipos = '';
$mensaje_error = '';

// Verificar si se ha enviado el formulario
if (isset($_POST['buscar'])) {
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);

    // Ajustar la consulta según la selección del estado
    if ($estado == "todos") {
        $consulta = "SELECT usuarios.identificacion, usuarios.nombre, usuarios.apellido, equipo.num_inventario, equipo.estado 
                     FROM equipo
                     INNER JOIN usuarios ON equipo.usuario_id_eq = usuarios.id_usuario"; // Relaciona usuario_id_eq con id_usuario
    } else {
        $consulta = "SELECT usuarios.identificacion, usuarios.nombre, usuarios.apellido, equipo.num_inventario, equipo.estado 
                     FROM equipo
                     INNER JOIN usuarios ON equipo.usuario_id_eq = usuarios.id_usuario
                     WHERE equipo.estado = '$estado'"; // Relaciona usuario_id_eq con id_usuario y filtra por estado
    }

    $resultado = mysqli_query($conn, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Construir la tabla del informe
        $informe_equipos .= "<h2>Informe de Equipos por Estado</h2>";
        $informe_equipos .= "<table>";
        $informe_equipos .= "<tr><th>Identificación</th><th>Nombre</th><th>Apellido</th><th>Número de Inventario</th><th>Estado</th></tr>"; // Añadir identificación, nombre y apellido

        // Mostrar los datos de los equipos según el estado seleccionado
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $informe_equipos .= "<tr>";
            $informe_equipos .= "<td>" . $fila['identificacion'] . "</td>";
            $informe_equipos .= "<td>" . $fila['nombre'] . "</td>";
            $informe_equipos .= "<td>" . $fila['apellido'] . "</td>";
            $informe_equipos .= "<td>" . $fila['num_inventario'] . "</td>";
            $informe_equipos .= "<td>" . $fila['estado'] . "</td>";
            $informe_equipos .= "</tr>";
        }

        $informe_equipos .= "</table>";
    } else {
        $mensaje_error = "No se encontraron equipos en el estado seleccionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Equipos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            overflow-y: auto;
            padding: 20px;
        }

        .container {
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

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }

        table, th, td {
            border: 1px solid red; /* Bordes de 1px de grosor y color rojo */
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>SIADSI</h1>
        <h2>Búsqueda de Equipos por Estado</h2>

        <!-- Formulario para seleccionar el estado -->
        <form method="POST" action="">
            <label for="estado">Seleccionar Estado:</label>
            <select name="estado" id="estado" required>
                <option value="todos" selected>Todos</option>
                <option value="En Uso">En Uso</option>
                <option value="Disponible">Disponible</option>
                <option value="En reparación">En reparación</option>
                <option value="Dado de baja">Dado de baja</option>
            </select>
            <input type="submit" name="buscar" value="Buscar">
        </form>

        <!-- Mostrar resultados de la búsqueda -->
        <div class="results">
            <?php
            if ($informe_equipos) {
                echo $informe_equipos;
            } elseif ($mensaje_error) {
                echo "<div class='error'>$mensaje_error</div>";
            }
            ?>
        </div>

        <!-- Botón Volver -->
        <a href="menuinformes.php" class="back-button">Volver</a>

    </div>

    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
