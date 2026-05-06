<?php
$servidor = "localhost";
$usuario  = "root";
$password = "";
$base_de_datos = "BancoAIEP";


$conexion = mysqli_connect($servidor, $usuario, $password, $base_de_datos);

if (!$conexion) {

    die("Error de conexión al BancoAIEP: " . mysqli_connect_error());
}


mysqli_set_charset($conexion, "utf8");

 ?>