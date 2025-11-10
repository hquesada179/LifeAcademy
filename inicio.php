<?php
session_start();
include("conexion.php");

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['usuario'])) {
  header("Location: Ingreso.html");
  exit();
}

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];

// 🔹 Obtener las horas del usuario
$sql = "SELECT horas_libres, horas_faltantes FROM horas WHERE id_usuario = (SELECT id FROM usuarios WHERE usuario = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
$horas = $result->fetch_assoc();

$horas_libres = $horas['horas_libres'] ?? 0;
$horas_faltantes = $horas['horas_faltantes'] ?? (120 - $horas_libres);
$porcentaje = min(100, ($horas_libres / 120) * 100);

// 🔸 Determinar insignia según progreso
if ($horas_libres >= 120) {
  $insignia = "oro";
  $titulo_insignia = "🏆 Insignia de Oro";
  $color = "from-yellow-400 to-yellow-600";
} elseif ($horas_libres >= 80) {
  $insignia = "plata";
  $titulo_insignia = "🥈 Insignia de Plata";
  $color = "from-gray-300 to-gray-500";
} elseif ($horas_libres >= 40) {
  $insignia = "bronce";
  $titulo_insignia = "🥉 Insignia de Bronce";
  $color = "from-amber-600 to-amber-800";
} else {
  $insignia = "ninguna";
  $titulo_insignia = "🚀 Aún sin insignia";
  $color = "from-blue-300 to-blue-500";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - UNAB LifeAcademy</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d79d9f3b.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to bottom right, #003366, #0055aa);
      color: white;
      min-height: 100vh;
      transition: background 0.5s ease, color 0.3s ease;
    }
    .dark-mode {
      background: linear-gradient(to bottom right, #0a0a0a, #1e293b);
      color: #f1f5f9;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 2rem;
    }
    .dark-mode .glass-card {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.15);
    }
  </style>
</head>

<body class="flex flex-col min-h-screen transition-all duration-500">
  <!-- 🔹 Barra superior -->
  <header class="p-4 bg-white/10 backdrop-blur-md shadow-md flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <img src="Login_img/Logo_2.png" alt="Logo UNAB" class="w-12 h-12 rounded-full">
      <h1 class="text-2xl font-bold">UNAB LifeAcademy</h1>
    </div>

    <nav class="flex items-center gap-3">
      <a href="inicio.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Perfil</a>
      <a href="eventos.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Eventos</a>
      <a href="noticias.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Noticias</a>
      <a href="calendario.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Calendario</a>
      <a href="mapa.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Mapa</a>
      <a href="ayuda.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Ayuda</a>
    </nav>

    <div class="flex items-center space-x-4">
      <button id="darkModeToggle" class="bg-white/20 hover:bg-white/30 p-2 rounded-md text-lg transition">
        🌙
      </button>
      <span class="text-lg font-semibold">👋 Hola, <?php echo htmlspecialchars($nombre); ?></span>
      <a href="logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg shadow-md font-semibold transition-all">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
  </header>

  <!-- 🔹 Contenido principal -->
  <main class="flex-1 flex flex-col items-center justify-center p-6 text-center">
    <div class="glass-card w-full max-w-2xl shadow-lg">
      <h2 class="text-3xl font-bold mb-4">¡Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h2>
      <p class="text-lg mb-6 text-blue-100">Aquí podrás ver tu progreso académico y tus logros obtenidos.</p>

      <!-- 🔸 Barra de progreso -->
      <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Progreso de horas</h3>
        <div class="w-full bg-white/20 rounded-full h-5 overflow-hidden mb-2">
          <div class="bg-green-400 h-5 rounded-full transition-all" style="width: <?php echo $porcentaje; ?>%;"></div>
        </div>
        <p><?php echo $horas_libres; ?> / 120 horas completadas</p>
      </div>

      <!-- 🔸 Insignia dinámica -->
      <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Tu insignia actual</h3>
        <div class="inline-block px-6 py-4 rounded-xl bg-gradient-to-r <?php echo $color; ?> shadow-lg">
          <span class="text-2xl font-bold"><?php echo $titulo_insignia; ?></span>
        </div>
      </div>

      <!-- 🔸 Acciones -->
      <div class="mt-8 flex flex-col gap-4">
        <a href="actualizar_horas.php" class="bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-lg font-semibold transition">
          <i class="fas fa-clock mr-2"></i> Actualizar horas
        </a>
        <a href="noticias.php" class="bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-lg font-semibold transition">
          <i class="fas fa-newspaper mr-2"></i> Noticias académicas
        </a>
      </div>
    </div>
  </main>

  <footer class="p-4 text-center text-blue-100 text-sm">
    © 2025 Universidad Autónoma de Bucaramanga — UNAB LifeAcademy
  </footer>

  <!-- 🔹 Script de modo oscuro -->
  <script>
    const body = document.body;
    const toggle = document.getElementById('darkModeToggle');

    // Aplicar modo oscuro guardado
    if (localStorage.getItem('dark-mode') === 'enabled') {
      body.classList.add('dark-mode');
      toggle.textContent = '☀️';
    }

    // Alternar modo oscuro
    toggle.addEventListener('click', () => {
      body.classList.toggle('dark-mode');
      const enabled = body.classList.contains('dark-mode');
      toggle.textContent = enabled ? '☀️' : '🌙';
      localStorage.setItem('dark-mode', enabled ? 'enabled' : 'disabled');
    });
  </script>
</body>
</html>
