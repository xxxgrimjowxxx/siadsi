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
    <title>Página Principal</title>
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
        a {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            margin: 0.5rem 0;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            font-weight: bold;
        }
        a:hover {
            background-color: #CC0000;
        }
        p {
            margin: 0;
        }
        .back-button {
    display: inline-block; /* Para que se ajuste al contenido */
    background-color: #FF0000;
    color: white;
    padding: 0.5rem 1rem;
    margin-top: 1rem;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
    text-align: center;
    font-weight: bold;
    width: auto; /* Asegúrate de no forzar un ancho fijo */
}

    .back-button:hover {
        background-color: #CC0000;
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
        <h1>Selecciona tu informe:</h1>
        <p><a href="usuario_x_equipo.php">Equipo asignado</a></p> 
        <p><a href="usuario_x_periferico.php">Perifericos asignado</a></p>
        <p><a href="usuario_x_estado.php">Listado de usuarios por estado</a></p>
        <p><a href="equipo_x_estado.php">Listado de Equipos por estado</a></p>
        <p><a href="periferico_x_estado.php">Listado de perifericos por estado</a></p>
        <p><a href="informes_varios.php">Informes Varios</a></p>
        <p><a href="cerrar_sesion.php">Cerrar sesión</a></p>        
    </div>
    
    <!-- Botón Volver -->
    <a href="menu.php" class="back-button">Volver</a>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
