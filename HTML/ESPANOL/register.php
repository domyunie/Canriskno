<?php
session_start();
$error = "";
$success_script = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $usuario = trim($_POST["usuario"]);
    $contrasena = trim($_POST["contrasena"]);

    // Validaciones iguales a las de JS
    if (strlen($name) < 3) {
        $error = "El nombre debe tener al menos 3 caracteres.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $usuario)) {
        $error = "El usuario debe tener entre 4 y 20 caracteres (letras, números o guión bajo).";
    } elseif (strlen($contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $usersFile = 'users.json';
        $usuarios = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        // Verificar si el usuario ya existe
        $usuarioExiste = false;
        foreach ($usuarios as $u) {
            if ($u['usuario'] === $usuario) {
                $usuarioExiste = true;
                break;
            }
        }

        if ($usuarioExiste) {
            $error = "Ese nombre de usuario ya está en uso. Elige otro.";
        } else {
            // Registrar nuevo usuario
            $usuarios[] = [
                'id' => time(),
                'nombre' => $name,
                'usuario' => $usuario,
                'contrasena' => $contrasena, // En un entorno real, usa password_hash()
                'fechaRegistro' => date("d/m/Y")
            ];
            file_put_contents($usersFile, json_encode($usuarios));
            
            $isEnglish = strpos($_SERVER['REQUEST_URI'], '/INGLES/') !== false;
            $redirectUrl = $isEnglish ? 'loginING.php' : 'login.php';
            
            $success_script = "<script>alert('¡Registro exitoso! Bienvenido, $name'); window.location.href='$redirectUrl';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrate - Canrisk</title>
    <link rel="stylesheet" href="../../CSS/register.css">
    <link rel="icon" type="image/png" href="../../MULTIMEDIA/Canrisk LOGO.svg">
</head>
<body>
    <?php if(!empty($error)) echo "<script>alert('$error');</script>"; ?>
    <?php if(!empty($success_script)) echo $success_script; ?>

    <div class="form">
        <a class="auth-brand" href="Principal.php">
            <img src="../../MULTIMEDIA/Canrisk LOGO.svg" alt="Canrisk">
            <span>Canrisk</span>
        </a>
        <form action="" method="POST">
            <h1>¡Crea una cuenta de Canrisk!</h1>
            <span class="auth-subtitle">Regístrate para acceder a todo el contenido</span>

            <div class="field-group">
                <label class="User-text" for="name">Nombre:</label>
                <input type="text" id="name" name="name" placeholder="Ingrese su nombre" class="name" required>
            </div>

            <div class="field-group">
                <label class="User-text" for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" class="username" required>
            </div>

            <div class="field-group">
                <label class="User-text" for="contrasena">Contraseña:</label>
                <!-- Actualizado a name="contrasena" para evitar problemas con la "ñ" -->
                <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" class="password" required>
            </div>

            <div class="submit-bttn">
                <button type="submit" class="submit-bttn" id="submit-bttn">Crear cuenta</button>
            </div>

            <button type="button" class="regresar" onclick="window.history.back()">Regresar</button>

            <div class="register">
                <a class="resgis-txt" href="login.php">¿Ya tienes una cuenta?<br>Ingresa a Canrisk</a>
            </div>
        </form>
    </div>
</body>
</html>