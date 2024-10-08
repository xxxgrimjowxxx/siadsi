<?php
include 'conexion.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . $_SESSION['nombre'];

$identificacion = '';
$usuario = '';
$nombre = '';
$apellido = '';
$password = '';
$estado = ''; // Variable para almacenar el estado del usuario
$message = '';
$messageType = '';

if (isset($_POST['buscar'])) {
    $identificacion = $_POST['txtidentificacion'];

    if (!empty($identificacion)) {
        // Consulta para obtener los datos del usuario por identificación
        $sql = $conn->prepare("SELECT usuario, nombre, apellido, password, estado FROM usuarios WHERE identificacion = ?");
        $sql->bind_param("i", $identificacion);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $usuario = $fila['usuario'];
            $nombre = $fila['nombre'];
            $apellido = $fila['apellido'];
            $password = $fila['password']; // Nota: Se asume que el password se almacena en texto plano (no recomendado).
            $estado = $fila['estado']; // Guardar el estado actual del usuario
        } else {
            $message = "No se encontró el usuario con esa identificación.";
            $messageType = "error";
        }

        $sql->close();
    } else {
        $message = "Por favor, ingrese una identificación.";
        $messageType = "error";
    }
}

if (isset($_POST['editar'])) {
    $identificacion = $_POST['txtidentificacion'];
    $nombre = $_POST['txtnombre'];
    $apellido = $_POST['txtapellido'];
    $password = $_POST['txtpassword']; // Nota: Hash el password antes de guardarlo en producción.
    $estado = $_POST['txtestado']; // Obtener el estado actualizado del formulario

    if (!empty($identificacion) && !empty($nombre) && !empty($apellido) && !empty($password) && !empty($estado)) {
        // Consulta para actualizar los datos del usuario
        $sql = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, password = ?, estado = ? WHERE identificacion = ?");
        $sql->bind_param("ssssi", $nombre, $apellido, $password, $estado, $identificacion); // 's' indica que es una cadena de texto

        if ($sql->execute()) {
            $message = "Usuario actualizado con éxito.";
            $messageType = "success";

            // Blanqueo de campos después de la operación exitosa
            $usuario = '';
            $nombre = '';
            $apellido = '';
            $password = '';
            $estado = '';
            $identificacion = ''; // Limpiamos la identificación después de la operación
        } else {
            $message = "Error al actualizar el usuario: " . $conn->error;
            $messageType = "error";
        }

        $sql->close();
    } else {
        $message = "Por favor, complete todos los campos.";
        $messageType = "error";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Formulario de Edición</title>
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
    .form-row input[type="password"],
    .form-row select {
        width: 70%;
        padding: 0.5rem;
        border: 1px solid #FF0000;
        border-radius: 4px;
        box-sizing: border-box;
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

    /* Estilos para el mensaje de éxito o error */
    .message {
        width: 100%;
        text-align: center;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 1rem;
        box-sizing: border-box;
    }
    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
</head>

<body>
    <!-- Mostrar mensaje si existe, encima de "SIADSI" -->
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Acrónimo SIADSI por fuera del formulario -->
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>

    <!-- Contenedor del formulario -->
    <div class="form-container">
        <h2>Editar Usuario</h2>

        <form method="POST" action="">
            <!-- Campo Identificación -->
            <div class="form-row">
                <label for="identificacion">Identificación:</label>
                <input name="txtidentificacion" type="text" id="identificacion" placeholder="Identificación" value="<?php echo $identificacion; ?>">
            </div>
            <!-- Campo Usuario (traído al buscar) -->
            <div class="form-row">
                <label for="usuario">Usuario:</label>
                <input name="txtusuario" type="text" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
            </div>
            <!-- Campo Nombre -->
            <div class="form-row">
                <label for="nombre">Nombre:</label>
                <input name="txtnombre" type="text" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
            </div>
            <!-- Campo Apellido -->
            <div class="form-row">
                <label for="apellido">Apellido:</label>
                <input name="txtapellido" type="text" id="apellido" placeholder="Apellido" value="<?php echo $apellido; ?>">
            </div>
            <!-- Campo Contraseña -->
            <div class="form-row">
                <label for="password">Contraseña:</label>
                <input name="txtpassword" type="password" id="password" placeholder="Contraseña" value="<?php echo $password; ?>">
            </div>
            <!-- Campo Estado -->
            <div class="form-row">
                <label for="estado">Estado:</label>
                <select name="txtestado" id="estado">
                    <option value="Activo" <?php echo ($estado == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo ($estado == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <!-- Botones de Buscar y Editar -->
            <div class="button-row">
                <input type="submit" name="buscar" id="buscar" value="Buscar">
                <input type="submit" name="editar" id="editar" value="Editar">
            </div>
        </form>
    </div>
    
    <!-- Botón Volver -->
    <a href="crudusuario.php" class="back-button">Volver</a>
    
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
