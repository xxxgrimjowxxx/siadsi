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
$datos_periferico = '';
$mensaje_error = '';

// Verificar si se ha enviado el formulario
if (isset($_POST['buscar'])) {
    $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);

    // Consulta para obtener los detalles del usuario y sus periféricos
    $consulta = "SELECT usuarios.identificacion, usuarios.nombre, usuarios.apellido, 
                        periferico.num_inventario, periferico.num_serie, periferico.tipo, 
                        periferico.fecha_compra, periferico.estado
                 FROM usuarios 
                 INNER JOIN periferico ON usuarios.id_usuario = periferico.usuario_id_per 
                 WHERE usuarios.id_usuario = '$id_usuario'";

    $resultado = mysqli_query($conn, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Mostrar los datos del usuario
        $fila_usuario = mysqli_fetch_assoc($resultado);
        $datos_periferico .= "<h2>Detalles del Usuario</h2>";
        $datos_periferico .= "<p><strong>Identificación:</strong> " . $fila_usuario['identificacion'] . "</p>";
        $datos_periferico .= "<p><strong>Nombre:</strong> " . $fila_usuario['nombre'] . " " . $fila_usuario['apellido'] . "</p>";

        // Reiniciar la cadena para los periféricos
        $datos_periferico .= "<h3>Periféricos Asignados:</h3>";

        // Iterar sobre los resultados de los periféricos
        do {
            $datos_periferico .= "<div style='margin-bottom: 20px;'>";
            $datos_periferico .= "<p><strong>Número de Inventario:</strong> " . $fila_usuario['num_inventario'] . "</p>";
            $datos_periferico .= "<p><strong>Número de Serie:</strong> " . $fila_usuario['num_serie'] . "</p>";
            $datos_periferico .= "<p><strong>Tipo:</strong> " . $fila_usuario['tipo'] . "</p>";
            $datos_periferico .= "<p><strong>Fecha de Compra:</strong> " . $fila_usuario['fecha_compra'] . "</p>";
            $datos_periferico .= "<p><strong>Estado:</strong> " . $fila_usuario['estado'] . "</p>";

            // Consultar las observaciones para el periférico actual
            $num_serie = $fila_usuario['num_serie'];
            $consulta_obs = "SELECT fecha, comentario, pdf FROM obs_periferico WHERE id_periferico = '$num_serie'";
            $resultado_obs = mysqli_query($conn, $consulta_obs);

            // Verificar si hay observaciones y crear la celda correspondiente
            if ($resultado_obs && mysqli_num_rows($resultado_obs) > 0) {
                $observaciones = "<h4>Observaciones:</h4><table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
                $observaciones .= "<tr><th>Fecha</th><th>Comentario</th><th>Archivo PDF</th></tr>";
                while ($fila_obs = mysqli_fetch_assoc($resultado_obs)) {
                    $observaciones .= "<tr>";
                    $observaciones .= "<td>" . $fila_obs['fecha'] . "</td>";
                    $observaciones .= "<td>" . $fila_obs['comentario'] . "</td>";
                    // Verificar si hay un PDF asociado
                    if (!empty($fila_obs['pdf'])) {
                        $observaciones .= "<td><a href='/siadsi/files/" . $fila_obs['pdf'] . "' target='_blank'>Ver PDF</a></td>";
                    } else {
                        $observaciones .= "<td>No disponible</td>";
                    }
                    $observaciones .= "</tr>";
                }
                $observaciones .= "</table>";
                $datos_periferico .= $observaciones;
            } else {
                $datos_periferico .= "<p>No hay observaciones</p>";
            }

            $datos_periferico .= "</div>";
            // Agregar un separador entre periféricos
            $datos_periferico .= "<hr style='border: 1px solid red; margin: 20px 0;'>";
        } while ($fila_usuario = mysqli_fetch_assoc($resultado));

    } else {
        $mensaje_error = "No se encontraron detalles para el usuario seleccionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Usuarios y Periféricos</title>
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
            overflow-y: auto;
            padding: 20px;
        }

        /* Estilo para el título SIADSI y su significado */
        .titulo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .titulo-container h1 {
            color: #FF0000;
            margin-bottom: 0.2rem;
            font-size: 2rem;
        }

        .titulo-container p {
            color: #000;
            font-size: 0.9rem;
            margin-top: 0;
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

        h2 {
            color: #FF0000;
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

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
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
    <!-- Título y significado fuera del contenedor -->
    <div class="titulo-container">
        <h1>SIADSI</h1>
        <p>Sistema de Administración de Sistemas</p>
    </div>

    <div class="container">
        <h2>Búsqueda de Periférico por Usuario</h2>

        <!-- Formulario de búsqueda por usuario -->
        <form method="POST" action="">
            <label for="id_usuario">Seleccionar Usuario:</label>
            <select name="id_usuario" id="id_usuario" required>
                <option value="" disabled selected>Seleccione un usuario</option>
                <?php
                // Consulta para obtener los usuarios
                $consulta_usuarios = "SELECT id_usuario, identificacion, nombre, apellido FROM usuarios";
                $resultado_usuarios = mysqli_query($conn, $consulta_usuarios);

                if ($resultado_usuarios) {
                    while ($row = mysqli_fetch_assoc($resultado_usuarios)) {
                        echo '<option value="' . $row['id_usuario'] . '">' . $row['identificacion'] . ' - ' . $row['nombre'] . ' ' . $row['apellido'] . '</option>';
                    }
                }
                ?>
            </select>
            <input type="submit" name="buscar" value="Buscar">
        </form>

        <!-- Mostrar resultados de la búsqueda -->
        <div class="results">
            <?php
            if ($datos_periferico) {
                echo $datos_periferico;
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
