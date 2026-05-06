<?php
// datos para entrar a la base de datos
$servidor = "localhost";
$usuario  = "root";
$password = "";
$base_de_datos = "BancoAIEP";


// intentar conectar al servidor
$conexion = mysqli_connect($servidor, $usuario, $password, $base_de_datos);

if (!$conexion) {

    die("Error de conexión al BancoAIEP: " . mysqli_connect_error());
}

// mensaje de error 
mysqli_set_charset($conexion, "utf8");

 ?>