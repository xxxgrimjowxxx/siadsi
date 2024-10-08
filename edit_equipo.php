<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . $_SESSION['nombre'];
?>
<?php
// Incluir la conexión a la base de datos
include('conexion.php');


// Obtener los números de inventario de la tabla 'equipo'
$queryInventarios = "SELECT num_inventario FROM equipo";
$resultInventarios = mysqli_query($conn, $queryInventarios);

// Obtener los usuarios de la tabla 'usuarios' (con nombre y apellido)
$queryUsuarios = "SELECT id_usuario, nombre, apellido FROM usuarios";
$resultUsuarios = mysqli_query($conn, $queryUsuarios);

// Inicializar variables
$datosEquipo = null;
$action_type = isset($_POST['action_type']) ? $_POST['action_type'] : 'buscar'; // Determina si estamos buscando o editando

// Validar si se ha enviado el formulario con el botón "Buscar"
if ($action_type === 'buscar' && isset($_POST['buscar'])) {
    $numInventarioSeleccionado = $_POST['num_inventario'];

    // Consultar los datos del equipo con el número de inventario seleccionado
    $queryEquipo = "SELECT * FROM equipo WHERE num_inventario = '$numInventarioSeleccionado'";
    $resultEquipo = mysqli_query($conn, $queryEquipo);

    if (mysqli_num_rows($resultEquipo) > 0) {
        $datosEquipo = mysqli_fetch_assoc($resultEquipo); // Obtener los datos en un array
    }
}

