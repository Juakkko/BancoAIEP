<?php
include 'db.php';
session_start();
$error = "";

if ($_POST) {
    $stmt = mysqli_prepare($conexion, "SELECT * FROM Cliente WHERE rut = ? AND activo = 1");
    mysqli_stmt_bind_param($stmt, "s", $_POST['rut']);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && $user['password'] === $_POST['password']) {
        $_SESSION['user_id'] = $user['id_cliente'];
        $_SESSION['user_name'] = $user['nombre'] . " " . $user['apellido'];
        header("Location: Index.php");
        exit;
    }

}
?>