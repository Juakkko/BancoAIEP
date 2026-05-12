<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Traemos TODAS las cuentas (activas e inactivas) para poder reactivarlas
$sql = "SELECT c.id_cuenta, cl.nombre, cl.rut, c.numero_cuenta, c.saldo, c.activa, tc.nombre as tipo 
        FROM Cuenta c 
        INNER JOIN TipoCuenta tc ON c.id_tipo_cuenta = tc.id_tipo_cuenta 
        INNER JOIN Cliente cl ON c.id_cliente = cl.id_cliente 
        WHERE cl.id_cliente = ?";

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
    <!-- Bootstrap para el Modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="dashboard-body">

    <nav class="sidebar">
        <div class="logo">
            <h2>BANCO<span>AIEP</span></h2>
        </div>
        <ul class="nav-links">
            <li class="active"><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="transferencia.php"><i class="fas fa-exchange-alt"></i> Transferir</a></li>
            <li><a href="movimientos.php"><i class="fas fa-history"></i> Movimientos</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </nav>

    <main class="main-content">
        <?php require "header.php"; ?>

        <section class="content">
            
            <!-- Encabezado con Botón de Nueva Cuenta -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 class="section-title" style="margin: 0;">Mis Cuentas</h2>
                <button class="btn-action" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta" style="border:none; cursor:pointer;">
                    <i class="fas fa-plus-circle"></i> Nueva Cuenta
                </button>
            </div>

            <!-- Alertas de mensajes -->
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert success" style="background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #c3e6cb;">
                    <?php 
                        if($_GET['msg'] == 'creada') echo "¡Cuenta creada con éxito!";
                        if($_GET['msg'] == 'activada') echo "La cuenta ha sido reactivada.";
                        if($_GET['msg'] == 'desactivada') echo "La cuenta ha sido desactivada.";
                    ?>
                </div>
            <?php endif; ?>

            <div class="accounts-grid">
                <?php while ($cuenta = mysqli_fetch_assoc($resultado)): ?>
                    <!-- Aplicamos clase inactive si activa es 0 -->
                    <div class="account-card <?= ($cuenta['activa'] == 0) ? 'inactive' : '' ?>">
                        <div class="account-header">
                            <span class="account-type"><?php echo $cuenta['tipo']; ?></span>
                            <?php if($cuenta['activa'] == 0): ?>
                                <span class="badge bg-secondary">Inactiva</span>
                            <?php endif; ?>
                        </div>
                        <div class="account-number">N° <?php echo $cuenta['numero_cuenta']; ?></div>
                        <div class="account-balance">
                            <span class="label">Saldo disponible</span>
                            <span class="amount">$<?php echo number_format($cuenta['saldo'], 0, ',', '.'); ?></span>
                        </div>

                        <div class="account-actions" style="flex-direction: column; gap: 10px;">
                            <?php if ($cuenta['activa'] == 1): ?>
                                <!-- Si está activa: Transferir y Desactivar -->
                                <a href="transferencia.php" class="btn-action" style="text-align: center;">Transferir</a>
                                <a href="deshabilitar_cuenta.php?id=<?= $cuenta['id_cuenta'] ?>&estado=0" 
                                   onclick="return confirm('¿Seguro que quieres desactivar esta cuenta?')"
                                   class="btn-link" style="color: var(--primary-red); text-align: center; font-size: 13px;">
                                    Desactivar cuenta
                                </a>
                            <?php else: ?>
                                <!-- Si está inactiva: Solo Activar -->
                                <a href="deshabilitar_cuenta.php?id=<?= $cuenta['id_cuenta'] ?>&estado=1" 
                                   class="btn-action" style="text-align: center; background-color: #27ae60;">
                                    Activar Cuenta
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <!-- MODAL PARA NUEVA CUENTA -->
    <div class="modal fade" id="modalNuevaCuenta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <form action="crear_cuenta.php" method="POST">
                    <div class="modal-header" style="border-bottom: 1px solid #eee;">
                        <h5 class="modal-title" style="color: var(--dark-blue); font-weight: bold;">Solicitar Nueva Cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <div class="form-group">
                            <label style="font-weight: 600; color: #555;">Tipo de Producto:</label>
                            <select name="tipo_cuenta" class="form-select" required style="margin-top: 10px; padding: 12px;">
                                <option value="1">Cuenta Corriente</option>
                                <option value="2">Cuenta Vista</option>
                                <option value="3">Cuenta de Ahorro</option>
                            </select>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 20px;">
                            <small style="color: #666; display: block;">
                                <i class="fas fa-info-circle"></i> Al confirmar, se generará un nuevo número de cuenta automáticamente con saldo inicial de $0.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none; padding: 20px;">
                        <button type="button" class="btn text-secondary" data-bs-dismiss="modal" style="text-decoration: none; font-weight: 600;">Cancelar</button>
                        <button type="submit" class="btn-action" style="border: none; padding: 12px 25px;">Abrir Cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap para que el Modal funcione -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>