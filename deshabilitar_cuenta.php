<?php
session_start();
include 'db.php';
// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) exit();

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id_cuenta = $_GET['id'];
    $nuevo_estado = $_GET['estado']; // Recibimos 0 para desactivar, 1 para activar
    $user_id = $_SESSION['user_id'];

    // Actualizamos al estado que nos manden (0 o 1)
    $sql = "UPDATE Cuenta SET activa = ? WHERE id_cuenta = ? AND id_cliente = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $nuevo_estado, $id_cuenta, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $msg = ($nuevo_estado == 1) ? "activada" : "desactivada";
        header("Location: dashboard.php?msg=$msg");
    } else {
        header("Location: dashboard.php?error=1");
    }
}