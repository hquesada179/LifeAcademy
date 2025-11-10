<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: Ingreso.html");
  exit();
}

$nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Noticias - UNAB LifeAcademy</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d79d9f3b.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to bottom right, #003366, #0055aa);
      color: white;
      transition: background 0.5s, color 0.3s;
    }

    .dark-mode {
      background: linear-gradient(to bottom right, #0a0a0a, #1e293b);
      color: #f1f5f9;
    }

    .news-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      padding: 1.5rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      transition: transform 0.3s, background 0.3s;
    }

    .news-card:hover {
      transform: scale(1.03);
      background: rgba(255, 255, 255, 0.15);
    }
  </style>
</head>

<body class="min-h-screen flex flex-col">
  <!-- 🔹 Encabezado -->
  <header class="p-4 bg-white/10 backdrop-blur-md shadow-md flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <img src="Login_img/Logo_2.png" alt="Logo UNAB" class="w-12 h-12 rounded-full">
      <h1 class="text-2xl font-bold">UNAB LifeAcademy</h1>
    </div>

    <nav class="flex items-center gap-3">
      <a href="inicio.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Perfil</a>
      <a href="eventos.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Eventos</a>
      <a href="noticias.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Noticias</a>
      <a href="calendario.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Calendario</a>
      <a href="mapa.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Mapa</a>
      <a href="ayuda.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Ayuda</a>
    </nav>

    <div class="flex items-center space-x-4">
      <button id="darkModeToggle" class="bg-white/20 hover:bg-white/30 p-2 rounded-md text-lg transition">🌙</button>
      <span class="font-semibold">👋 Hola, <?php echo htmlspecialchars($nombre); ?></span>
      <a href="logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition-all">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
  </header>

  <!-- 🔹 Contenido principal -->
  <main class="flex-1 p-8">
  <h2 class="text-3xl font-bold mb-8 text-center">📰 Noticias y Comunicados UNAB</h2>

  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- 📰 Noticia 1 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">🎉 ¡Ingeniotic 2025 se acerca!</h3>
      <time class="block text-blue-100 mb-2">15 de noviembre de 2025</time>
      <p>La feria tecnológica más grande de la UNAB abre sus puertas. ¡Inscríbete ya en la sección de Eventos!</p>
    </article>

    <!-- 📰 Noticia 2 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">📚 Ulibro: Feria del Libro UNAB</h3>
      <time class="block text-blue-100 mb-2">10-12 de noviembre</time>
      <p>Conferencias, talleres y descuentos en libros. Abierto a toda la comunidad universitaria.</p>
    </article>

    <!-- 📰 Noticia 3 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">⚠️ Cierre de inscripciones para horas libres</h3>
      <time class="block text-blue-100 mb-2">1 de diciembre de 2025</time>
      <p>No olvides completar tus 20 horas libres antes de la fecha límite.</p>
    </article>

    <!-- 📰 Noticia 4 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">💡 Nueva carrera en Ciberseguridad en la UNAB</h3>
      <time class="block text-blue-100 mb-2">20 de febrero de 2025</time>
      <p>La Facultad de Ingeniería lanza el nuevo programa de Ciberseguridad, enfocado en proteger sistemas y datos en entornos digitales.</p>
    </article>

    <!-- 📰 Noticia 5 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">🌱 UNAB Verde: campaña de sostenibilidad 2025</h3>
      <time class="block text-blue-100 mb-2">5 de marzo de 2025</time>
      <p>Se inaugura el programa “UNAB Verde” para fomentar prácticas sostenibles en el campus, reciclaje y energía renovable.</p>
    </article>

    <!-- 📰 Noticia 6 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">🤖 Hackathon UNAB 2025: Innovación y creatividad</h3>
      <time class="block text-blue-100 mb-2">18 de abril de 2025</time>
      <p>Estudiantes competirán durante 48 horas desarrollando soluciones tecnológicas para retos reales de la región.</p>
    </article>

    <!-- 📰 Noticia 7 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">🎓 Reconocimiento a egresados destacados</h3>
      <time class="block text-blue-100 mb-2">25 de mayo de 2025</time>
      <p>La UNAB celebra los logros de sus egresados que hoy lideran proyectos empresariales y sociales en Colombia y el exterior.</p>
    </article>

    <!-- 📰 Noticia 8 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">🧬 Investigación UNAB obtiene premio nacional</h3>
      <time class="block text-blue-100 mb-2">30 de junio de 2025</time>
      <p>Un grupo de investigadores UNAB ganó el premio Colciencias por su estudio sobre inteligencia artificial aplicada a la educación.</p>
    </article>

    <!-- 📰 Noticia 9 -->
    <article class="news-card">
      <h3 class="text-xl font-bold mb-2">🎭 Semana Cultural UNAB</h3>
      <time class="block text-blue-100 mb-2">15 de agosto de 2025</time>
      <p>Teatro, danza, música y exposiciones de arte llenarán el campus en la Semana Cultural más esperada del año.</p>
    </article>
  </div>
</main>


  <footer class="p-4 text-center text-blue-100 text-sm">
    © 2025 Universidad Autónoma de Bucaramanga — UNAB LifeAcademy
  </footer>

  <!-- 🔹 Script modo oscuro -->
  <script>
    const body = document.body;
    const toggle = document.getElementById('darkModeToggle');

    // Aplicar modo oscuro guardado
    if (localStorage.getItem('dark-mode') === 'enabled') {
      body.classList.add('dark-mode');
      toggle.textContent = '☀️';
    }

    toggle.addEventListener('click', () => {
      body.classList.toggle('dark-mode');
      const enabled = body.classList.contains('dark-mode');
      toggle.textContent = enabled ? '☀️' : '🌙';
      localStorage.setItem('dark-mode', enabled ? 'enabled' : 'disabled');
    });
  </script>
</body>
</html>
