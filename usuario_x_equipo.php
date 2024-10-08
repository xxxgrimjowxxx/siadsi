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
$datos_equipo = '';
$mensaje_error = '';

// Verificar si se ha enviado el formulario
if (isset($_POST['buscar'])) {
    $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);

    // Consulta para obtener los detalles del usuario y sus equipos
    $consulta = "SELECT usuarios.id_usuario, usuarios.identificacion, usuarios.nombre, usuarios.apellido, 
                        equipo.num_inventario, equipo.us_dominio, equipo.nom_equipo, equipo.tipo_pc, 
                        equipo.marca, equipo.modelo, equipo.numero_parte, equipo.serial,
                        equipo.procesador, equipo.disco_duro, equipo.memoria_ram, equipo.so, 
                        equipo.fecha_compra, equipo.fecha_v_garantia, equipo.estado
                 FROM usuarios 
                 INNER JOIN equipo ON usuarios.id_usuario = equipo.usuario_id_eq 
                 WHERE usuarios.id_usuario = '$id_usuario'";

    $resultado = mysqli_query($conn, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Mostrar los datos del usuario
        $fila_usuario = mysqli_fetch_assoc($resultado);
        $datos_equipo .= "<h2>Detalles del Usuario</h2>";
        $datos_equipo .= "<p><strong>Identificación:</strong> " . $fila_usuario['identificacion'] . "</p>"; // Muestra el número de identificación
        $datos_equipo .= "<p><strong>Nombre:</strong> " . $fila_usuario['nombre'] . " " . $fila_usuario['apellido'] . "</p>";

        // Reiniciar la cadena para los equipos
        $datos_equipo .= "<h3>Equipos Asignados:</h3>";

        // Iterar sobre los resultados de los equipos
        do {
            // Mostrar detalles del equipo
            $datos_equipo .= "<div style='margin-bottom: 20px; border: 1px solid red; padding: 10px;'>";
            $datos_equipo .= "<p><strong>Número de Inventario:</strong> " . $fila_usuario['num_inventario'] . "</p>";
            $datos_equipo .= "<p><strong>Dominio:</strong> " . $fila_usuario['us_dominio'] . "</p>";
            $datos_equipo .= "<p><strong>Nombre del Equipo:</strong> " . $fila_usuario['nom_equipo'] . "</p>";
            $datos_equipo .= "<p><strong>Tipo de PC:</strong> " . $fila_usuario['tipo_pc'] . "</p>";
            $datos_equipo .= "<p><strong>Marca:</strong> " . $fila_usuario['marca'] . "</p>";
            $datos_equipo .= "<p><strong>Modelo:</strong> " . $fila_usuario['modelo'] . "</p>";
            $datos_equipo .= "<p><strong>Número de Parte:</strong> " . $fila_usuario['numero_parte'] . "</p>";
            $datos_equipo .= "<p><strong>Serial:</strong> " . $fila_usuario['serial'] . "</p>";
            $datos_equipo .= "<p><strong>Procesador:</strong> " . $fila_usuario['procesador'] . "</p>";
            $datos_equipo .= "<p><strong>Disco Duro:</strong> " . $fila_usuario['disco_duro'] . "</p>";
            $datos_equipo .= "<p><strong>Memoria RAM:</strong> " . $fila_usuario['memoria_ram'] . "</p>";
            $datos_equipo .= "<p><strong>Sistema Operativo:</strong> " . $fila_usuario['so'] . "</p>";
            $datos_equipo .= "<p><strong>Fecha de Compra:</strong> " . $fila_usuario['fecha_compra'] . "</p>";
            $datos_equipo .= "<p><strong>Fecha de Vencimiento de Garantía:</strong> " . $fila_usuario['fecha_v_garantia'] . "</p>";
            $datos_equipo .= "<p><strong>Estado:</strong> " . $fila_usuario['estado'] . "</p>";

            // Consultar todas las observaciones para este equipo
            $num_inventario = $fila_usuario['num_inventario'];
            $consulta_obs = "SELECT fecha, comentario, pdf FROM observaciones WHERE id_equipo = '$num_inventario'";
            $resultado_obs = mysqli_query($conn, $consulta_obs);

            // Mostrar observaciones en una tabla con el estilo solicitado
            if ($resultado_obs && mysqli_num_rows($resultado_obs) > 0) {
                $datos_equipo .= "<h4>Observaciones del Equipo:</h4>";
                $datos_equipo .= "<table style='border-collapse: collapse; width: 100%; margin-top: 10px;'>";
                $datos_equipo .= "<tr style='background-color: #f2f2f2;'><th style='border: 1px solid red; padding: 8px;'>Fecha</th><th style='border: 1px solid red; padding: 8px;'>Comentario</th><th style='border: 1px solid red; padding: 8px;'>Archivo PDF</th></tr>";
                while ($fila_obs = mysqli_fetch_assoc($resultado_obs)) {
                    $datos_equipo .= "<tr>";
                    $datos_equipo .= "<td style='border: 1px solid red; padding: 8px;'>" . $fila_obs['fecha'] . "</td>";
                    $datos_equipo .= "<td style='border: 1px solid red; padding: 8px;'>" . $fila_obs['comentario'] . "</td>";
                    // Verificar si hay un PDF asociado
                    if ($fila_obs['pdf']) {
                        $datos_equipo .= "<td style='border: 1px solid red; padding: 8px;'><a href='/siadsi/files/" . $fila_obs['pdf'] . "' target='_blank'>Ver PDF</a></td>";
                    } else {
                        $datos_equipo .= "<td style='border: 1px solid red; padding: 8px;'>Sin PDF</td>";
                    }
                    $datos_equipo .= "</tr>";
                }
                $datos_equipo .= "</table>";
            } else {
                $datos_equipo .= "<p>No se encontraron observaciones para este equipo.</p>";
            }
            $datos_equipo .= "</div>"; // Cierre del div del equipo

        } while ($fila_usuario = mysqli_fetch_assoc($resultado)); // Continuar mientras haya equipos
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
    <title>Informe de Usuarios y Equipos</title>
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

        h1 {
            color: #FF0000;
            margin-bottom: 0.2rem;
        }

        h2 {
            color: #FF0000;
            margin-top: 0;
        }

        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
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
            border-radius: 8px;
            text-align: left;
        }

        .results p {
            color: #333;
            line-height: 1.5;
        }

        .results h3 {
            margin-bottom: 0.5rem;
        }

        .results h4 {
            color: #FF0000;
        }

        .back-button {
            display: block;
            margin: 1rem 0;
            text-align: center;
        }

        .back-button a {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .back-button a:hover {
            background-color: #CC0000;
        }

        footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Título y subtítulo fuera del contenedor del formulario -->
    <h1>SIADSI</h1>
    <p>Sistema de Administración de Sistemas</p>

    <div class="container">
        <h2>Informe de Usuarios y Equipos</h2>
        <form method="POST">
            <label for="id_usuario">Seleccionar Usuario:</label>
            <select name="id_usuario" id="id_usuario" required>
            <option value="" disabled selected>Seleccione un usuario</option>
                <?php
                // Consulta para obtener los usuarios
                $consulta_usuarios = "SELECT id_usuario, nombre, apellido, identificacion FROM usuarios WHERE estado = 'activo'";
                $resultado_usuarios = mysqli_query($conn, $consulta_usuarios);
                while ($usuario = mysqli_fetch_assoc($resultado_usuarios)) {
                    echo "<option value='" . $usuario['id_usuario'] . "'>" . $usuario['nombre'] . " " . $usuario['apellido'] . " - " . $usuario['identificacion'] . "</option>"; // Mostrar identificación
                }
                ?>
            </select>
            <input type="submit" name="buscar" value="Buscar">
        </form>

        <div class="results">
            <?php
            if ($mensaje_error) {
                echo "<p style='color: red;'>$mensaje_error</p>";
            } else {
                echo $datos_equipo;
            }
            ?>
        </div>

        <!-- Botón de regresar -->
        <div class="back-button">
            <a href="menuinformes.php">Regresar</a>
        </div>

        <!-- Footer con Copyright -->
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
