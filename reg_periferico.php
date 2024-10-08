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

//  Obtener la lista de usuarios con nombre y apellido de la base de datos
$sql_usuarios = "SELECT id_usuario, nombre, apellido FROM usuarios";
$result_usuarios = mysqli_query($conn, $sql_usuarios);

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Formulario de Registro de Periférico</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
            text-align: center;
        }
        h1 {
            color: #FF0000;
            margin-bottom: 10px;
        }
        h3 {
            color: #333;
            margin-bottom: 20px;
            font-weight: normal;
        }
        h2 {
            color: #FF0000;
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-row label {
            color: #FF0000;
            font-weight: bold;
            width: 20%;
            text-align: right;
            margin-right: 10px;
        }
        .form-row input,
        .form-row select {
            width: 75%;
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
        }
        input[type="submit"]:hover {
            background-color: #CC0000;
        }
        .back-button {
            background-color: #FF0000;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            display: inline-block;
            margin-top: 20px;
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
        function validarFormulario() {
            var numSerie = document.getElementById("num_serie").value.trim();
            var tipo = document.getElementById("tipopc").value;
            var fechac = document.getElementById("fechac").value.trim();
            var inventario = document.getElementById("inventario").value.trim();
            var estado = document.getElementById("estado").value.trim();
            
            if (!numSerie || !tipo || !fechac || !inventario || !estado) {
                alert("Todos los campos son obligatorios.");
                return false;
            }

            if (numSerie.length < 2) {
                alert("El número de serie debe tener al menos 2 caracteres.");
                return false;
            }

            var regexFecha = /^\d{4}-\d{2}-\d{2}$/;
            if (!regexFecha.test(fechac)) {
                alert("La fecha debe estar en el formato AAAA-MM-DD.");
                return false;
            }

            if (inventario.length < 2) {
                alert("El número de inventario debe tener al menos 2 caracteres.");
                return false;
            }           

            return true;
        }
    </script>
</head>
<body>
    <h1>SIADSI</h1>
    <h3>Sistema de Administración de Sistemas</h3>
    <div class="form-container">
        <h2>Registrar Periférico</h2>
        <form method="POST" action="crear_periferico.php" onsubmit="return validarFormulario()">
            <div class="form-row">
                <label for="num_serie"># Serie:</label>
                <input name="txtnum_serie" type="text" id="num_serie" placeholder="# Serie" required>
            </div>
            <div class="form-row">
                <label for="tipo">Tipo:</label>
                <select name="txttipopc" id="tipopc" required>
                    <option value="">Seleccione...</option>
                    <option value="Teclado">Teclado</option>
                    <option value="Mouse">Mouse</option>
                    <option value="Monitor pc">Monitor</option>
                    <option value="Cargador pc">Cargador</option>
                    <option value="Camara Web">Camara Web</option>
                    <option value="USB">USB</option>
                    <option value="Diadema">Diadema</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Disco Duro Extraible">Cargador</option>
                    <option value="Lector tarjetas">Lector de tarjetas</option>
                    <option value="Celular">Celular</option>
                    <option value="Cargador celular">Cargador celular</option>
                    <option value="Camara">Camara</option>
                    <option value="Cargador camara">Cargador camara</option>
                    <option value="Lente camara">Lente camara</option>
                    <option value="Tripode">Tripode</option>
                    <option value="Estabilizador">Estabilizador</option>
                    <option value="Dron">Dron</option>
                    <option value="Control dron">Control dron</option>
                    <option value="cargador Dron">Cargador Dron</option>
                    <option value="Cargador Baterias recargables">Cargador Baterias recargables</option>
                </select>
            </div>
            <div class="form-row">
                <label for="fechac">Fecha de compra:</label>
                <input name="txtfechac" type="text" id="fechac" placeholder="AAAA-MM-DD" required>
            </div>
            <div class="form-row">
                <label for="inventario">Número de Inventario:</label>
                <input name="txtinventario" type="text" id="inventario" placeholder="Inventario" required>
            </div>

            <!-- Campo para seleccionar el usuario -->
            <div class="form-row">
                <label for="usuario_id_per">Usuario:</label>
                <select name="usuario_id_per" id="usuario_id_per" required>
                    <option value="">Seleccione un usuario...</option>
                    <?php
                    // Mostrar la lista de usuarios con nombre y apellido concatenados
                    while ($row = mysqli_fetch_assoc($result_usuarios)) {
                        $nombreCompleto = $row['nombre'] . ' ' . $row['apellido'];
                        echo "<option value='" . $row['id_usuario'] . "'>" . $nombreCompleto . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-row">
                <label for="tipo">Estado:</label>
                <select name="txtestado" id="estado" required>
                    <option value="">Seleccione...</option>
                         <option value="En Uso">En uso</option>                        
                        <option value="Disponible">Disponible</option>
                        <option value="En reparación">En reparación</option>
                        <option value="Dado de baja">Dado de baja</option>
                </select>
            </div>

            <input type="submit" name="submit" value="Registrar">
        </form>        
    </div>
    <a href="crudperiferico.php" class="back-button">Volver</a>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
