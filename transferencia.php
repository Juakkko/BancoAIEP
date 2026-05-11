<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) header("Location: index.php");

$u_id = $_SESSION['user_id'];
$u_name = $_SESSION['user_name'];
$done = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar'])) {
    $monto = $_POST['monto'];
    $ori = $_POST['cuenta_origen'];
    $dest_n = $_POST['cuenta_destino'];

    $res = mysqli_query($conexion, "SELECT id_cuenta FROM Cuenta WHERE numero_cuenta = '$dest_n' LIMIT 1");
    if ($d = mysqli_fetch_assoc($res)) {
        $dest_id = $d['id_cuenta'];
        mysqli_begin_transaction($conexion);
        
        $q1 = mysqli_query($conexion, "UPDATE Cuenta SET saldo = saldo - $monto WHERE id_cuenta = $ori");
        $q2 = mysqli_query($conexion, "UPDATE Cuenta SET saldo = saldo + $monto WHERE id_cuenta = $dest_id");
        $q3 = mysqli_query($conexion, "INSERT INTO Transaccion (id_cuenta_origen, id_cuenta_destino, monto, id_tipo_transaccion, fecha_hora) VALUES ($ori, $dest_id, $monto, 1, NOW())");

        ($q1 && $q2 && $q3) ? (mysqli_commit($conexion) . $done = true) : mysqli_rollback($conexion);
    } else {
        echo "<script>alert('Cuenta no existe');</script>";
    }
}
$cuentas = mysqli_query($conexion, "SELECT * FROM Cuenta WHERE id_cliente = $u_id AND activa = 1");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transferir - Banco AIEP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">
    <nav class="sidebar">
        <div class="logo"><h2>BANCO<span>AIEP</span></h2></div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li class="active"><a href="transferencia.php"><i class="fas fa-exchange-alt"></i> Transferir</a></li>
            <li><a href="movimientos.php"><i class="fas fa-history"></i> Movimientos</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </nav>

    <main class="main-content">
        <header class="top-bar">
            <div class="user-info">
                <span>Hola, <strong><?= $u_name ?></strong></span>
                <div class="avatar"><?= strtoupper($u_name[0]) ?></div>
            </div>
        </header>

        <section class="content">
            <div class="transfer-container">
                <?php if ($done): ?>
                    <div style="text-align:center; padding:20px;">
                        <i class="fas fa-check-circle" style="font-size:50px; color:#28a745"></i>
                        <h2 style="margin:15px 0;">Transferencia Lista</h2>
                        <a href="transferencia.php" class="btn-primary-red">Hacer otra</a>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Desde:</label>
                            <select name="cuenta_origen" required>
                                <?php while($c = mysqli_fetch_assoc($cuentas)): ?>
                                    <option value="<?= $c['id_cuenta'] ?>">N° <?= $c['numero_cuenta'] ?> ($<?= number_format($c['saldo'], 0, ',', '.') ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>A la cuenta:</label>
                            <input type="text" name="cuenta_destino" placeholder="N° de cuenta" required>
                        </div>
                        <div class="form-group">
                            <label>Monto:</label>
                            <input type="number" name="monto" min="1" placeholder="$ 0" required>
                        </div>
                        <button type="submit" name="confirmar" class="btn-primary-red">Confirmar Transferencia</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>