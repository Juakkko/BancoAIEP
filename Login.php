<?php

session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut = trim($_POST['rut']);
    $pass = $_POST['password'];

    $sql = "SELECT id_cliente, nombre, apellido, password FROM Cliente WHERE rut = ? AND activo = 1";

    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $rut);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($resultado)) {

            if ($user['password'] === $pass) { 
                $_SESSION['user_id'] = $user['id_cliente'];
                $_SESSION['user_name'] = $user['nombre'] . " " . $user['apellido'];

                header("Location: dashboard.php");
                exit();
            } else {

                $_SESSION['error_login'] = "La contraseña ingresada es incorrecta.";
                header("Location: Index.php");
                exit();
            }
        } else {

            $_SESSION['error_login'] = "El RUT no existe o la cuenta está inactiva.";
            header("Location: Index.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_login'] = "Error interno del servidor. Intente más tarde.";
        header("Location: Index.php");
        exit();
    }
}
?>