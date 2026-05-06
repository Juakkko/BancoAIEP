<?php
// iniciar sesion para tomar datos del formulario
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // tomar datos enviados en el formulario
    $rut = trim($_POST['rut']);
    $pass = $_POST['password'];
// verificar si el rut es correcto
    $sql = "SELECT id_cliente, nombre, apellido, password FROM Cliente WHERE rut = ? AND activo = 1";
    
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $rut);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($resultado)) {
           // comparar la contraseña ingresada con la contraseña de la base de datos
            if ($user['password'] === $pass) { 
                // guardar datos en la sesion para identificar al usuario
                
                $_SESSION['user_id'] = $user['id_cliente'];
                $_SESSION['user_name'] = $user['nombre'] . " " . $user['apellido'];
                
                header("Location: dashboard.php");
                exit();
            } else {
              // mostrar error si la contraseña no existe   
                $_SESSION['error_login'] = "La contraseña ingresada es incorrecta.";
                header("Location: index.php");
                exit();
            }
        } else {
            //Error de RUT o cuenta inactiva
            $_SESSION['error_login'] = "El RUT no existe o la cuenta está inactiva.";
            header("Location: index.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        //Error de consulta
        $_SESSION['error_login'] = "Error interno del servidor. Intente más tarde.";
        header("Location: index.php");
        exit();
    }
}
?>