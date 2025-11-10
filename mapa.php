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
  <title>📍 Mapa Interactivo - UNAB LifeAcademy</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

    #map {
      height: 500px;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      margin-bottom: 2rem;
    }

    /* 🔹 Sección original de Áreas destacadas */
    .info-box {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      margin-bottom: 20px;
      color: #003366;
    }

    .info-box h3 {
      border-bottom: 2px solid #0055a4;
      padding-bottom: 8px;
      margin-bottom: 15px;
    }

    .locations-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 15px;
    }

    .location-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .location-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .card-image {
      width: 100%;
      height: 160px;
      object-fit: cover;
      display: block;
    }

    .card-title {
      padding: 12px;
      font-size: 14px;
      font-weight: bold;
      color: #003366;
      text-align: center;
      background: #f8fafc;
    }

    footer {
      text-align: center;
      padding: 15px;
      color: #ccc;
      font-size: 0.9em;
    }

    /* Modal */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.9);
      z-index: 2000;
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      max-width: 90%;
      max-height: 90%;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .modal-image {
      width: 100%;
      height: auto;
      display: block;
    }
    .close-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      color: white;
      font-size: 32px;
      cursor: pointer;
      z-index: 2001;
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
      <a href="eventos.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Eventos</a>
      <a href="noticias.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Noticias</a>
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
    <h2 class="text-3xl font-bold mb-6 text-center">🗺️ Mapa Interactivo del Campus UNAB</h2>
    <p class="text-center text-blue-100 mb-8">Explora los puntos principales de la Universidad Autónoma de Bucaramanga.</p>

    <!-- 🔸 Mapa -->
    <div id="map"></div>

    <!-- 🔸 Sección original: Áreas destacadas -->
    <div class="info-box">
      <h3>🖼️ Áreas Destacadas en el Campus El Cabeceras</h3>
      <div class="locations-grid">
        <div class="location-card" onclick="openImageModal('Mapa_img/Sede_Principal.png')">
          <img src="Mapa_img/Sede_Principal.png" alt="Sede Principal" class="card-image">
          <div class="card-title">Sede Principal UNAB</div>
        </div>

        <div class="location-card" onclick="openImageModal('Mapa_img/biblioteca.jpg')">
          <img src="Mapa_img/biblioteca.jpg" alt="Biblioteca Central" class="card-image">
          <div class="card-title">Biblioteca Central</div>
        </div>

        <div class="location-card" onclick="openImageModal('Mapa_img/Edificio_L.jpg')">
          <img src="Mapa_img/Edificio_L.jpg" alt="Edificio L de Ingeniería" class="card-image">
          <div class="card-title">Edificio L de Ingeniería</div>
        </div>

        <div class="location-card" onclick="openImageModal('Mapa_img/Parqueadero_principal_Jardin.png')">
          <img src="Mapa_img/Parqueadero_principal_Jardin.png" alt="Parqueadero Principal" class="card-image">
          <div class="card-title">Parqueadero Principal</div>
        </div>

        <div class="location-card" onclick="openImageModal('Mapa_img/Plazoleta_Banu.jpg')">
          <img src="Mapa_img/Plazoleta_Banu.jpg" alt="Plazoleta Banu" class="card-image">
          <div class="card-title">Plazoleta Banu</div>
        </div>

        <div class="location-card" onclick="openImageModal('Mapa_img/Auditorio_Mayor.jpg')">
          <img src="Mapa_img/Auditorio_Mayor.jpg" alt="Auditorio Mayor" class="card-image">
          <div class="card-title">Auditorio Mayor</div>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 Universidad Autónoma de Bucaramanga — Campus El Cabeceras (Sede Principal)</p>
  </footer>

  <!-- Modal -->
  <div id="imageModal" class="modal-overlay">
    <span class="close-btn" onclick="closeImageModal()">&times;</span>
    <div class="modal-content">
      <img id="modalImg" class="modal-image" src="" alt="">
    </div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    const campusCenter = [7.1168802, -73.1052063];
    const map = L.map('map').setView(campusCenter, 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap | UNAB'
    }).addTo(map);

    const locations = [
      { name: "Sede Principal UNAB", pos: campusCenter, desc: "Calle 42 #48-11, Bucaramanga." },
      { name: "Biblioteca Central", pos: [7.117280, -73.104800], desc: "Centro académico con colecciones y estudio." },
      { name: "Bloque L - Ingenierías", pos: [7.117000, -73.105500], desc: "Laboratorios y clases de ingeniería." },
      { name: "Auditorio Mayor", pos: [7.116900, -73.105050], desc: "Conferencias y eventos institucionales." },
      { name: "Plazoleta Banu", pos: [7.116650, -73.105300], desc: "Zona social y de descanso del campus." }
    ];

    locations.forEach(loc => {
      L.marker(loc.pos).addTo(map)
        .bindPopup(`<b>${loc.name}</b><br>${loc.desc}`);
    });
  </script>

  <script>
    // Modal
    function openImageModal(src) {
      const modal = document.getElementById('imageModal');
      const img = document.getElementById('modalImg');
      img.src = src;
      modal.style.display = 'flex';
    }
    function closeImageModal() {
      document.getElementById('imageModal').style.display = 'none';
    }

    // Modo oscuro
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
