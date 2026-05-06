<?php
session_start();
include 'db.php';

// verificar si el usuario ha iniciado sesion
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
    
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// obtener cuentas activas vinculadas al usuario
$sql = "SELECT c.numero_cuenta, c.saldo, tc.nombre as tipo 
        FROM Cuenta c 
        INNER JOIN TipoCuenta tc ON c.id_tipo_cuenta = tc.id_tipo_cuenta 
        WHERE c.id_cliente = ? AND c.activa = 1";

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
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </nav>

   
    <main class="main-content">
        <header class="top-bar">
            <div class="user-info">
                <span>Hola, <strong><?php echo htmlspecialchars($user_name); ?></strong></span>
                <div class="avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
            </div>
        </header>

        <section class="content">
            <h2 class="section-title">Mis Cuentas</h2>
            
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
                        <span class="amount">$<?php echo number_format($cuenta['saldo'], 0, ',', '.'); ?></span>
                    </div>
                    <div class="account-actions">
                        <a href="transferencia.php" class="btn-action">Transferir</a>
                        <a href="#" class="btn-link">Ver detalles</a>
                
                <?php endwhile; ?>
            </div>
        </section>
    </main>

</body>
</html>