// Validar si se ha enviado el formulario con el botón "Editar"
if ($action_type === 'editar' && isset($_POST['editar'])) {
    $numInventario = $_POST['num_inventario'];
    $usDominio = $_POST['txtdominio'];
    $nomEquipo = $_POST['txtnombre'];
    $tipoPc = $_POST['txttipopc'];
    $marca = $_POST['txtmarca'];
    $modelo = $_POST['txtmodelo'];
    $numeroParte = $_POST['txtnparte'];
    $serial = $_POST['txtserial'];
    $procesador = $_POST['txtprocesador'];
    $discoDuro = $_POST['txtdiscoduro'];
    $memoriaRam = $_POST['txtmemoria'];
    $so = $_POST['txtso'];
    $fechaCompra = $_POST['txtfechac'];
    $fechaVencimiento = $_POST['txtfechav'];
    $estado = $_POST['txtestado'];
    $usuarioId = $_POST['usuario']; // Nuevo campo para el ID del usuario

    // Actualizar los datos del equipo en la base de datos
    $queryUpdate = "UPDATE equipo SET 
        us_dominio = '$usDominio',
        nom_equipo = '$nomEquipo',
        tipo_pc = '$tipoPc',
        marca = '$marca',
        modelo = '$modelo',
        numero_parte = '$numeroParte',
        serial = '$serial',
        procesador = '$procesador',
        disco_duro = '$discoDuro',
        memoria_ram = '$memoriaRam',
        so = '$so',
        fecha_compra = '$fechaCompra',
        fecha_v_garantia = '$fechaVencimiento',
        estado = '$estado',
        usuario_id_eq = '$usuarioId'
        WHERE num_inventario = '$numInventario'";

    if (mysqli_query($conn, $queryUpdate)) {
        echo '<div class="message-success">¡Éxito! La información del equipo ha sido actualizada con éxito.</div>';
    } else {
        echo '<div class="message-error">¡Error! No se pudo actualizar los datos: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Formulario de Edición Equipos</title>
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
        width: auto;
    }
    input[type="submit"]:hover {
        background-color: #CC0000;
    }

    /* Estilos para los mensajes de éxito y error */
    .message-success,
    .message-error {
        font-size: 1.5rem; /* Tamaño de fuente similar al título */
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
        text-align: center; /* Centramos los botones */
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
        text-align: center; /* Centramos el botón */
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
        // Deshabilitar "required" al hacer una búsqueda
        var inputs = document.querySelectorAll('input[required], select[required]');
        inputs.forEach(function(input) {
            input.removeAttribute('required');
        });
    }

    function habilitarRequired() {
        // Habilitar "required" para la acción de registro o edición
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
        <h2>Editar Equipo</h2>
        <form method="POST" action="" name="form" onsubmit="return true;">
            <!-- Campo oculto para manejar el tipo de acción -->
            <input type="hidden" name="action_type" id="action_type" value="buscar">

            <div class="form-grid">
                <!-- Campo para seleccionar el número de inventario -->
                <div class="form-row">
                    <label for="num_inventario"># Inventario:</label>
                    <select name="num_inventario" id="num_inventario" onchange="deshabilitarRequired()" required>
                        <option value="">Seleccione un número de inventario...</option>
                        <?php
                        if (mysqli_num_rows($resultInventarios) > 0) {
                            while ($row = mysqli_fetch_assoc($resultInventarios)) {
                                $selected = ($datosEquipo && $datosEquipo['num_inventario'] == $row['num_inventario']) ? 'selected' : '';
                                echo '<option value="' . $row['num_inventario'] . '" ' . $selected . '>' . $row['num_inventario'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay inventarios disponibles</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Campo para seleccionar el usuario -->
<div class="form-row">
    <label for="usuario">Usuario:</label>
    <select name="usuario" id="usuario" data-validate="true" required>
        <option value="">Seleccione un usuario...</option>
        <?php
        if (mysqli_num_rows($resultUsuarios) > 0) {
            while ($rowUsuario = mysqli_fetch_assoc($resultUsuarios)) {
                // Concatenar nombre y apellido para mostrar el nombre completo
                $nombreCompleto = $rowUsuario['nombre'] . ' ' . $rowUsuario['apellido'];
                $selected = ($datosEquipo && $datosEquipo['usuario_id_eq'] == $rowUsuario['id_usuario']) ? 'selected' : '';
                echo '<option value="' . $rowUsuario['id_usuario'] . '" ' . $selected . '>' . $nombreCompleto . '</option>';
            }
        } else {
            echo '<option value="">No hay usuarios disponibles</option>';
        }
        ?>
    </select>
</div>

                <!-- Campos adicionales -->
                <div class="form-row">
                    <label for="us_dominio">Usuario de dominio:</label>
                    <input name="txtdominio" type="text" id="us_dominio" placeholder="Usuario Dominio" value="<?php echo $datosEquipo ? $datosEquipo['us_dominio'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="nom_equipo">Nombre equipo:</label>
                    <input name="txtnombre" type="text" id="nom_equipo" placeholder="Nombre de equipo" value="<?php echo $datosEquipo ? $datosEquipo['nom_equipo'] : ''; ?>" data-validate="true">
                </div>

                <!-- Campo "Tipo de equipo" convertido en select -->
                <div class="form-row">
                    <label for="tipo_pc">Tipo de equipo:</label>
                    <select name="txttipopc" id="tipo_pc" data-validate="true" required>
                        <option value="">Seleccione...</option>
                        <option value="All in one" <?php echo ($datosEquipo && $datosEquipo['tipo_pc'] == 'All in one') ? 'selected' : ''; ?>>All in one</option>
                        <option value="Desktop" <?php echo ($datosEquipo && $datosEquipo['tipo_pc'] == 'Desktop') ? 'selected' : ''; ?>>Desktop</option>
                        <option value="Portatil" <?php echo ($datosEquipo && $datosEquipo['tipo_pc'] == 'Portatil') ? 'selected' : ''; ?>>Portatil</option>
                        <option value="Small Form Factor" <?php echo ($datosEquipo && $datosEquipo['tipo_pc'] == 'Small Form Factor') ? 'selected' : ''; ?>>Small Form Factor</option>
                        <option value="Servidor" <?php echo ($datosEquipo && $datosEquipo['tipo_pc'] == 'Servidor') ? 'selected' : ''; ?>>Servidor</option>
                    </select>
                </div>

                <div class="form-row">
                    <label for="marca">Marca:</label>
                    <input name="txtmarca" type="text" id="marca" placeholder="Marca" value="<?php echo $datosEquipo ? $datosEquipo['marca'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="modelo">Modelo:</label>
                    <input name="txtmodelo" type="text" id="modelo" placeholder="Modelo" value="<?php echo $datosEquipo ? $datosEquipo['modelo'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="numero_parte">Número de parte:</label>
                    <input name="txtnparte" type="text" id="numero_parte" placeholder="Número de parte" value="<?php echo $datosEquipo ? $datosEquipo['numero_parte'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="serial">Serial:</label>
                    <input name="txtserial" type="text" id="serial" placeholder="Serial" value="<?php echo $datosEquipo ? $datosEquipo['serial'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="procesador">Procesador:</label>
                    <input name="txtprocesador" type="text" id="procesador" placeholder="Procesador" value="<?php echo $datosEquipo ? $datosEquipo['procesador'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="disco_duro">Disco duro:</label>
                    <input name="txtdiscoduro" type="text" id="disco_duro" placeholder="Disco duro" value="<?php echo $datosEquipo ? $datosEquipo['disco_duro'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="memoria_ram">Memoria RAM:</label>
                    <input name="txtmemoria" type="text" id="memoria_ram" placeholder="Memoria RAM" value="<?php echo $datosEquipo ? $datosEquipo['memoria_ram'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="so">Sistema operativo:</label>
                    <input name="txtso" type="text" id="so" placeholder="Sistema operativo" value="<?php echo $datosEquipo ? $datosEquipo['so'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="fecha_compra">Fecha de compra:</label>
                    <input name="txtfechac" type="date" id="fecha_compra" value="<?php echo $datosEquipo ? $datosEquipo['fecha_compra'] : ''; ?>" data-validate="true">
                </div>
                <div class="form-row">
                    <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                    <input name="txtfechav" type="date" id="fecha_vencimiento" value="<?php echo $datosEquipo ? $datosEquipo['fecha_v_garantia'] : ''; ?>" data-validate="true">
                </div>

                <!-- Campo "Estado" convertido en select -->
                <div class="form-row">
                    <label for="estado">Estado:</label>
                    <select name="txtestado" id="estado" data-validate="true" required>
                        <option value="">Seleccione...</option>
                        <option value="En uso" <?php echo ($datosEquipo && $datosEquipo['estado'] == 'En Uso') ? 'selected' : ''; ?>>En Uso</option>
                        <option value="Disponible" <?php echo ($datosEquipo && $datosEquipo['estado'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                        <option value="En reparación" <?php echo ($datosEquipo && $datosEquipo['estado'] == 'En reparación') ? 'selected' : ''; ?>>En reparación</option>
                        <option value="Dado de baja" <?php echo ($datosEquipo && $datosEquipo['estado'] == 'Dado de baja') ? 'selected' : ''; ?>>Dado de baja</option>
                    </select>
                </div>
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
        <a href="crudequipo.php" class="back-button">Volver</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
