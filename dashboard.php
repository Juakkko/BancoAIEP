<?php
session_start();
include 'db.php';
// Verificamos si el usuario ha iniciado sesión, si no, lo redirigimos al login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
// Obtenemos el ID y nombre del usuario desde la sesión
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Consulta para obtener las cuentas activas del cliente junto con su tipo
$sql = "SELECT c.numero_cuenta, c.saldo, tc.nombre as tipo 
        FROM Cuenta c 
        INNER JOIN TipoCuenta tc ON c.id_tipo_cuenta = tc.id_tipo_cuenta 
        WHERE c.id_cliente = ? AND c.activa = 1";

// Preparamos la consulta para evitar inyecciones SQL
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Banco - Banco AIEP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">

    
    <nav class="sidebar">
        <div class="logo">
            <h2>BANCO<span>AIEP</span></h2>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="transferencia.php"><i class="fas fa-exchange-alt"></i> Transferir</a></li>
            <li><a href="movimientos.php"><i class="fas fa-history"></i> Movimientos</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </nav>

   
    <main class="main-content">
        <header class="top-bar">
            <div class="user-info">
                <!-- Mostramos el nombre del usuario y una imagen con la inicial -->
                <span>Hola, <strong><?php echo htmlspecialchars($user_name); ?></strong></span>
                <div class="avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
            </div>
        </header>

        <section class="content">
            <h2 class="section-title">Mis Cuentas</h2>
            <!-- Mostramos las cuentas del cliente -->
            <div class="accounts-grid">
                <?php while($cuenta = mysqli_fetch_assoc($resultado)): ?>
                <div class="account-card">
                    <div class="account-header">
                        <span class="account-type"><?php echo $cuenta['tipo']; ?></span>
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <div class="account-number">N° <?php echo $cuenta['numero_cuenta']; ?></div>
                    <div class="account-balance">
                        <span class="label">Saldo disponible</span>
                        <!-- Separamos los miles con puntos -->
                        <span class="amount">$<?php echo number_format($cuenta['saldo'], 0, ',', '.'); ?></span>
                    </div>
                    <a href="eliminar_cuenta.php?id=<?= ['id_user'] ?>";
                        onclick="return confirm('¿Desea desactivar esta cuenta?')"
                        class="btn-primary-red">Desactivar cuenta</a>
                    <div class="account-actions">
                        <a href="transferencia.php" class="btn-action">Transferir</a>
                        <a href="#" class="btn-link">Ver detalles</a>
                
                <?php endwhile; ?>
            </div>
        </section>
    </main>

</body>
</html>