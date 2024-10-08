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
if (isset($_POST['submit'])) {

    // Obtener los valores del formulario de manera segura
    $inventario = mysqli_real_escape_string($conn, $_POST['txtinventario']);
    $dominio = mysqli_real_escape_string($conn, $_POST['txtdominio']);
    $nombre = mysqli_real_escape_string($conn, $_POST['txtnombre']);
    $tipopc = mysqli_real_escape_string($conn, $_POST['txttipopc']);
    $marca = mysqli_real_escape_string($conn, $_POST['txtmarca']);
    $modelo = mysqli_real_escape_string($conn, $_POST['txtmodelo']);
    $parte = mysqli_real_escape_string($conn, $_POST['txtparte']);
    $serial = mysqli_real_escape_string($conn, $_POST['txtserial']);
    $procesador = mysqli_real_escape_string($conn, $_POST['txtprocesador']);
    $disco = mysqli_real_escape_string($conn, $_POST['txtdisco']);
    $memoria = mysqli_real_escape_string($conn, $_POST['txtmemoria']);
    $so = mysqli_real_escape_string($conn, $_POST['txtso']);
    $fechac = mysqli_real_escape_string($conn, $_POST['txtfechac']);
    $fechav = mysqli_real_escape_string($conn, $_POST['txtfechav']);
    $estado = mysqli_real_escape_string($conn, $_POST['txtestado']);
    $usuario_id_eq = mysqli_real_escape_string($conn, $_POST['usuario_id_eq']); // Obtener el id del usuario seleccionado

    // Consulta SQL para insertar los datos en la tabla equipo
    $sql = "INSERT INTO equipo (num_inventario, us_dominio, nom_equipo, tipo_pc, marca, modelo, numero_parte, serial, procesador, disco_duro, memoria_ram, so, fecha_compra, fecha_v_garantia, estado, usuario_id_eq) 
            VALUES ('$inventario', '$dominio', '$nombre', '$tipopc', '$marca', '$modelo', '$parte', '$serial', '$procesador', '$disco', '$memoria', '$so', '$fechac', '$fechav', '$estado', '$usuario_id_eq')";

    // Ejecutar la consulta
    $registroExitoso = mysqli_query($conn, $sql);

    // Cerrar la conexión
    mysqli_close($conn);
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro de Equipo</title>
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
            width: auto; /* Ajusta el tamaño según el contenido */
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
        <?php if (isset($registroExitoso) && $registroExitoso): ?>
            <h1>Registro Exitoso</h1>
            <p>Nuevo equipo registrado exitosamente.</p>
        <?php else: ?>
            <h1>Error</h1>
            <p>No se pudo registrar el equipo. Por favor, inténtelo de nuevo.</p>
        <?php endif; ?>
        <a href="reg_equipo.php" class="back-button">Regresar</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
