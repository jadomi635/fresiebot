<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Frasie 🍓</title>

    <!-- Estilos externos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

    @php
        $productos = [
            ['nombre' => 'Fresa con crema', 'imagen' => 'https://images.pexels.com/photos/12372481/pexels-photo-12372481.jpeg'],
            ['nombre' => 'Fresa con chocolate', 'imagen' => 'https://images.pexels.com/photos/8531694/pexels-photo-8531694.jpeg'],
            ['nombre' => 'Malteada de fresa', 'imagen' => 'https://images.pexels.com/photos/8380252/pexels-photo-8380252.jpeg'],
            ['nombre' => 'Nieve de fresa', 'imagen' => 'https://images.pexels.com/photos/1132558/pexels-photo-1132558.jpeg'],
            ['nombre' => 'Galleta con fresas', 'imagen' => 'https://images.pexels.com/photos/7664400/pexels-photo-7664400.jpeg'],
            ['nombre' => 'Cheesecake de fresa', 'imagen' => 'https://images.pexels.com/photos/18286678/pexels-photo-18286678.jpeg'],
            ['nombre' => 'Cupcake de fresa', 'imagen' => 'https://images.pexels.com/photos/18286678/pexels-photo-18286678.jpeg'],
            ['nombre' => 'Helado de fresa', 'imagen' => 'https://images.pexels.com/photos/18286678/pexels-photo-18286678.jpeg'],
        ];
    @endphp

    <header>
        <h1>La Frasie</h1>
        <p>Tu lugar para las fresas más dulces y con más amor</p>
    </header>

    <main>
        <div class="section-buttons">
            <button onclick="mostrarPedidos()">📦 Ver pedidos</button>
            <button onclick="alert('Historial de reservaciones pronto disponible')">📋 Historial</button>
        </div>

        <h2 style="text-align:center; color:#ff6fa1;">Especialidad de la casa</h2>
        <div class="menu-section">
            @foreach ($productos as $producto)
                <div class="menu-item">
                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" style="width:100px;">
                    <p>{{ $producto['nombre'] }}</p>
                    <button onclick="confirmarReserva('{{ $producto['nombre'] }}')">Pedir</button>
                </div>
            @endforeach
        </div>

        <div id="orders-section">
            <h3>📋 Pedidos recientes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Fecha</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody id="orders-body">
                    <!-- Se insertan con JS -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Chat flotante -->
    <button class="chatbot-btn" onclick="toggleChat()">
        <i class="fas fa-robot"></i> Soporte IA
    </button>

    <div id="chat-container">
        <div id="chat-log"></div>
        <div id="user-input">
            <input type="text" id="user-message" placeholder="Escribe tu mensaje...">
            <button id="send-btn" onclick="sendMessage()">Enviar</button>
        </div>
    </div>

    <div class="footer">© 2025 La Frasie. Todos los derechos reservados.</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
