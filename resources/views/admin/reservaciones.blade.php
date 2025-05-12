<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Pedidos | La Frasie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff6f8;
        }
        header {
            background-color: #ff6fa1;
            padding: 1rem;
            color: white;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        header h1 {
            margin: 0;
            font-size: 2rem;
        }
        .container {
            padding: 2rem;
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: #ff4081;
            text-align: center;
            margin-bottom: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #f3c2d6;
        }
        th {
            background-color: #ffb6c1;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fff0f5;
        }
        tr:hover {
            background-color: #ffe6ef;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff6fa1;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #ff4e89;
        }
    </style>
</head>
<body>

<header>
    <h1>📊 Dashboard de Pedidos - La Frasie</h1>
</header>

<div class="container">
    <h2>Reservaciones Recibidas</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Nombre del Cliente</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservaciones as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->producto }}</td>
                    <td>{{ $r->nombre }}</td>
                    <td>{{ $r->cantidad }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->fecha_reservacion)->format('d/m/Y H:i') }}</td>
                    <td>{{ $r->comentario ?: 'Sin comentario' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay reservaciones registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align:center;">
        <a href="/" class="btn">⬅️ Volver a inicio</a>
    </div>
</div>

</body>
</html>
