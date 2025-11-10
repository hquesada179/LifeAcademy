<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $usuario = $_POST['usuario'];
  $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

  // Insertar usuario
  $sql = "INSERT INTO usuarios (nombre, correo, usuario, contraseña)
          VALUES ('$nombre', '$correo', '$usuario', '$contraseña')";

  if ($conn->query($sql) === TRUE) {
    $id_usuario = $conn->insert_id;

    // Insertar horas iniciales (10 libres, 5 faltantes)
    $conn->query("INSERT INTO horas (id_usuario, horas_libres, horas_faltantes, semana)
                  VALUES ($id_usuario, 10, 5, 'Semana 1')");

    // Asignar insignia inicial
    $conn->query("INSERT INTO insignias (id_usuario, nombre_insignia, descripcion, icono)
                  VALUES ($id_usuario, 'Nuevo Miembro', 'Te has unido a LifeAcademy', '⭐')");

    echo "<script>
      alert('Cuenta creada exitosamente. ¡Bienvenido a UNAB LifeAcademy!');
      window.location.href='Ingreso.html';
    </script>";
  } else {
    echo "<script>
      alert('Error al registrar el usuario: " . $conn->error . "');
      window.history.back();
    </script>";
  }
}

$conn->close();
?>
