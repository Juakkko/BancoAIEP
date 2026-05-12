<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) exit();

if (isset($_GET['id'])) {
    $id_cuenta = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Cambiamos el estado a 0 (Inactiva)
    $sql = "UPDATE Cuenta SET activa = 0 WHERE id_cuenta = ? AND id_cliente = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_cuenta, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php?msg=desactivada");
    } else {
        header("Location: dashboard.php?error=1");
    }
}