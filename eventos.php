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
  <title>Eventos - UNAB LifeAcademy</title>
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
    .event-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.2s, background 0.3s;
    }
    .event-card:hover {
      transform: scale(1.03);
      background: rgba(255, 255, 255, 0.15);
    } 
    /* Estilo del buscador */
#searchInput::placeholder {
  color: #555; /* gris medio para contraste */
}
#searchInput:focus {
  outline: none;
  box-shadow: 0 0 5px #003366;
}
#searchInput:focus {
  box-shadow: 0 0 8px #0055aa;
  border-radius: 6px;
}

  </style>
</head>

<body class="min-h-screen flex flex-col">
  <!-- 🔹 Header -->
  <header class="p-4 bg-white/10 backdrop-blur-md shadow-md flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <img src="Login_img/Logo_2.png" alt="Logo UNAB" class="w-12 h-12 rounded-full">
      <h1 class="text-2xl font-bold">UNAB LifeAcademy</h1>
    </div>

    <nav class="flex items-center gap-3">
      <a href="inicio.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Perfil</a>
      <a href="eventos.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Eventos</a>
      <a href="noticias.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Noticias</a>
      <a href="calendario.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Calendario</a>
      <a href="mapa.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Mapa</a>
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
    <h2 class="text-3xl font-bold mb-6 text-center">🎓 Eventos Disponibles</h2>

    <!-- 🔍 Buscador -->
    <!-- 🔍 Buscador mejorado -->
<!-- 🔍 Buscador mejorado con icono de lupa -->
<!-- 🔍 Buscador con borde azul, texto negro y lupa -->
<div class="max-w-md mx-auto mb-10">
  <div class="flex items-center bg-white border-2 border-[#003366] rounded-lg p-2 shadow-lg">
    <i class="fas fa-search text-[#003366] text-lg px-2"></i>
    <input 
      id="searchInput" 
      type="text" 
      placeholder="Buscar evento (ej. Ulibro, Ingeniotic, Arte...)" 
      class="w-full bg-white text-black focus:outline-none px-2 placeholder-gray-500 rounded-md"
    >
    <button 
      id="searchBtn" 
      class="bg-white hover:bg-gray-200 text-black font-semibold px-4 py-2 rounded-md transition ml-2 border border-[#003366]"
    >
      Buscar
    </button>
  </div>
  <p id="noResults" class="text-center mt-3 text-blue-100 hidden">
    ⚠️ No se encontraron resultados para tu búsqueda.
  </p>
</div>

  <p id="noResults" class="text-center mt-3 text-blue-100 hidden">
    ⚠️ No se encontraron resultados para tu búsqueda.
  </p>
</div>
    <!-- 🔸 Lista de eventos -->
    <div id="eventList" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Los eventos se insertan dinámicamente con JS -->
    </div>

    <!-- 🔸 Eventos registrados -->
    <!-- 🔹 Lista de eventos registrados con opción de eliminar -->
<section class="mt-12 text-center">
  <h3 class="text-2xl font-bold mb-4">🗓️ Mis Eventos Registrados</h3>
  <ul id="registeredList" class="space-y-3 text-blue-100"></ul>
  <p id="noEventsMsg" class="text-gray-400">Aún no te has registrado en ningún evento.</p>
