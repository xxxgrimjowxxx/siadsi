<?php
session_start(); // Iniciar la sesión

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no está logueado
    exit;
}

// Mostrar contenido si está logueado
echo "Bienvenido, " . $_SESSION['nombre'];

include 'conexion.php';

$tipo_informe = isset($_POST['tipo_informe']) ? $_POST['tipo_informe'] : '';
$resultados = [];

if (isset($_POST['generar_informe'])) {
    if ($tipo_informe == 'equipo_usuario_observaciones') {
        // Consulta para el informe "Equipo, usuario y observaciones"
        $sql = "SELECT eq.num_inventario, eq.us_dominio, eq.nom_equipo, eq.tipo_pc, eq.marca, eq.modelo, eq.numero_parte, eq.serial, eq.procesador, eq.disco_duro, eq.memoria_ram, eq.so, eq.fecha_compra, eq.fecha_v_garantia, eq.estado, usr.nombre, usr.apellido, obs.fecha AS fecha_comentario, obs.comentario, obs.pdf 
                FROM equipo eq 
                LEFT JOIN usuarios usr ON eq.usuario_id_eq = usr.id_usuario 
                LEFT JOIN observaciones obs ON eq.num_inventario = obs.id_equipo";
    } elseif ($tipo_informe == 'periferico_usuario_observaciones') {
        // Consulta para el informe "Periférico, usuario y observaciones"
        $sql = "SELECT per.num_serie, per.tipo, per.fecha_compra, per.num_inventario, per.estado, usr.nombre, usr.apellido, obs.fecha AS fecha_comentario, obs.comentario, obs.pdf 
                FROM periferico per 
                LEFT JOIN usuarios usr ON per.usuario_id_per = usr.id_usuario 
                LEFT JOIN obs_periferico obs ON per.num_serie = obs.id_periferico";
    }

    // Ejecutar la consulta
    $result = mysqli_query($conn, $sql);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error en la consulta SQL: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $resultados[] = $row;
        }
    }

    // Guardar los resultados en la sesión para exportarlos luego
    $_SESSION['resultados'] = $resultados;
    $_SESSION['tipo_informe'] = $tipo_informe;
}

