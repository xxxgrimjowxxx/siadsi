<?php
// Incluir la conexión a la base de datos
include('conexion.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . $_SESSION['nombre'];

// Obtener los números de serie de la tabla 'periferico'
$querySeries = "SELECT num_serie FROM periferico";
$resultSeries = mysqli_query($conn, $querySeries);

// Inicializar variables
$datosPeriferico = null;
$estado = null; // Para almacenar el estado del periférico
$action_type = isset($_POST['action_type']) ? $_POST['action_type'] : 'buscar'; // Determina si estamos buscando o editando

// Validar si se ha enviado el formulario con el botón "Buscar"
if ($action_type === 'buscar' && isset($_POST['buscar'])) {
    $numSerieSeleccionado = $_POST['num_serie'];

    // Consultar los datos del periférico con el número de serie seleccionado
    $queryPeriferico = "SELECT * FROM periferico WHERE num_serie = '$numSerieSeleccionado'";
    $resultPeriferico = mysqli_query($conn, $queryPeriferico);

    if (mysqli_num_rows($resultPeriferico) > 0) {
        $datosPeriferico = mysqli_fetch_assoc($resultPeriferico); // Obtener los datos en un array
        $estado = $datosPeriferico['estado']; // Capturar el estado del periférico
    }
}

// Validar si se ha enviado el formulario con el botón "Editar"
if ($action_type === 'editar' && isset($_POST['editar'])) {
    $numSerie = $_POST['num_serie'];
    $tipo = $_POST['tipo'];
    $fechaCompra = $_POST['txtfechac'];
    $usuarioID = $_POST['usuario_id_per'];
    $numInventario = $_POST['txtinventario'];
    $estado = $_POST['estado']; // Capturar el valor del estado desde el formulario

    // Actualizar los datos del periférico en la base de datos
    $queryUpdate = "UPDATE periferico SET 
        tipo = '$tipo',
        fecha_compra = '$fechaCompra',
        usuario_id_per = '$usuarioID',
        num_inventario = '$numInventario',
        estado = '$estado'
        WHERE num_serie = '$numSerie'";

    if (mysqli_query($conn, $queryUpdate)) {
        echo '<div class="message-success">¡Éxito! La información del periférico ha sido actualizada con éxito.</div>';
    } else {
        echo '<div class="message-error">¡Error! No se pudo actualizar los datos: ' . mysqli_error($conn) . '</div>';
    }
}

// Obtener la lista de usuarios para el select de usuario
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
        max-width: 500px;
        width: 100%;
        box-sizing: border-box;
        text-align: center;
        margin: 0 auto;
    }
    h2 {
        color: #FF0000;
        margin: 1rem 0;
    }
    .form-row {
        display: flex;
        flex-direction: column;
        align-items: start;
        margin-bottom: 15px;
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
        width: auto;
    }
    input[type="submit"]:hover {
        background-color: #CC0000;
    }

    .message-success,
    .message-error {
        font-size: 1.5rem;
        font-weight: bold;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        border: 1px solid;
        text-align: center;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .message-success {
        background-color: #dff0d8;
        color: #3c763d;
        border-color: #d6e9c6;
    }
    .message-error {
        background-color: #f2dede;
        color: #a94442;
        border-color: #ebccd1;
    }

    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    .back-button {
        display: inline-block;
        background-color: #FF0000;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        border: none;
        transition: background-color 0.3s;
        margin-top: 1rem;
        font-size: 1rem;
        text-align: center;
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
    function deshabilitarRequired() {
        var inputs = document.querySelectorAll('input[required], select[required]');
        inputs.forEach(function(input) {
            input.removeAttribute('required');
        });
    }

    function habilitarRequired() {
        var inputs = document.querySelectorAll('input[data-validate="true"], select[data-validate="true"]');
        inputs.forEach(function(input) {
            input.setAttribute('required', true);
        });
    }
</script>
</head>

<body>
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>
    <div class="form-container">
        <h2>Editar Periférico</h2>
        <form method="POST" action="" name="form" onsubmit="return true;">
            <!-- Campo oculto para manejar el tipo de acción -->
            <input type="hidden" name="action_type" id="action_type" value="buscar">

            <!-- Campo para seleccionar el número de serie -->
            <div class="form-row">
                <label for="num_serie"># Serie:</label>
                <select name="num_serie" id="num_serie" onchange="deshabilitarRequired()" required>
                    <option value="">Seleccione un número de serie...</option>
                    <?php
                    if (mysqli_num_rows($resultSeries) > 0) {
                        while ($row = mysqli_fetch_assoc($resultSeries)) {
                            $selected = ($datosPeriferico && $datosPeriferico['num_serie'] == $row['num_serie']) ? 'selected' : '';
                            echo '<option value="' . $row['num_serie'] . '" ' . $selected . '>' . $row['num_serie'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay números de serie disponibles</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Campo Tipo actualizado -->
            <div class="form-row">
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo" data-validate="true" required>
                    <option value="">Seleccione...</option>
                    <option value="Teclado" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Teclado') ? 'selected' : ''; ?>>Teclado</option>
                    <option value="Mouse" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Mouse') ? 'selected' : ''; ?>>Mouse</option>
                    <option value="Monitor pc" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Monitor pc') ? 'selected' : ''; ?>>Monitor pc</option>
                    <option value="Cargador pc" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Cargador pc') ? 'selected' : ''; ?>>Cargador pc</option>
                    <option value="Camara Web" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Camara Web') ? 'selected' : ''; ?>>Camara Web</option>
                    <option value="USB" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'USB') ? 'selected' : ''; ?>>USB</option>
                    <option value="Diadema" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Diadema') ? 'selected' : ''; ?>>Diadema</option>
                    <option value="Tablet" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Tablet') ? 'selected' : ''; ?>>Tablet</option>
                    <option value="Disco Duro Extraible" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Disco Duro Extraible') ? 'selected' : ''; ?>>Disco Duro Extraible</option>
                    <option value="Lector tarjetas" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Lector tarjetas') ? 'selected' : ''; ?>>Lector tarjetas</option>
                    <option value="Celular" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Celular') ? 'selected' : ''; ?>>Celular</option>
                    <option value="Cargador celular" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Cargador celular') ? 'selected' : ''; ?>>Cargador celular</option>
                    <option value="Camara" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Camara') ? 'selected' : ''; ?>>Camara</option>
                    <option value="Cargador camara" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Cargador camara') ? 'selected' : ''; ?>>Cargador camara</option>
                    <option value="Lente camara" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Lente camara') ? 'selected' : ''; ?>>Lente Camara</option>
                    <option value="Tripode" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Tripode') ? 'selected' : ''; ?>>Tripode</option>
                    <option value="Estabilizador" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Estabilizador') ? 'selected' : ''; ?>>Estabilizador</option>
                    <option value="Dron" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Dron') ? 'selected' : ''; ?>>Dron</option>
                    <option value="Control dron" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Control dron') ? 'selected' : ''; ?>>Control dron</option>
                    <option value="Cargador dron" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Cargador dron') ? 'selected' : ''; ?>>Cargador dron</option>
                    <option value="Cargador Baterias recargables" <?php echo ($datosPeriferico && $datosPeriferico['tipo'] == 'Cargador Baterias recargables') ? 'selected' : ''; ?>>Cargador Baterias recargables</option>                   

                </select>
            </div>

            <div class="form-row">
                <label for="fechac">Fecha de compra:</label>
                <input name="txtfechac" type="text" id="fechac" placeholder="AAAA-MM-DD" value="<?php echo $datosPeriferico ? $datosPeriferico['fecha_compra'] : ''; ?>" data-validate="true">
            </div>

            <div class="form-row">
                <label for="inventario">Número de Inventario:</label>
                <input name="txtinventario" type="text" id="inventario" placeholder="Inventario" value="<?php echo $datosPeriferico ? $datosPeriferico['num_inventario'] : ''; ?>" data-validate="true">
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
                        // Selecciona el usuario si coincide
                        $selected = ($datosPeriferico && $row['id_usuario'] == $datosPeriferico['usuario_id_per']) ? 'selected' : '';
                        echo "<option value='" . $row['id_usuario'] . "' $selected>" . $nombreCompleto . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Campo para seleccionar el estado -->
            <div class="form-row">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado" data-validate="true" required>
                    <option value="">Seleccione...</option>
                    <option value="En Uso" <?php echo ($estado == 'En Uso') ? 'selected' : ''; ?>>En Uso</option>
                    <option value="Disponible" <?php echo ($estado == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                    <option value="En reparación" <?php echo ($estado == 'En reparación') ? 'selected' : ''; ?>>En reparación</option>
                    <option value="Dado de baja" <?php echo ($estado == 'Dado de baja') ? 'selected' : ''; ?>>Dado de baja</option>
                </select>
            </div>

            <!-- Botones de búsqueda y edición -->
            <div class="button-container">
                <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="deshabilitarRequired(); document.getElementById('action_type').value = 'buscar';">
                <input type="submit" name="editar" id="editar" value="Editar" onclick="habilitarRequired(); document.getElementById('action_type').value = 'editar';">
            </div>
        </form>
    </div>
    <!-- Botón Volver -->
    <div class="button-container">
        <a href="crudperiferico.php" class="back-button">Volver</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
