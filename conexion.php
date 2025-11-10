<?php
$servername = "localhost";
$username = "root"; // usuario por defecto de XAMPP
$password = ""; // sin contraseña
$database = "lifeacademy_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