// Exportar a Excel en formato CSV
if (isset($_POST['exportar_excel'])) {
    // Recuperar los resultados desde la sesión
    if (isset($_SESSION['resultados']) && !empty($_SESSION['resultados'])) {
        $resultados = $_SESSION['resultados'];
        $tipo_informe = $_SESSION['tipo_informe'];
    } else {
        die('No hay datos para exportar.');
    }

    $fileName = 'Informe_' . $tipo_informe . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
    $output = fopen("php://output", "w");

    if ($tipo_informe == 'equipo_usuario_observaciones') {
        // Encabezados para "Equipo, usuario y observaciones"
        fputcsv($output, ['Número de Inventario', 'Dominio', 'Nombre de Equipo', 'Tipo de PC', 'Marca', 'Modelo', 'Número de Parte', 'Serial', 'Procesador', 'Disco Duro', 'Memoria RAM', 'Sistema Operativo', 'Fecha de Compra', 'Vencimiento de Garantía', 'Estado', 'Usuario', 'Fecha Comentario', 'Comentario', 'Link PDF']);
    } elseif ($tipo_informe == 'periferico_usuario_observaciones') {
        // Encabezados para "Periférico, usuario y observaciones"
        fputcsv($output, ['Número de Serie', 'Tipo de Periférico', 'Fecha de Compra', 'Número de Inventario', 'Estado', 'Usuario', 'Fecha Comentario', 'Comentario', 'Link PDF']);
    }

    // Insertar datos
    foreach ($resultados as $row) {
        if ($tipo_informe == 'equipo_usuario_observaciones') {
            fputcsv($output, [
                $row['num_inventario'],
                $row['us_dominio'],
                $row['nom_equipo'],
                $row['tipo_pc'],
                $row['marca'],
                $row['modelo'],
                $row['numero_parte'],
                $row['serial'],
                $row['procesador'],
                $row['disco_duro'],
                $row['memoria_ram'],
                $row['so'],
                $row['fecha_compra'],
                $row['fecha_v_garantia'],
                $row['estado'],
                $row['nombre'] . ' ' . $row['apellido'],
                $row['fecha_comentario'],
                $row['comentario'],
                $row['pdf']
            ]);
        } elseif ($tipo_informe == 'periferico_usuario_observaciones') {
            fputcsv($output, [
                $row['num_serie'],
                $row['tipo'],
                $row['fecha_compra'],
                $row['num_inventario'],
                $row['estado'],
                $row['nombre'] . ' ' . $row['apellido'],
                $row['fecha_comentario'],
                $row['comentario'],
                $row['pdf']
            ]);
        }
    }

    // Cerrar el archivo CSV
    fclose($output);
    exit;
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Generar Informes</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }
    h1, h2 {
        color: #FF0000;
    }
    h1 {
        margin-bottom: 0.5rem;
    }
    h2 {
        margin-bottom: 1rem;
    }
    label {
        font-weight: bold;
        color: #FF0000;
    }
    select {
        width: 100%;
        padding: 0.5rem;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #FF0000;
        border-radius: 4px;
        appearance: none;
        background-color: #fff;
        cursor: pointer;
    }
    .button-row {
        display: flex;
        justify-content: center;
        gap: 10px;
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
        font-size: 1rem;
    }
    input[type="submit"]:hover {
        background-color: #CC0000;
    }
    .table-container {
        width: 100%;
        overflow-x: auto; /* Permitir desplazamiento horizontal */
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }
    th, td {
        border: 1px solid #FF0000;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #FF0000;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
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
        font-weight: bold;
    }
    .back-button:hover {
        background-color: #CC0000;
    }
    .footer {
        margin-top: 20px;
        text-align: center;
        color: #333;
        font-size: 0.9rem;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
</style>
</head>

<body>
    <div class="header">
        <h1>SIADSI</h1>
        <p>Sistema de Administración de Sistemas</p>
        <h2>Generar Informes</h2>
    </div>

    <form method="POST" action="">
        <label for="tipo_informe">Seleccione el tipo de informe:</label>
        <select name="tipo_informe" id="tipo_informe">
            <option value="">Seleccione un informe...</option>
            <option value="equipo_usuario_observaciones">Equipo, Usuario y Observaciones</option>
            <option value="periferico_usuario_observaciones">Periférico, Usuario y Observaciones</option>
        </select>
        <div class="button-row">
            <input type="submit" name="generar_informe" value="Generar Informe">
            <input type="submit" name="exportar_excel" value="Exportar a Excel">
        </div>
    </form>

    <div class="table-container">
        <?php if (!empty($resultados)): ?>
        <table>
            <thead>
                <?php if ($tipo_informe == 'equipo_usuario_observaciones'): ?>
                    <tr>
                        <th>Número de Inventario</th>
                        <th>Dominio</th>
                        <th>Nombre de Equipo</th>
                        <th>Tipo de PC</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Número de Parte</th>
                        <th>Serial</th>
                        <th>Procesador</th>
                        <th>Disco Duro</th>
                        <th>Memoria RAM</th>
                        <th>Sistema Operativo</th>
                        <th>Fecha de Compra</th>
                        <th>Vencimiento de Garantía</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Fecha Comentario</th>
                        <th>Comentario</th>
                        <th>Link PDF</th>
                    </tr>
                <?php elseif ($tipo_informe == 'periferico_usuario_observaciones'): ?>
                    <tr>
                        <th>Número de Serie</th>
                        <th>Tipo de Periférico</th>
                        <th>Fecha de Compra</th>
                        <th>Número de Inventario</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Fecha Comentario</th>
                        <th>Comentario</th>
                        <th>Link PDF</th>
                    </tr>
                <?php endif; ?>
            </thead>
            <tbody>
                <?php foreach ($resultados as $row): ?>
                    <tr>
                        <?php if ($tipo_informe == 'equipo_usuario_observaciones'): ?>
                            <td><?php echo $row['num_inventario']; ?></td>
                            <td><?php echo $row['us_dominio']; ?></td>
                            <td><?php echo $row['nom_equipo']; ?></td>
                            <td><?php echo $row['tipo_pc']; ?></td>
                            <td><?php echo $row['marca']; ?></td>
                            <td><?php echo $row['modelo']; ?></td>
                            <td><?php echo $row['numero_parte']; ?></td>
                            <td><?php echo $row['serial']; ?></td>
                            <td><?php echo $row['procesador']; ?></td>
                            <td><?php echo $row['disco_duro']; ?></td>
                            <td><?php echo $row['memoria_ram']; ?></td>
                            <td><?php echo $row['so']; ?></td>
                            <td><?php echo $row['fecha_compra']; ?></td>
                            <td><?php echo $row['fecha_v_garantia']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></td>
                            <td><?php echo $row['fecha_comentario']; ?></td>
                            <td><?php echo $row['comentario']; ?></td>
                            <td><?php echo $row['pdf']; ?></td>
                        <?php elseif ($tipo_informe == 'periferico_usuario_observaciones'): ?>
                            <td><?php echo $row['num_serie']; ?></td>
                            <td><?php echo $row['tipo']; ?></td>
                            <td><?php echo $row['fecha_compra']; ?></td>
                            <td><?php echo $row['num_inventario']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></td>
                            <td><?php echo $row['fecha_comentario']; ?></td>
                            <td><?php echo $row['comentario']; ?></td>
                            <td><?php echo $row['pdf']; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <a href="menuinformes.php" class="back-button">Volver</a>

    <div class="footer">
        <p>&copy; 2024 SIADSI - Sistema de Administración de Sistemas</p>
    </div>

</body>
</html>
