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

// Verificar si el formulario fue enviado
if (isset($_POST['Enviar'])) {

    // Obtener los valores del formulario de manera segura
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    $comentario = mysqli_real_escape_string($conn, $_POST['comentario']);
    $num_inventario = mysqli_real_escape_string($conn, $_POST['num_inventario']);

    // Convertir la fecha ingresada al formato correcto si es necesario
    $fecha_convertida = date('Y-m-d', strtotime($fecha));

    // Validar que la fecha esté en el formato correcto (aaaa-mm-dd)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_convertida)) {
        echo "<script>alert('La fecha debe estar en el formato aaaa-mm-dd.'); window.history.back();</script>";
        exit;
    }

    // Inicializar la variable de PDF como NULL por defecto
    $nombre_archivo = null;

    // Verificar si se subió un archivo PDF
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {

        // Definir Carpeta de destino
        $carpeta_destino = "files/";

        // Crear la carpeta si no existe
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        // Obtener el nombre y la extensión del archivo
        $nombre_archivo = basename($_FILES["pdf"]["name"]);
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

        // Mover el archivo a la carpeta de destino
        if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $carpeta_destino . $nombre_archivo)) {
            echo "<script>alert('Error al subir el archivo PDF.'); window.history.back();</script>";
            exit;
        }
    }

    // Construir la consulta SQL dependiendo si se subió o no el archivo PDF
    if ($nombre_archivo) {
        // Si se subió el archivo, insertar con el nombre del archivo
        $consulta = "INSERT INTO observaciones (fecha, comentario, id_equipo, pdf) VALUES ('$fecha_convertida', '$comentario', '$num_inventario', '$nombre_archivo')";
    } else {
        // Si no se subió, insertar sin el campo PDF
        $consulta = "INSERT INTO observaciones (fecha, comentario, id_equipo) VALUES ('$fecha_convertida', '$comentario', '$num_inventario')";
    }

    // Ejecutar la consulta
    $resultado = mysqli_query($conn, $consulta);

    if ($resultado) {
        echo "<script>alert('Registro exitoso');</script>";
    } else {
        echo "<script>alert('Error al registrar los datos.');</script>";
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro de Observación</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #FF0000;
            font-size: 2rem;
            margin: 0;
        }
        .header span {
            font-size: 1rem;
            color: #333;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        h1 {
            color: #FF0000;
            margin-bottom: 1.5rem;
        }
        p {
            margin: 0;
        }
        a {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            margin: 0.5rem 0;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            box-sizing: border-box;
            text-align: center;
            font-weight: bold;
        }
        a:hover {
            background-color: #CC0000;
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
            text-align: center;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>
    <div class="container">
        <?php if (isset($resultado) && $resultado): ?>
            <h1>Registro Exitoso</h1>
            <p>Nueva observación registrada exitosamente.</p>
        <?php else: ?>
            <h1>Error</h1>
            <p>No se pudo registrar la observación. Por favor, inténtelo de nuevo.</p>
        <?php endif; ?>
        <a href="crudobservacion.php" class="back-button">Regresar</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