</section>

  </main>

  <footer class="p-4 text-center text-blue-100 text-sm">
    © 2025 Universidad Autónoma de Bucaramanga — UNAB LifeAcademy
  </footer>

  <!-- 🔹 Script funcional -->
  <script>
    const body = document.body;
    const toggle = document.getElementById('darkModeToggle');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const noResults = document.getElementById('noResults');

    // 🌙 Modo oscuro persistente
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

    // 🔹 Datos de ejemplo de eventos
    const eventos = [
      { nombre: "Ulibro 2025", fecha: "10 Nov 2025", lugar: "Auditorio Carlos Gómez", descripcion: "Feria del libro UNAB", imagen: "Eventos_img/Ulibro.jpg" },
      { nombre: "Ingeniotic 2025", fecha: "22 Oct 2025", lugar: "Bloque N - Sala 301", descripcion: "Congreso de Ingeniería y Tecnología", imagen: "Eventos_img/Ingeniatec.jpeg" },
      { nombre: "Semana del Arte", fecha: "3 Dic 2025", lugar: "Centro de Arte UNAB", descripcion: "Exposición de proyectos artísticos", imagen: "Eventos_img/Semana_de_arte.jpeg" },
      { nombre: "Foro de Emprendimiento", fecha: "15 Sep 2025", lugar: "Sala de Innovación", descripcion: "Charlas y networking para emprender", imagen: "Eventos_img/Emprendimiento.png" }
    ];

    const eventList = document.getElementById("eventList");
    const registeredList = document.getElementById("registeredList");
    const noEventsMsg = document.getElementById("noEventsMsg");

    // Mostrar eventos
    function mostrarEventos(lista = eventos) {
      eventList.innerHTML = "";
      if (lista.length === 0) {
        noResults.classList.remove("hidden");
        return;
      }
      noResults.classList.add("hidden");
      lista.forEach((evento, i) => {
        const card = document.createElement("div");
        card.className = "event-card p-4 text-center";
        card.innerHTML = `
          <img src="${evento.imagen}" alt="${evento.nombre}" class="w-full h-40 object-cover rounded-md mb-3">
          <h4 class="text-xl font-bold mb-1">${evento.nombre}</h4>
          <p class="text-sm text-blue-100 mb-1"><i class="fas fa-calendar"></i> ${evento.fecha}</p>
          <p class="text-sm text-blue-100 mb-3"><i class="fas fa-map-marker-alt"></i> ${evento.lugar}</p>
          <p class="text-sm mb-4">${evento.descripcion}</p>
          <button class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg font-semibold" onclick="registrarEvento(${i})">
            <i class="fas fa-plus-circle"></i> Registrar
          </button>
        `;
        eventList.appendChild(card);
      });
    }

    // Registrar evento (local)
    function registrarEvento(i) {
      const evento = eventos[i];
      const registrados = JSON.parse(localStorage.getItem('eventosRegistrados')) || [];
      if (registrados.some(e => e.nombre === evento.nombre)) {
        alert("⚠️ Ya estás registrado en este evento.");
        return;
      }
      registrados.push(evento);
      localStorage.setItem('eventosRegistrados', JSON.stringify(registrados));
      alert(`✅ Te has registrado en "${evento.nombre}"`);
      actualizarRegistrados();
    }

    // 🔹 Mostrar eventos registrados
function actualizarRegistrados() {
  const registrados = JSON.parse(localStorage.getItem('eventosRegistrados')) || [];
  registeredList.innerHTML = "";

  if (registrados.length === 0) {
    noEventsMsg.style.display = "block";
  } else {
    noEventsMsg.style.display = "none";

    registrados.forEach((e, index) => {
      const li = document.createElement("li");
      li.className = "flex items-center justify-between bg-white/10 p-3 rounded-lg";
      li.innerHTML = `
        <span>${e.nombre} – ${e.fecha}</span>
        <button onclick="eliminarEvento(${index})" 
          class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md transition-all">
          <i class="fas fa-trash-alt"></i> Eliminar
        </button>
      `;
      registeredList.appendChild(li);
    });
  }
}

// 🔹 Eliminar evento registrado
function eliminarEvento(index) {
  const registrados = JSON.parse(localStorage.getItem('eventosRegistrados')) || [];
  const evento = registrados[index];

  if (confirm(`¿Seguro que deseas eliminar "${evento.nombre}"?`)) {
    registrados.splice(index, 1);
    localStorage.setItem('eventosRegistrados', JSON.stringify(registrados));
    actualizarRegistrados();
    alert(`🗑️ Evento "${evento.nombre}" eliminado correctamente.`);
  }
}


    // 🔍 Búsqueda funcional (nombre, lugar, descripción)
    function buscarEventos() {
      const q = searchInput.value.trim().toLowerCase();
      if (q === "") {
        mostrarEventos();
        return;
      }
      const filtrados = eventos.filter(e =>
        e.nombre.toLowerCase().includes(q) ||
        e.lugar.toLowerCase().includes(q) ||
        e.descripcion.toLowerCase().includes(q)
      );
      mostrarEventos(filtrados);
    }

    // Buscar con botón o Enter
    searchBtn.addEventListener("click", buscarEventos);
    searchInput.addEventListener("keyup", e => {
      if (e.key === "Enter") buscarEventos();
      if (searchInput.value === "") mostrarEventos();
    });

    mostrarEventos();
    actualizarRegistrados();
  </script>
</body>
</html>
