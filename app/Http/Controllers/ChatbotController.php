<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Reservacion;
use Illuminate\Support\Carbon;

class ChatbotController extends Controller
{
    public function responder(Request $request)
    {
        $mensaje = $request->input('message');
        Log::info('✉️ Mensaje recibido:', ['mensaje' => $mensaje]);

        $apiKey = env('OPENAI_API_KEY');
        $respuestaTexto = '';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente amable de La Frasie. Si el usuario quiere hacer una reservación, responde siempre en este formato exacto:
Producto: [nombre del postre]
Cantidad: [número]
Cliente: [nombre completo]
Fecha: [YYYY-MM-DD HH:mm]
Comentario: [comentario adicional]'
                    ],
                    ['role' => 'user', 'content' => $mensaje],
                ]
            ]);

            if ($response->successful()) {
                $respuestaTexto = $response->json()['choices'][0]['message']['content'] ?? 'No se pudo interpretar la respuesta.';
                Log::info('🤖 Respuesta OpenAI:', ['respuesta' => $respuestaTexto]);
            } else {
                Log::error('❌ Error al contactar OpenAI', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                $respuestaTexto = "Producto: Fresa con crema\nCantidad: 1\nCliente: Invitado\nFecha: 2025-05-02 17:00\nComentario: Reservación simulada por falta de saldo.";
            }
        } catch (\Exception $e) {
            Log::error('❌ Error conectando a OpenAI', ['error' => $e->getMessage()]);
            $respuestaTexto = "Producto: Nieve\nCantidad: 1\nCliente: Invitado\nFecha: 2025-05-02 18:00\nComentario: Respuesta simulada por error técnico.";
        }

        // Extraer datos del texto y guardar si es válido
        if (str_contains($respuestaTexto, 'Producto:') && str_contains($respuestaTexto, 'Fecha:')) {
            try {
                preg_match('/Producto:\s*(.+)/i', $respuestaTexto, $productoMatch);
                preg_match('/Cantidad:\s*(\d+)/i', $respuestaTexto, $cantidadMatch);
                preg_match('/Cliente:\s*(.+)/i', $respuestaTexto, $clienteMatch);
                preg_match('/Fecha:\s*(.+)/i', $respuestaTexto, $fechaMatch);
                preg_match('/Comentario:\s*(.+)/i', $respuestaTexto, $comentarioMatch);

                $producto = trim($productoMatch[1] ?? 'Postre');
                $cantidad = (int) trim($cantidadMatch[1] ?? 1);
                $cliente = trim($clienteMatch[1] ?? 'Invitado');
                $fechaTexto = trim($fechaMatch[1] ?? now());
                $comentario = trim($comentarioMatch[1] ?? null);

                try {
                    $fecha = Carbon::parse($fechaTexto);
                } catch (\Exception $e) {
                    Log::error('❌ No se pudo convertir la fecha', ['texto' => $fechaTexto]);
                    $respuestaTexto .= "\n⚠️ La fecha debe tener el formato `YYYY-MM-DD HH:mm` para registrar tu reservación.";
                    return response()->json([
                        'choices' => [
                            ['message' => ['content' => $respuestaTexto]]
                        ]
                    ]);
                }

                Reservacion::create([
                    'nombre' => $cliente,
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'fecha_reservacion' => $fecha,
                    'comentario' => $comentario,
                ]);

                Log::info('✅ Reservación guardada', compact('producto', 'cantidad', 'cliente', 'fecha', 'comentario'));
                $respuestaTexto .= "\n✅ ¡Tu pedido fue registrado exitosamente!";
            } catch (\Exception $e) {
                Log::error('❌ Error al guardar reservación', ['error' => $e->getMessage()]);
                $respuestaTexto .= "\n⚠️ Hubo un error al guardar tu pedido.";
            }
        }

        return response()->json([
            'choices' => [
                ['message' => ['content' => $respuestaTexto]]
            ]
        ]);
    }
}
