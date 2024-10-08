<?php
include 'conexion.php';

$nombre = $_POST['txtnombre'];
$apellido = $_POST['txtapellido'];
$identificacion = $_POST['txtidentificacion'];
$usuario = $_POST['txtusuario'];
$password = $_POST['txtpassword'];
$estado = $_POST['txtestado'];

// Consulta para insertar los datos del usuario, incluyendo la identificación
$insertar = "INSERT INTO usuarios (nombre, apellido, identificacion, usuario, password, estado) 
             VALUES ('$nombre','$apellido','$identificacion','$usuario','$password','$estado')";

$query = mysqli_query($conn, $insertar);

if ($query) {
    // Mostrar alerta en JavaScript sin interferir con el header
    echo '<script type="text/javascript">alert("Usuario agregado Correctamente");</script>';
    echo '<script type="text/javascript">window.location.href = "reg_usuario.php";</script>';
} else {
    // Mostrar mensaje de error en caso de fallo
    echo '<script type="text/javascript">alert("Error, el usuario no se ha agregado correctamente");</script>';
    echo '<script type="text/javascript">window.location.href = "reg_usuario.php";</script>';
}
?>
