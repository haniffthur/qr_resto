<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $order->order_code }}</title>
    <style>
        /* CSS Khusus Printer Thermal (Kertas Lebar 58mm/80mm) */
        @page { margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            margin: 0; 
            padding: 10px;
            color: #000;
            background: #fff;
            font-size: 12px;
            width: 58mm; /* Sesuaikan dengan ukuran printer kasir */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; margin-top: 5px; padding-top: 5px; }
        .border-bottom { border-bottom: 1px dashed #000; margin-bottom: 5px; padding-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-2 { margin-top: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        
        /* Hilangkan tombol print saat dicetak beneran */
        @media print {
            .no-print { display: none !important; }
        }

        /* Styling tombol untuk layar komputer */
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #000;
            color: #fff;
            text-align: center;
            text-decoration: none;
            margin-bottom: 5px;
            border: none;
            cursor: pointer;
            font-family: sans-serif;
            border-radius: 4px;
        }
        .btn-back { background: #666; }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center mb-2">
        <h2 style="margin:0; font-size: 16px;">RESTO CEMARA</h2>
        <p style="margin:0;">Jl. Universitas Paramadina</p>
        <p style="margin:0;">Telp: 0812-XXXX-XXXX</p>
    </div>

    <div class="border-top border-bottom">
        <table>
            <tr>
                <td>Tgl</td>
                <td class="text-right">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Meja</td>
                <td class="text-right font-bold">{{ $order->table->number }}</td>
            </tr>
            <tr>
                <td>Kode</td>
                <td class="text-right">{{ $order->order_code }}</td>
            </tr>
        </table>
    </div>

    <table class="mb-2">
        @foreach($order->items as $item)
        <tr>
            <td colspan="3">{{ $item->menu->name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }}x</td>
            <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="border-top border-bottom text-right font-bold">
        <table>
            <tr>
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="text-center mt-2 mb-2">
        <p style="margin:0;">STATUS: LUNAS</p>
        <p style="margin:0; margin-top:5px;">Terima Kasih</p>
        <p style="margin:0;">Selamat Menikmati</p>
    </div>

    <div class="no-print mt-2">
        <button onclick="window.print()" class="btn">🖨️ Cetak Ulang</button>
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-back">&larr; Kembali ke Kasir</a>
    </div>

</body>
</html>