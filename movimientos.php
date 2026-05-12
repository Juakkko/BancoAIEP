<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name']; 


$sql_cta = "SELECT id_cuenta FROM Cuenta WHERE id_cliente = ? LIMIT 1";
$stmt_c = mysqli_prepare($conexion, $sql_cta);
mysqli_stmt_bind_param($stmt_c, "i", $user_id);
mysqli_stmt_execute($stmt_c);
$res_c = mysqli_stmt_get_result($stmt_c);
$fila_cta = mysqli_fetch_assoc($res_c);
$mi_id_cuenta = $fila_cta['id_cuenta'];


$sql = "SELECT t.*, tt.nombre as tipo_nombre 
        FROM Transaccion t 
        JOIN TipoTransaccion tt ON t.id_tipo_transaccion = tt.id_tipo_transaccion
        WHERE t.id_cuenta_origen = ? OR t.id_cuenta_destino = ? 
        ORDER BY t.fecha_hora DESC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $mi_id_cuenta, $mi_id_cuenta);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimientos - Banco AIEP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">
    <nav class="sidebar">
        <div class="logo"><h2>BANCO<span>AIEP</span></h2></div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="transferencia.php"><i class="fas fa-exchange-alt"></i> Transferir</a></li>
            <li class="active"><a href="movimientos.php"><i class="fas fa-history"></i> Movimientos</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
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
            <div class="table-container">
                <table class="movimientos-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Detalle</th>
                            <th style="text-align: right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($r = mysqli_fetch_assoc($resultado)): 
                            $es_ingreso = ($r['id_cuenta_destino'] == $mi_id_cuenta);
                            $clase = $es_ingreso ? 'monto-ingreso' : 'monto-egreso';
                            $simbolo = $es_ingreso ? '+$' : '-$';
                        ?>
                        <tr>
                            <td><?php echo date('d/m/y H:i', strtotime($r['fecha_hora'])); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($r['tipo_nombre']); ?></strong><br>
                                <small><?php echo htmlspecialchars($r['descripcion']); ?></small>
                            </td>
                            <td class="<?php echo $clase; ?>" style="text-align: right;">
                                <?php echo $simbolo . number_format($r['monto'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>