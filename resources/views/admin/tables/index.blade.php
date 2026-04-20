@extends('layouts.admin')

@section('title', 'Manajemen Meja - Admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Meja</h2>
            <p class="text-sm text-gray-500">Kelola nomor meja dan cetak QR Code untuk pelanggan.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm mb-8 border border-gray-100">
        <h3 class="font-bold text-gray-700 mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Meja Baru
        </h3>
        <form action="{{ route('admin.tables.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <div class="flex-1">
                <input type="text" name="number" placeholder="Nomor Meja (Contoh: 01 atau A1)" required 
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
            </div>
            <button type="submit" class="bg-black text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                Simpan Meja
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($tables as $table)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:border-orange-300 transition-all">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="text-4xl font-black text-gray-800">#{{ $table->number }}</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Status Meja</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $table->status == 'available' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $table->status }}
                    </span>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-2xl border-2 border-dashed border-gray-200 flex justify-center items-center mb-6 group-hover:bg-white transition">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        {!! QrCode::size(150)->generate(url('/scan/' . $table->number . '/' . $table->token)) !!}
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg mb-6 overflow-hidden">
                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">Link Meja:</p>
                    <code class="text-[10px] text-orange-600 break-all block leading-tight font-mono">
                        {{ url('/scan/'.$table->number.'/'.$table->token) }}
                    </code>
                </div>

                <div class="flex gap-2">
                    <button onclick="printTable('{{ $table->number }}', '{{ url('/scan/'.$table->number.'/'.$table->token) }}')" 
                        class="flex-1 bg-black text-white text-xs font-bold py-3 rounded-xl hover:bg-gray-800 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak QR
                    </button>
                    <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        function printTable(number, url) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Cetak QR Meja ${number}</title>
                    <style>
                        body { font-family: sans-serif; text-align: center; padding: 40px; }
                        .card { border: 2px solid #000; padding: 30px; display: inline-block; border-radius: 20px; }
                        h1 { font-size: 48px; margin: 0; }
                        p { font-size: 18px; color: #666; margin-bottom: 20px; }
                        .qr-placeholder { margin: 20px 0; }
                    </style>
                </head>
                <body onload="window.print()">
                    <div class="card">
                        <p>Silakan Scan untuk Memesan</p>
                        <h1>MEJA ${number}</h1>
                        <div class="qr-placeholder">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(url)}" />
                        </div>
                        <p>Selamat Menikmati!</p>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
@endsection