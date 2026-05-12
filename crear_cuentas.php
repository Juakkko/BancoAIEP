<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) exit();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $tipo_cuenta = $_POST['tipo_cuenta']; // ID del tipo (Vista, Corriente, etc.)
    $saldo_inicial = 0;
    $activa = 1;

    // Generar un número de cuenta aleatorio de 8 dígitos
    $numero_cuenta = rand(10000000, 99999999);

    $sql = "INSERT INTO Cuenta (id_cliente, id_tipo_cuenta, numero_cuenta, saldo, activa) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "iisdi", $user_id, $tipo_cuenta, $numero_cuenta, $saldo_inicial, $activa);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php?msg=creada");
    } else {
        header("Location: dashboard.php?error=creacion");
    }
}
?>