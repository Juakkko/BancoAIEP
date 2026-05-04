<?php
session_start();
$error = isset($_SESSION['error_login']) ? $_SESSION['error_login'] : "";
unset($_SESSION['error_login']);
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Acceso Clientes - Banco AIEP</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #001a33; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #ffffff; color: #333; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 350px; }
        h2 { text-align: center; color: #004a99; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #e63946; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background: #c1121f; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; text-align: center; font-size: 14px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>BANCO AIEP</h2>
        <?php if($error): ?> <div class="error-msg"><?php echo $error; ?></div> <?php endif; ?>

        <form action ="login.php" method="POST">
            <label>RUT Cliente</label>
            <input type="text" name="rut" placeholder="12345678-9" required>

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="**" required>

            <button type="submit">ENTRAR AL PORTAL</button>
        </form>
    </div>
</body>
</html>
