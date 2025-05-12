let saludoMostrado = false;

function toggleChat() {
    const container = document.getElementById('chat-container');
    const log = document.getElementById('chat-log');
    const wasHidden = container.style.display === 'none';
    container.style.display = wasHidden ? 'flex' : 'none';
    if (wasHidden && !saludoMostrado) {
        log.innerHTML = `
        <div style="text-align:center; padding:10px; color:#ff6fa1;">
            <i class="fas fa-robot fa-2x"></i>
            <p><strong>FrasieBot:</strong> ¡Hola! 🍓 ¿En qué puedo ayudarte hoy?<br>
            Puedes pedirme una reservación o preguntarme por nuestros postres.</p>
        </div>`;
        saludoMostrado = true;
    }
}

async function sendMessage(text = null) {
    const input = document.getElementById('user-message');
    const log = document.getElementById('chat-log');
    const userText = text || input.value.trim();
    if (!userText) return;

    log.innerHTML += `<div><strong>Tú:</strong> ${userText}</div>`;
    input.value = '';

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const loader = document.createElement('div');
    loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> FrasieBot está escribiendo...';
    loader.style.color = '#999';
    loader.style.margin = '10px';
    log.appendChild(loader);
    log.scrollTop = log.scrollHeight;

    try {
        const response = await fetch('/api/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ message: userText })
        });

        if (!response.ok) throw new Error('Error al contactar con el servidor');

        const data = await response.json();
        loader.remove();

        const reply = data.choices?.[0]?.message?.content || 'Lo siento, hubo un error en la respuesta.';
        log.innerHTML += `<div><strong>FrasieBot:</strong> ${reply}</div>`;
        log.scrollTop = log.scrollHeight;

        mostrarPedidos();
    } catch (error) {
        loader.remove();
        log.innerHTML += `<div><strong>FrasieBot:</strong> ⚠️ No se pudo contactar al servidor.</div>`;
    }
}

function quickReply(text) {
    sendMessage(text);
}

function confirmarReserva(nombreProducto) {
    Swal.fire({
        title: '¿Cuál es tu nombre?',
        input: 'text',
        inputLabel: 'Nombre del cliente',
        inputPlaceholder: 'Escribe tu nombre',
        showCancelButton: true,
        confirmButtonText: 'Siguiente',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Por favor, ingresa tu nombre';
            }
        }
    }).then(nombreResult => {
        if (nombreResult.isConfirmed) {
            const nombreCliente = nombreResult.value;
            Swal.fire({
                title: '¿Cuántos deseas pedir?',
                input: 'number',
                inputLabel: `Cantidad para "${nombreProducto}"`,
                inputAttributes: {
                    min: 1,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: 'Pedir',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value || value <= 0) {
                        return 'Ingresa una cantidad válida';
                    }
                }
            }).then(cantidadResult => {
                if (cantidadResult.isConfirmed) {
                    sendMessage(`Pedido: ${nombreProducto}\nCantidad: ${cantidadResult.value}\nCliente: ${nombreCliente}`);
                    Swal.fire('¡Pedido enviado!', 'FrasieBot procesará tu solicitud.', 'success');
                }
            });
        }
    });
}

async function mostrarPedidos() {
    const section = document.getElementById('orders-section');
    const tbody = document.getElementById('orders-body');
    section.style.display = 'block';
    try {
        const response = await fetch('/reservaciones');
        const data = await response.json();
        tbody.innerHTML = '';
        data.forEach(pedido => {
            tbody.innerHTML += `<tr><td>${pedido.producto}</td><td>${pedido.fecha_reservacion}</td><td>${pedido.comentario || '-'}</td></tr>`;
        });
    } catch {
        tbody.innerHTML = '<tr><td colspan="3">⚠️ No se pudieron cargar los pedidos</td></tr>';
    }
}
