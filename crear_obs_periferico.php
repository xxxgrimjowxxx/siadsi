<?php
session_start();
require_once('conexion.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
    $id_periferico = isset($_POST['id_periferico']) ? $_POST['id_periferico'] : '';

    // Validación de los inputs
    if (empty($fecha) || empty($comentario) || empty($id_periferico)) {
        $message = "Todos los campos son obligatorios.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        $message = "La fecha debe estar en el formato aaaa-mm-dd.";
    } else {
        $pdf_filename = null;

        // Manejo de la subida del archivo PDF
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['application/pdf'];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($_FILES['pdf']['type'], $allowed_types)) {
                $message = "Solo se permiten archivos PDF.";
            } elseif ($_FILES['pdf']['size'] > $max_size) {
                $message = "El archivo no debe superar 5MB.";
            } else {
                $pdf_filename = uniqid() . '_' . basename($_FILES['pdf']['name']);
                $upload_dir = "files/";

                if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $upload_dir . $pdf_filename)) {
                    $message = "Error al subir el archivo.";
                }
            }
        }

        if (empty($message)) {
            // Preparar y ejecutar la consulta para insertar la observación
            $stmt = $conn->prepare("INSERT INTO obs_periferico (fecha, comentario, id_periferico, pdf) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fecha, $comentario, $id_periferico, $pdf_filename);

            if ($stmt->execute()) {
                $message = "Observación registrada exitosamente.";
                $_SESSION['success_message'] = $message; // Guardamos el mensaje en la sesión
                header("Location: crudobservacion.php"); // Redirigimos a la página de observaciones
                exit;
            } else {
                $message = "Error al registrar la observación: " . $stmt->error;
            }

            $stmt->close();
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
    <title>Procesar Observación de Periférico</title>
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
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #FF0000;
        }
        .message {
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: #f8d7da;
            color: #721c24;
        }
        .back-link {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: #CC0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Procesar Observación</h1>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <a href="crear_obs_periferico.php" class="back-link">Volver al formulario</a>
    </div>
</body>
</html>
