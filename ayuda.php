<?php
session_start();
include("conexion.php");

// Si no hay sesión activa, redirige al login
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
  <title>Ayuda - UNAB LifeAcademy</title>
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
      color: white;
    }

    .dark-mode .glass-card {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.15);
    }

    details {
      background: rgba(255, 255, 255, 0.1);
      padding: 1rem;
      border-radius: 0.75rem;
      margin-bottom: 0.75rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    details:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    summary {
      font-weight: 600;
      outline: none;
    }

    .chat-box {
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 1rem;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.1);
      height: 250px;
      overflow-y: auto;
      margin-bottom: 1rem;
    }

    .chat-message {
      margin-bottom: 0.5rem;
    }

    .chat-message strong {
      color: #ffd700;
    }
  </style>
</head>

<body class="flex flex-col min-h-screen transition-all duration-500">
  <!-- 🔹 Barra superior idéntica a inicio.php -->
  <header class="p-4 bg-white/10 backdrop-blur-md shadow-md flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <img src="Login_img/Logo_2.png" alt="Logo UNAB" class="w-12 h-12 rounded-full">
      <h1 class="text-2xl font-bold">UNAB LifeAcademy</h1>
    </div>

    <nav class="flex items-center gap-3">
      <a href="inicio.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Perfil</a>
      <a href="eventos.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Eventos</a>
      <a href="noticias.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Noticias</a>
      <a href="calendario.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Calendario</a>
      <a href="mapa.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Mapa</a>
      <a href="ayuda.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Ayuda</a>
    </nav>

    <div class="flex items-center space-x-4">
      <button id="darkModeToggle" class="bg-white/20 hover:bg-white/30 p-2 rounded-md text-lg transition">🌙</button>
      <span class="text-lg font-semibold">👋 Hola, <?php echo htmlspecialchars($nombre); ?></span>
      <a href="logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg shadow-md font-semibold transition-all">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
  </header>

  <!-- 🔹 Contenido principal -->
  <main class="flex-1 flex flex-col items-center justify-start p-8 text-center">
    <div class="glass-card w-full max-w-3xl shadow-lg">
      <h2 class="text-3xl font-bold mb-4">🆘 Centro de Ayuda y Soporte</h2>
      <p class="text-blue-100 mb-6">Encuentra respuestas a tus dudas o comunícate con nuestro soporte.</p>

      <!-- 🔸 Preguntas Frecuentes -->
      <section class="text-left mb-8">
        <h3 class="text-xl font-semibold mb-3">📖 Preguntas Frecuentes</h3>

        <details>
          <summary>¿Cómo consulto mis horas libres?</summary>
          <p>Ve a la sección <strong>Perfil</strong> y podrás ver tus horas completadas y faltantes.</p>
        </details>

        <details>
          <summary>¿Cómo puedo registrarme en un evento?</summary>
          <p>Dirígete a <strong>Eventos</strong>, busca el evento que te interese y presiona “Registrar”.</p>
        </details>

        <details>
          <summary>¿Cómo obtengo una insignia?</summary>
          <p>Las insignias se otorgan automáticamente al cumplir metas de horas libres (Bronce, Plata, Oro).</p>
        </details>

        <details>
          <summary>¿Qué hago si olvido mi contraseña?</summary>
          <p>Comunícate con soporte técnico al correo <a href="mailto:soporte@unab.edu.co" class="text-yellow-300 underline">soporte@unab.edu.co</a>.</p>
        </details>
      </section>

      <!-- 🔸 Chat Simulado -->
      <section class="text-left mb-8">
        <h3 class="text-xl font-semibold mb-3">💬 Chat de Soporte (Simulado)</h3>

        <div id="chatBox" class="chat-box">
          <div class="chat-message"><strong>Bot:</strong> ¡Hola! 👋 Soy tu asistente virtual. Pregúntame sobre eventos, horas o insignias.</div>
        </div>

        <div class="flex gap-2">
          <input id="chatInput" type="text" placeholder="Escribe tu pregunta..." class="flex-1 px-3 py-2 rounded-md text-black focus:outline-none">
          <button onclick="sendMessage()" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-md font-semibold transition">Enviar</button>
        </div>
      </section>

      <!-- 🔸 Contacto -->
      <section>
        <h3 class="text-xl font-semibold mb-3">📞 Contacto Directo</h3>
        <p>Correo: <a href="mailto:soporte@unab.edu.co" class="text-yellow-300 underline">soporte@unab.edu.co</a></p>
        <p>Teléfono: +57 607 634 4000 (Lunes a Viernes, 8:00 a.m. - 4:00 p.m.)</p>
      </section>
    </div>
  </main>

  <footer class="p-4 text-center text-blue-100 text-sm">
    © 2025 Universidad Autónoma de Bucaramanga — UNAB LifeAcademy
  </footer>

  <!-- 🔹 Scripts -->
  <script>
    // Chat simulado
    function sendMessage() {
      const input = document.getElementById('chatInput');
      const chatBox = document.getElementById('chatBox');
      const text = input.value.trim();
      if (text === '') return;

      const userMsg = document.createElement('div');
      userMsg.classList.add('chat-message');
      userMsg.innerHTML = `<strong>Tú:</strong> ${text}`;
      chatBox.appendChild(userMsg);

      const botMsg = document.createElement('div');
      botMsg.classList.add('chat-message');

      let reply = "No entendí tu pregunta 😅. Prueba con 'eventos', 'horas' o 'insignias'.";
      if (text.toLowerCase().includes("evento")) reply = "Puedes registrarte en eventos desde la sección 'Eventos'.";
      else if (text.toLowerCase().includes("hora")) reply = "Consulta o actualiza tus horas desde 'Perfil' o 'Actualizar horas'.";
      else if (text.toLowerCase().includes("insignia")) reply = "Las insignias se otorgan automáticamente al cumplir tus metas.";
      else if (text.toLowerCase().includes("ayuda")) reply = "Estoy aquí para ayudarte 😊. Escribe tu duda concreta.";

      botMsg.innerHTML = `<strong>Bot:</strong> ${reply}`;
      chatBox.appendChild(botMsg);

      input.value = '';
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    // 🔹 Modo oscuro
    const body = document.body;
    const toggle = document.getElementById('darkModeToggle');

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
