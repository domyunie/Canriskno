<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $contrasena = $_POST["contrasena"];

    if (empty($usuario) || empty($contrasena)) {
        $error = "Usuario y contraseña requeridos";
    } else {
        $usersFile = 'users.json';
        $usuarios = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        $foundUser = false;
        foreach ($usuarios as $u) {
            if ($u['usuario'] === $usuario && $u['contrasena'] === $contrasena) {
                $foundUser = true;
                break;
            }
        }

        if ($foundUser) {
            // Reemplazo del localStorage de Login.js
            $_SESSION['userSession'] = ['type' => 'users', 'username' => $usuario];
            
            $isEnglish = strpos($_SERVER['REQUEST_URI'], '/INGLES/') !== false;
            $redirectUrl = $isEnglish ? 'PrincipalING.html' : 'Principal.html';
            
            header("Location: $redirectUrl");
            exit;
        } else {
            $error = "Credenciales inválidas";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia Sesión - Canrisk</title>
    <link rel="stylesheet" href="../../CSS/register.css">
    <link rel="icon" type="image/png" href="../../MULTIMEDIA/Canrisk LOGO.svg">
</head>
<body>
    <?php if(!empty($error)) echo "<script>alert('$error');</script>"; ?>

    <div class="form">
        <a class="auth-brand" href="Principal.php">
            <img src="../../MULTIMEDIA/Canrisk LOGO.svg" alt="Canrisk">
            <span>Canrisk</span>
        </a>
        <form action="" method="POST">
            <h1>Iniciar Sesión</h1>
            <span class="auth-subtitle">Ingresa tus datos para continuar</span>

            <div class="field-group">
                <label class="User-text" for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" class="username" required>
            </div>

            <div class="field-group">
                <label class="User-text" for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" class="password" required>
            </div>

            <div class="submit-bttn">
                <button type="submit" class="submit-bttn" id="submit-bttn">Ingresa!</button>
            </div>

            <button type="button" class="regresar" onclick="window.history.back()">Regresar</button>

            <div class="register">
                <a class="resgis-txt" href="register.php">¿No tienes una cuenta?<br>Regístrate aquí</a>
            </div>
        </form>
    </div>
</body>
</html>