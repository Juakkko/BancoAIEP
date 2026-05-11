<?php
session_start();
include 'db.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Verificar si llega el id
if (isset($_GET['id'])) {

    $id_cuenta = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Desactivar cuenta
    $sql = "UPDATE Cuenta  
            SET activa = 0  
            WHERE id_cuenta = $id_cuenta
            AND id_cliente = $user_id";

    if (mysqli_query($conexion, $sql)) {
//mensaje de éxito
        echo "
        <script>
            alert('Cuenta desactivada correctamente');
            header(location='dashboard.php');
        </script>
        ";
//mensaje de error
    } else {

        echo "
        <script>
            alert('Error al desactivar cuenta');
            header(location='dashboard.php');
        </script>
        ";
    }
}
?>