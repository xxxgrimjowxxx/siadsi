
<?php
session_start();
include 'conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST['txtusuario']);
    $password = mysqli_real_escape_string($conn, $_POST['txtpassword']);

    // Consulta para verificar si el usuario y la contraseña son correctos
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuario válido, iniciamos la sesión
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id_usuario'];
        $_SESSION['nombre'] = $row['nombre'];

        // Redirigir al menú principal
        header("Location: menu.php");
    } else {
        // Si no se encontró el usuario o está inactivo
        echo "Usuario o contraseña incorrectos, o usuario inactivo.";
    }
}
?>
