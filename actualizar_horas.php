<?php
session_start();
include("conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
  header("Location: Ingreso.html");
  exit();
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";

// 🔹 Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nuevas_horas = intval($_POST['horas_libres']);
  $semana = htmlspecialchars($_POST['semana'], ENT_QUOTES, 'UTF-8');

  // Validar que las horas no excedan el límite de 120
  if ($nuevas_horas > 120) {
    $nuevas_horas = 120;
    $mensaje = "⚠️ No puedes registrar más de 120 horas. Se ha ajustado al límite máximo.";
  }

  // Calcular las horas faltantes automáticamente
  $horas_faltantes = 120 - $nuevas_horas;

  // Actualizar los datos en la base de datos
  $sql = "UPDATE horas SET horas_libres = ?, horas_faltantes = ?, semana = ? WHERE id_usuario = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iisi", $nuevas_horas, $horas_faltantes, $semana, $id_usuario);

  if ($stmt->execute()) {
    if (empty($mensaje)) {
      $mensaje = "✅ Horas actualizadas correctamente.";
    }
  } else {
    $mensaje = "❌ Error al actualizar las horas: " . $conn->error;
  }

  $stmt->close();
}

// 🔹 Obtener los valores actuales del usuario
$sql = "SELECT * FROM horas WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$horas = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Si no existen datos, inicializar en 0
$horas_libres = $horas['horas_libres'] ?? 0;
$horas_faltantes = $horas['horas_faltantes'] ?? (120 - $horas_libres);
$semana_actual = $horas['semana'] ?? date('o-\WW');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualizar Horas - UNAB LifeAcademy</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d79d9f3b.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to bottom right, #003366, #0055aa);
      color: white;
      min-height: 100vh;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 2rem;
    }
    .progress-bar {
      width: 100%;
      height: 20px;
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
      overflow: hidden;
      margin-top: 10px;
    }
    .progress-fill {
      height: 100%;
      background: #00ffcc;
      width: 0%;
      transition: width 0.3s ease;
    }
  </style>
</head>

<body class="flex flex-col items-center justify-center p-6">
  <div class="glass-card w-full max-w-md text-center">
    <h1 class="text-3xl font-bold mb-4">Actualizar Horas</h1>
    <p class="text-blue-100 mb-6">Modifica tus horas académicas registradas (máx. 120).</p>

    <?php if (!empty($mensaje)): ?>
      <div class="mb-4 p-3 rounded-md bg-white/20">
        <p><?php echo $mensaje; ?></p>
      </div>
    <?php endif; ?>

    <form action="actualizar_horas.php" method="POST" class="space-y-4">
      <div class="text-left">
        <label class="block font-semibold mb-1">Horas libres (máx. 120)</label>
        <input type="number" id="horas_libres" name="horas_libres" 
          value="<?php echo htmlspecialchars($horas_libres); ?>" 
          min="0" max="120" required
          class="w-full p-3 rounded-md text-black focus:ring-[#003366] focus:border-[#003366]"
          oninput="actualizarFaltantes()">
      </div>

      <div class="text-left">
        <label class="block font-semibold mb-1">Horas faltantes</label>
        <input type="number" id="horas_faltantes" name="horas_faltantes"
          value="<?php echo htmlspecialchars($horas_faltantes); ?>" 
          readonly class="w-full p-3 rounded-md text-black bg-gray-200 cursor-not-allowed">
      </div>

      <div class="text-left">
        <label class="block font-semibold mb-1">Semana</label>
        <input type="text" name="semana" 
          value="<?php echo htmlspecialchars($semana_actual); ?>" required
          class="w-full p-3 rounded-md text-black focus:ring-[#003366] focus:border-[#003366]">
      </div>

      <div class="text-left mt-4">
        <label class="block font-semibold mb-2">Progreso</label>
        <div class="progress-bar">
          <div id="progress-fill" class="progress-fill"></div>
        </div>
        <p class="mt-2 text-sm text-blue-100">
          Has completado <span id="porcentaje">0</span>% de las 120 horas.
        </p>
      </div>

      <button type="submit"
        class="w-full py-3 rounded-md bg-blue-600 hover:bg-blue-700 transition text-white font-semibold mt-6">
        💾 Guardar cambios
      </button>

      <a href="inicio.php" class="block mt-4 text-blue-100 hover:text-white underline">
        ← Volver al perfil
      </a>
    </form>
  </div>

  <footer class="mt-8 text-sm text-blue-100">
    © 2025 Universidad Autónoma de Bucaramanga - UNAB LifeAcademy
  </footer>

  <script>
    function actualizarFaltantes() {
      const libres = parseInt(document.getElementById('horas_libres').value) || 0;
      const faltantes = Math.max(0, 120 - libres);
      document.getElementById('horas_faltantes').value = faltantes;

      // Actualizar barra de progreso
      const porcentaje = Math.min(100, (libres / 120) * 100);
      document.getElementById('progress-fill').style.width = porcentaje + '%';
      document.getElementById('porcentaje').textContent = porcentaje.toFixed(1);
    }

    // Ejecutar al cargar
    actualizarFaltantes();
  </script>
</body>
</html>
