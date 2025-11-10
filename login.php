<?php
include("conexion.php");

session_start(); // 🔹 Iniciar sesión PHP

$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

// Buscar el usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verificar la contraseña (encriptada)
    if (password_verify($contraseña, $row['contraseña'])) {
        // ✅ Guardar datos de sesión
        $_SESSION['id_usuario'] = $row['id'];         // ← 🔹 Esta línea es la que faltaba
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['nombre'] = $row['nombre'];

        // Redirigir al panel principal
        header("Location: inicio.php");
        exit();
    } else {
        echo "<script>
            alert('⚠️ Contraseña incorrecta.');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('⚠️ Usuario no encontrado.');
        window.history.back();
    </script>";
}

$conn->close();
?>
