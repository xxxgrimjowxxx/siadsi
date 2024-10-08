<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . $_SESSION['nombre'];
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Formulario de Observaciones</title>
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<style>
     /* Estilos del formulario */
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
        justify-content: space-between;
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
        width: 48%;
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
    .button-row {
    display: auto;
    justify-content: center; /* Esto centrará el botón */
    margin-top: 20px;
}

.button-row input[type="submit"] {
    background-color: #FF0000;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%; /* Ajusta el botón al ancho del contenedor */
    max-width: 200px; /* Puedes cambiar este valor para controlar el ancho máximo */
    white-space: nowrap; /* Evita que el texto se divida en varias líneas */
}

.button-row input[type="submit"]:hover {
    background-color: #d40000;
}



</style>
</head>
<body>
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>

    <div class="form-container">
        <h2>Crear Observación Equipo</h2>

        <form method="POST" action="crear_obs_equipo.php" enctype="multipart/form-data" onsubmit="return validateForm()">
            <!-- Selección de número de inventario -->
            <div class="form-row">
                <label for="num_inventario"># Inventario:</label>
                <select name="num_inventario" id="num_inventario" required>
                    <option value="" disabled selected>Seleccione un número de inventario</option>
                    <?php
                    include('conexion.php');
                    $query_inventarios = "SELECT num_inventario FROM equipo";
                    $result_inventarios = mysqli_query($conn, $query_inventarios);
                    
                    if ($result_inventarios) {
                        while ($row = mysqli_fetch_assoc($result_inventarios)) {
                            echo '<option value="' . $row['num_inventario'] . '">' . $row['num_inventario'] . '</option>';
                        }
                    } else {
                        echo "<div class='error'>Error al cargar inventarios: " . mysqli_error($conn) . "</div>";
                    }
                    ?>
                </select>
            </div>

            <!-- Campo Fecha -->
            <div class="form-row">
                <label for="fecha">Fecha:</label>
                <input name="fecha" type="date" id="fecha" required>
            </div>

            <!-- Campo Comentario -->
            <div class="form-row">
                <label for="comentario">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu comentario" required></textarea>
            </div>

            <!-- Campo Subir PDF (Opcional) -->
            <div class="form-row">
                <label for="pdf">Subir PDF:</label>
                <input name="pdf" type="file" id="pdf" accept="application/pdf">
            </div>

            <!-- Botón Guardar -->
            <div class="button-row">
                <input type="submit" name="Enviar" id="Enviar" value="Guardar Observación">
            </div>
        </form>
    </div>
    
    <a href="crudobservacion.php" class="back-button">Volver</a>
    
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
