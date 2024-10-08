<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . htmlspecialchars($_SESSION['nombre']);

include('conexion.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Formulario de Observaciones Periférico</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
            padding: 20px;
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
        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        h2 {
            color: #FF0000;
            margin: 1rem 0;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .form-row label {
            color: #FF0000;
            font-weight: bold;
            width: 30%;
            text-align: right;
            margin-right: 1rem;
        }
        .form-row input[type="text"],
        .form-row input[type="date"],
        .form-row select,
        .form-row textarea,
        .form-row input[type="file"] {
            width: 70%;
            padding: 0.5rem;
            border: 1px solid #FF0000;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-row textarea {
            resize: vertical;
        }
        .form-row select {
            background-color: #fff;
            appearance: none;
            cursor: pointer;
        }
        .button-row {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        input[type="submit"] {
            background-color: #FF0000;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            max-width: 200px;
        }
        input[type="submit"]:hover {
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
        .back-button:hover {
            background-color: #CC0000;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>

    <div class="form-container">
        <h2>Crear Observación Periférico</h2>

        <form method="POST" action="crear_obs_periferico.php" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-row">
                <label for="id_periferico"># Serie:</label>
                <select name="id_periferico" id="id_periferico" required>
                    <option value="" disabled selected>Seleccione un número de serie</option>
                    <?php
                    // Consulta para seleccionar los números de serie de la tabla 'periferico'
                    $query_series = "SELECT num_serie FROM periferico";
                    $result_series = mysqli_query($conn, $query_series);
                    
                    if ($result_series) {
                        while ($row = mysqli_fetch_assoc($result_series)) {
                            echo '<option value="' . htmlspecialchars($row['num_serie']) . '">' . htmlspecialchars($row['num_serie']) . '</option>';
                        }
                    } else {
                        echo "<div class='error'>Error al cargar series: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-row">
                <label for="fecha">Fecha:</label>
                <input name="fecha" type="date" id="fecha" required>
            </div>

            <div class="form-row">
                <label for="comentario">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu comentario" required></textarea>
            </div>

            <div class="form-row">
                <label for="pdf">Subir PDF:</label>
                <input name="pdf" type="file" id="pdf" accept="application/pdf">
            </div>

            <div class="button-row">
                <input type="submit" name="Enviar" id="Enviar" value="Guardar Observación">
            </div>
        </form>
    </div>
    
    <a href="crudobservacion.php" class="back-button">Volver</a>
    
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>

    <script>
    function validateForm() {
        var idPeriferico = document.getElementById('id_periferico').value;
        var fecha = document.getElementById('fecha').value;
        var comentario = document.getElementById('comentario').value;

        if (idPeriferico == "" || fecha == "" || comentario == "") {
            alert("Por favor, complete todos los campos obligatorios.");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
