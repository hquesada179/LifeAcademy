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
  <title>Eventos Registrados - UNAB LifeAcademy</title>
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
    .event-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 1.5rem;
      transition: transform 0.2s, background 0.3s;
    }
    .event-card:hover {
      transform: translateY(-4px);
      background: rgba(255, 255, 255, 0.2);
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
      <a href="calendario.php" class="px-3 py-2 rounded-md bg-white/30 font-semibold">Calendario</a>
      <a href="mapa.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Mapa</a>
      <a href="ayuda.php" class="px-3 py-2 rounded-md bg-white/20 hover:bg-white/30 transition">Ayuda</a>
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
  <main class="flex-1 p-8 text-center">
    <h2 class="text-3xl font-bold mb-4">📋 Mis Actividades Registradas</h2>
    <p class="text-blue-100 mb-8">Aquí podrás ver todos los eventos en los que te has inscrito dentro de LifeAcademy.</p>

    <!-- 🔸 Contenedor de eventos -->
    <div id="eventContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

    <p id="noEventsMsg" class="text-gray-300 text-lg mt-8">Aún no te has registrado en ningún evento.</p>
  </main>

   <button id="downloadPDF" class="mt-8 bg-green-600 hover:bg-green-700 px-6 py-3 rounded-lg font-semibold">
    <i class="fas fa-file-download"></i> Descargar certificado en PDF
  </button>
</main>
  <footer class="p-4 text-center text-blue-100 text-sm">
    © 2025 Universidad Autónoma de Bucaramanga — UNAB LifeAcademy
  </footer>

  <!-- 🔹 Script funcional -->
  <script>
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

    // 🔹 Cargar eventos registrados desde LocalStorage
    const registrados = JSON.parse(localStorage.getItem('eventosRegistrados')) || [];
    const container = document.getElementById('eventContainer');
    const noMsg = document.getElementById('noEventsMsg');

    if (registrados.length === 0) {
      noMsg.style.display = "block";
    } else {
      noMsg.style.display = "none";
      registrados.forEach(ev => {
        const card = document.createElement("div");
        card.className = "event-card";
        card.innerHTML = `
          <h3 class="text-xl font-bold mb-2">${ev.nombre}</h3>
          <p class="text-blue-100 mb-1"><i class="fas fa-calendar"></i> ${ev.fecha}</p>
          <p class="text-blue-100 mb-1"><i class="fas fa-map-marker-alt"></i> ${ev.lugar || "Lugar por confirmar"}</p>
          <p class="mb-2">${ev.descripcion || "Evento institucional de la UNAB."}</p>
          <button onclick="eliminarEvento('${ev.nombre}')" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg mt-2">
            <i class="fas fa-trash-alt"></i> Eliminar
          </button>
        `;
        container.appendChild(card);
      });
    }

    // 🔹 Eliminar evento
    function eliminarEvento(nombre) {
      const confirmDelete = confirm(`¿Deseas eliminar "${nombre}" de tus actividades?`);
      if (!confirmDelete) return;

      let registrados = JSON.parse(localStorage.getItem('eventosRegistrados')) || [];
      registrados = registrados.filter(ev => ev.nombre !== nombre);
      localStorage.setItem('eventosRegistrados', JSON.stringify(registrados));
      alert(`❌ "${nombre}" eliminado de tus registros.`);
      location.reload();
    }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
  // 📄 Generar PDF de eventos registrados
  document.getElementById("downloadPDF").addEventListener("click", () => {
    const registrados = JSON.parse(localStorage.getItem("eventosRegistrados")) || [];
    if (registrados.length === 0) {
      alert("⚠️ No tienes eventos registrados para generar el certificado.");
      return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Encabezado
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.text("Universidad Autónoma de Bucaramanga", 20, 20);
    doc.setFontSize(14);
    doc.text("Certificado de Participación - UNAB LifeAcademy", 20, 30);

    // Línea
    doc.setDrawColor(0, 0, 128);
    doc.line(20, 33, 190, 33);

    // Nombre del usuario (PHP)
    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.text("Estudiante:", 20, 45);
    doc.text("<?php echo htmlspecialchars($nombre); ?>", 60, 45);

    // Fecha actual
    const hoy = new Date().toLocaleDateString("es-CO", {
      year: "numeric",
      month: "long",
      day: "numeric"
    });
    doc.text("Fecha de emisión:", 20, 55);
    doc.text(hoy, 60, 55);

    // Título de lista
    doc.setFont("helvetica", "bold");
    doc.text("Eventos registrados:", 20, 70);
    doc.setFont("helvetica", "normal");

    // Listado de eventos
    let y = 80;
    registrados.forEach((ev, i) => {
      if (y > 270) { // salto de página
        doc.addPage();
        y = 20;
      }
      doc.text(`${i + 1}. ${ev.nombre} – ${ev.fecha}`, 25, y);
      y += 8;
      if (ev.lugar) doc.text(`   Lugar: ${ev.lugar}`, 25, y);
      y += 6;
      if (ev.descripcion) doc.text(`   ${ev.descripcion}`, 25, y);
      y += 10;
    });

    // Pie de página
    doc.setFontSize(10);
    doc.text("© 2025 Universidad Autónoma de Bucaramanga - UNAB LifeAcademy", 20, 285);

    // Descargar
    doc.save("Certificado_UNAB_LifeAcademy.pdf");
  });
</script>
</body>
</html>

