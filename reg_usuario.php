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
<title>Formulario de Registro</title>
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
    input[type="submit"] {
        background-color: #FF0000;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: block;
        margin: 1rem auto 0;
        width: auto;
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
</style>
<script>
// Función para validar el formulario
function validarFormulario() {
    var nombre = document.getElementById("nombre").value.trim();
    var apellido = document.getElementById("apellido").value.trim();
    var identificacion = document.getElementById("identificacion").value.trim();
    var usuario = document.getElementById("usuario").value.trim();
    var password = document.getElementById("password").value.trim();
    var estado = document.getElementById("estado").value;

    // Validar que todos los campos estén llenos
    if (nombre === "" || apellido === "" || identificacion === "" || usuario === "" || password === "" || estado === "") {
        alert("Todos los campos son obligatorios.");
        return false;
    }

    // Validar que el nombre y apellido no tengan menos de 2 caracteres
    if (nombre.length < 2) {
        alert("El nombre debe tener al menos 2 caracteres.");
        return false;
    }

    if (apellido.length < 2) {
        alert("El apellido debe tener al menos 2 caracteres.");
        return false;
    }

    // Validar que la identificación sea numérica
    if (!/^\d+$/.test(identificacion)) {
        alert("La identificación debe ser un número válido.");
        return false;
    }

    // Validar que el usuario tenga al menos 3 caracteres y no contenga espacios
    if (usuario.length < 3) {
        alert("El nombre de usuario debe tener al menos 3 caracteres.");
        return false;
    }
    if (/\s/.test(usuario)) {
        alert("El nombre de usuario no debe contener espacios.");
        return false;
    }

    // Validar que la contraseña tenga al menos 6 caracteres
    if (password.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        return false;
    }

    // Validar que el estado esté seleccionado
    if (estado === "") {
        alert("Debe seleccionar un estado.");
        return false;
    }

    return true;
}
</script>
</head>

<body>
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>
    <div class="form-container">
        <h2>Registrar Usuario</h2>
        <form method="POST" action="crear_usuario.php" name="form" onsubmit="return validarFormulario()">
            <div class="form-row">
                <label for="nombre">Nombre:</label>
                <input name="txtnombre" type="text" id="nombre" placeholder="Nombre" minlength="2" maxlength="20" required>
            </div>
            <div class="form-row">
                <label for="apellido">Apellido:</label>
                <input name="txtapellido" type="text" id="apellido" placeholder="Apellido" minlength="2" maxlength="20" required>
            </div>
            <div class="form-row">
                <label for="identificacion">Identificación:</label>
                <input name="txtidentificacion" type="text" id="identificacion" placeholder="Identificación" required>
            </div>
            <div class="form-row">
                <label for="usuario">Usuario:</label>
                <input name="txtusuario" type="text" id="usuario" placeholder="Usuario" minlength="3" maxlength="20" pattern="^\S+$" title="El usuario no debe contener espacios" required>
            </div>
            <div class="form-row">
                <label for="password">Contraseña:</label>
                <input name="txtpassword" type="password" id="password" placeholder="Contraseña" minlength="6" maxlength="20" required>
            </div>
            <div class="form-row">
                <label for="estado">Estado:</label>
                <select name="txtestado" id="estado" required>
                    <option value="">Seleccione...</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <p>
                <input type="submit" name="submit" id="registrarse" value="Registrarse">
            </p>
            <input type="hidden" name="MM_insert" value="form">
        </form>
    </div>
    <a href="crudusuario.php" class="back-button">Volver</a>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
