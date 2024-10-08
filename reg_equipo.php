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

// Obtener la lista de usuarios de la base de datos
$sql_usuarios = "SELECT id_usuario, nombre, apellido FROM usuarios";
$result_usuarios = mysqli_query($conn, $sql_usuarios);

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Formulario de Registro Equipos</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
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
            max-width: 800px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            margin: 0 auto;
        }
        h2 {
            color: #FF0000;
            margin: 1rem 0;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 1rem;
        }
        .form-row {
            display: flex;
            flex-direction: column;
            align-items: start;
        }
        .form-row label {
            color: #FF0000;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-row input,
        .form-row select {
            width: 100%;
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
            margin-top: 1rem;
            width: 150px;
        }
        input[type="submit"]:hover {
            background-color: #CC0000;
        }
        .submit-button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .back-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .back-button {
            display: inline-block;
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
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
        var inventario = document.getElementById("inventario").value.trim();
        var dominio = document.getElementById("dominio").value.trim();
        var nombre = document.getElementById("nombre").value.trim();
        var marca = document.getElementById("marca").value.trim();
        var modelo = document.getElementById("modelo").value.trim();
        var parte = document.getElementById("parte").value.trim();
        var serial = document.getElementById("serial").value.trim();
        var procesador = document.getElementById("procesador").value.trim();
        var disco = document.getElementById("disco").value.trim();
        var memoria = document.getElementById("memoria").value.trim();
        var so = document.getElementById("so").value.trim();
        var fechac = document.getElementById("fechac").value.trim();
        var fechav = document.getElementById("fechav").value.trim();
        var estado = document.getElementById("estado").value;

        if (!inventario || !dominio || !nombre || !marca || !modelo || !parte || !serial || !procesador || !disco || !memoria || !so || !fechac || !fechav || !estado) {
            alert("Todos los campos son obligatorios.");
            return false;
        }

        if (/\s/.test(nombre)) {
            alert("El nombre de equipo no debe contener espacios.");
            return false;
        }

        var regexFecha = /^\d{4}-\d{2}-\d{2}$/;
        if (!regexFecha.test(fechac) || !regexFecha.test(fechav)) {
            alert("La fecha debe estar en el formato AAAA-MM-DD.");
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
        <h2>Registrar Equipo</h2>
        <form method="POST" action="crear_equipo.php" name="form" onsubmit="return validarFormulario()">
            <div class="form-grid">
                <!-- Aquí van los demás campos de registro -->
                <div class="form-row">
                    <label for="inventario"># Inventario:</label>
                    <input name="txtinventario" type="text" id="inventario" placeholder="# Inventario" minlength="2" maxlength="20" required>
                </div>
                <div class="form-row">
                    <label for="us_dominio">Usuario de dominio:</label>
                    <input name="txtdominio" type="text" id="dominio" placeholder="Usuario Dominio" minlength="2" maxlength="20" required>
                </div>
                <div class="form-row">
                    <label for="nombre">Nombre equipo:</label>
                    <input name="txtnombre" type="text" id="nombre" placeholder="Nombre de equipo" minlength="3" maxlength="20" pattern="^\S+$" title="El nombre no debe contener espacios" required>
                </div>
                <div class="form-row">
                    <label for="tipo_pc">Tipo de equipo:</label>
                    <select name="txttipopc" id="tipopc" required>
                        <option value="">Seleccione...</option>
                        <option value="All in one">All in one</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Portatil">Portatil</option>
                        <option value="Small Form Factor">Small Form Factor</option>
                        <option value="Servidor">Servidor</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="marca">Marca:</label>
                    <input name="txtmarca" type="text" id="marca" placeholder="Marca" minlength="2" maxlength="20" required>
                </div>
                <div class="form-row">
                    <label for="modelo">Modelo:</label>
                    <input name="txtmodelo" type="text" id="modelo" placeholder="Modelo" minlength="6" maxlength="50" required>
                </div>
                <div class="form-row">
                    <label for="parte"># Parte:</label>
                    <input name="txtparte" type="text" id="parte" placeholder="# Parte" minlength="6" maxlength="50" required>
                </div>
                <div class="form-row">
                    <label for="serial">Serial:</label>
                    <input name="txtserial" type="text" id="serial" placeholder="Serial" minlength="6" maxlength="50" required>
                </div>
                <div class="form-row">
                    <label for="procesador">Procesador:</label>
                    <input name="txtprocesador" type="text" id="procesador" placeholder="Procesador" minlength="6" maxlength="50" required>
                </div>
                <div class="form-row">
                    <label for="disco">Disco Duro:</label>
                    <input name="txtdisco" type="text" id="disco" placeholder="Disco Duro" minlength="1" maxlength="20" required>
                </div>
                <div class="form-row">
                    <label for="memoria">Memoria RAM:</label>
                    <input name="txtmemoria" type="text" id="memoria" placeholder="Memoria RAM" minlength="1" maxlength="20" required>
                </div>
                <div class="form-row">
                    <label for="so">Sistema Operativo:</label>
                    <input name="txtso" type="text" id="so" placeholder="Sistema Operativo" minlength="1" maxlength="50" required>
                </div>
                <div class="form-row">
                    <label for="fechac">Fecha de Compra:</label>
                    <input name="txtfechac" type="date" id="fechac" required>
                </div>
                <div class="form-row">
                    <label for="fechav">Fecha de Vencimiento:</label>
                    <input name="txtfechav" type="date" id="fechav" required>
                </div>
                <div class="form-row">
                    <label for="estado">Estado:</label>
                    <select name="txtestado" id="estado" required>
                        <option value="">Seleccione...</option>
                        <option value="En Uso">En uso</option>                        
                        <option value="Disponible">Disponible</option>
                        <option value="En reparación">En reparación</option>
                        <option value="Dado de baja">Dado de baja</option>
                    </select>
                </div>
                <!-- Campo para seleccionar el usuario -->
                <!-- Campo para seleccionar el usuario -->
<div class="form-row">
    <label for="usuario_id_eq">Usuario:</label>
    <select name="usuario_id_eq" id="usuario_id_eq" required>
        <option value="">Seleccione un usuario...</option>
        <?php
        // Mostrar la lista de usuarios con nombre y apellido
        while ($row = mysqli_fetch_assoc($result_usuarios)) {
            echo "<option value='" . $row['id_usuario'] . "'>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
        }
        ?>
    </select>
</div>
            </div>
            <div class="submit-button-container">
                <input type="submit" name="submit" id="registrarse" value="Registrarse">
            </div>
        </form>
    </div>

    <!-- Botón Volver -->
    <div class="back-button-container">
        <a href="crudequipo.php" class="back-button">Volver</a>
    </div>

    <!-- Leyenda de Copyright -->
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
