@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Kategori 📂</h2>
    <button onclick="toggleModal('add')" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-orange-200">
        + Kategori Baru
    </button>
</div>

@if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 border border-emerald-100 font-bold text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest">
            <tr>
                <th class="px-6 py-4">Icon</th>
                <th class="px-6 py-4">Nama Kategori</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $cat)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-6 py-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center p-2 border border-orange-100">
                        @if($cat->icon)
                            <img src="{{ asset('storage/'.$cat->icon) }}" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid fa-utensils text-orange-200"></i>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 font-bold text-gray-700 uppercase tracking-tight">{{ $cat->name }}</td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button onclick="editCategory({{ json_encode($cat) }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori? Semua menu di kategori ini mungkin akan bermasalah.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Belum ada kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modal-cat" class="hidden fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl scale-95 transition-transform duration-300" id="modal-content">
        <h3 id="modal-title" class="text-xl font-black text-gray-800 uppercase mb-6">Tambah Kategori</h3>
        
        <form id="form-cat" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="method-container"></div>
            
            <div class="space-y-5">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Kategori</label>
                    <input type="text" name="name" id="in-name" required placeholder="Contoh: Minuman Segar" 
                           class="w-full border border-gray-200 p-4 rounded-2xl mt-1 font-bold text-gray-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
                </div>
                
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Icon Gambar (PNG Berwarna)</label>
                    <input type="file" name="icon" class="w-full border border-gray-200 p-3 rounded-2xl mt-1 text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-orange-50 file:text-orange-500 transition">
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="toggleModal()" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold uppercase text-xs tracking-widest">Batal</button>
                <button type="submit" class="flex-1 bg-orange-500 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-orange-200">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-cat');
    const mContent = document.getElementById('modal-content');

    function toggleModal(type = 'add') {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => mContent.classList.replace('scale-95', 'scale-100'), 10);
            if(type === 'add') {
                document.getElementById('modal-title').innerText = 'Tambah Kategori';
                document.getElementById('form-cat').action = "{{ route('admin.categories.store') }}";
                document.getElementById('method-container').innerHTML = '';
                document.getElementById('in-name').value = '';
            }
        } else {
            mContent.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }
    }

    function editCategory(cat) {
        toggleModal('edit');
        document.getElementById('modal-title').innerText = 'Edit Kategori';
        document.getElementById('form-cat').action = `/admin/categories/${cat.id}`;
        document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('in-name').value = cat.name;
    }
</script>
@endsection