<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>SIADSI - Sistema de Administración de Sistemas</title>
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
        }
        h2 {
            color: #FF0000;
            text-align: center;
            margin: 0.5rem 0 1.5rem;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 0.5rem;
            border: none;
        }
        input[type="text"],
        input[type="password"] {
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
            display: block;
            margin: 1rem auto 0;
            width: auto;
        }
        input[type="submit"]:hover {
            background-color: #CC0000;
        }
        label {
            color: #FF0000;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }
    </style>
    <script>
        // Validación del formulario en el frontend con JavaScript
        function validarFormulario() {
            var usuario = document.getElementById("usuario").value.trim();
            var password = document.getElementById("password").value.trim();

            // Validar que el campo usuario no esté vacío y tenga al menos 3 caracteres
            if (usuario.length < 3) {
                alert("El nombre de usuario debe tener al menos 3 caracteres.");
                return false;
            }

            // Validar que la contraseña tenga al menos 6 caracteres
            if (password.length < 6) {
                alert("La contraseña debe tener al menos 6 caracteres.");
                return false;
            }

            // Validar que no haya espacios en el nombre de usuario o contraseña
            if (/\s/.test(usuario)) {
                alert("El nombre de usuario no debe contener espacios.");
                return false;
            }

            if (/\s/.test(password)) {
                alert("La contraseña no debe contener espacios.");
                return false;
            }

            return true; // Si todo es correcto, el formulario se envía
        }
    </script>
</head>

<body>
    <div class="header">
        <h1>SIADSI</h1>
        <span>Sistema de Administración de Sistemas</span>
    </div>
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" id="form_login" name="form_login" method="POST" onsubmit="return validarFormulario()">
            <table>
                <tr>
                    <td><label for="usuario">Usuario:</label></td>
                    <td><input type="text" name="txtusuario" id="usuario" required minlength="3" maxlength="20" pattern="^\S+$" title="El nombre de usuario no debe contener espacios"></td>
                </tr>
                <tr>
                    <td><label for="password">Contraseña:</label></td>
                    <td><input type="password" name="txtpassword" id="password" required minlength="6" maxlength="20" pattern="^\S+$" title="La contraseña no debe contener espacios"></td>
                </tr>
            </table>
            <input type="submit" name="btnenviar" id="enviar" value="Iniciar Sesion">
        </form>
    </div>
    <div class="footer">
        <p>&copy; 2024 JMC Enterprises. Todos los derechos reservados.</p>
    </div>
</body>
</html>
