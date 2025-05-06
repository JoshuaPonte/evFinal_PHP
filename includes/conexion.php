<?php
$conexion = new mysqli("localhost", "root", "", "bs_usuarios");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
