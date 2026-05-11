<?php
session_start();
// recuperar y borrar mensaje de error anterior
$error = isset($_SESSION['error_login']) ? $_SESSION['error_login'] : "";
unset($_SESSION['error_login']);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Clientes - Banco AIEP</title>
    <!-- Conectamos con el archivo de estilos -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

    <div class="login-card">
        <h2>BANCO AIEP</h2>
        
        <?php if($error): ?> 
            <div class="error-msg"><?php echo $error; ?></div> 
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <label>RUT Cliente</label>
            <input type="text" name="rut" placeholder="12345678-9" required>
            
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="******" required>
            
            <button type="submit">ENTRAR AL PORTAL</button>
        </form>
    </div>

</body>
</html>