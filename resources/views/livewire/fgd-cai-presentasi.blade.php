<div class="min-h-screen bg-gray-100 flex flex-col font-sans" x-data="{ fullscreen: false }">
    <!-- Header Controls (Hidden in Fullscreen if needed, or kept minimal) -->
    <header class="bg-white border-b py-3 px-6 shadow-sm flex flex-wrap items-center justify-between gap-4" :class="{ 'hidden': fullscreen }">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-cai.webp') }}" alt="Logo CAI" class="h-8 w-auto object-contain">
            <h1 class="text-xl font-bold text-blue-900">Mode Presentasi FGD</h1>
        </div>
        
        <div class="flex flex-wrap items-center gap-4 flex-grow justify-center">
            @if(count($sessions) > 1)
                <select wire:model.live="activeSessionId" class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">-- Pilih Sesi FGD --</option>
                    @foreach($sessions as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            @endif

            @if($activeSessionId || count($sessions) === 1)
                <select wire:model.live="activeThemeId" class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold text-blue-900 min-w-[200px]">
                    <option value="">-- Pilih Tema --</option>
                    @foreach($themes as $t)
                        <option value="{{ $t->id }}">{{ $t->title }}</option>
                    @endforeach
                </select>

                <select wire:model.live="activeGroupId" class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold text-blue-900 min-w-[200px]">
                    <option value="">-- Pilih Grup --</option>
                    @foreach($groups as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('fgd-cai') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 underline">Kembali ke Form</a>
            <button @click="
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                    fullscreen = true;
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        fullscreen = false;
                    }
                }
            " class="px-4 py-2 bg-blue-800 text-white text-sm font-bold rounded hover:bg-blue-900 transition flex items-center gap-2 shadow-sm">
                <span x-text="fullscreen ? 'Keluar Fullscreen' : 'Layar Penuh'"></span>
            </button>
        </div>
    </header>

    <main class="flex-grow p-4 md:p-6 flex flex-col w-full max-w-[1920px] mx-auto">
        @if($activeGroupId && $activeThemeId)
            
            <div class="mb-6 flex items-center justify-center gap-4 text-white py-4 px-6 rounded-xl shadow-lg" style="background-color: #1e3a8a; border: 1px solid #1e40af;" x-show="fullscreen" x-transition x-cloak>
                <img src="{{ asset('images/logo-cai.webp') }}" alt="Logo CAI" class="h-10 w-auto object-contain bg-white rounded p-1">
                <h2 class="text-3xl md:text-4xl font-black tracking-widest uppercase">
                    {{ $groups->firstWhere('id', $activeGroupId)?->name }} <span class="text-blue-300 mx-2">—</span> {{ $themes->firstWhere('id', $activeThemeId)?->title }}
                </h2>
            </div>
            
            @if($note)
                <!-- 3 Columns Layout for Projector -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 flex-grow">
                    
                    <!-- Problem -->
                    <div class="bg-white rounded-xl shadow border border-red-200 overflow-hidden flex flex-col">
                        <div class="bg-red-50 py-3 px-5 border-b border-red-100 flex items-center gap-2">
                            <h2 class="text-lg font-extrabold text-red-800 uppercase tracking-wider">Problem</h2>
                        </div>
                        <div class="p-6 prose prose-lg prose-red max-w-none flex-grow overflow-y-auto" style="font-size: 1.15rem; line-height: 1.6;">
                            {!! $note->problem ?? '<p class="text-gray-400 italic">Belum ada catatan problem...</p>' !!}
                        </div>
                    </div>

                    <!-- Penyebab -->
                    <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden flex flex-col">
                        <div class="bg-orange-50 py-3 px-5 border-b border-orange-100 flex items-center gap-2">
                            <h2 class="text-lg font-extrabold text-orange-800 uppercase tracking-wider">Penyebab</h2>
                        </div>
                        <div class="p-6 prose prose-lg prose-orange max-w-none flex-grow overflow-y-auto" style="font-size: 1.15rem; line-height: 1.6;">
                            {!! $note->penyebab ?? '<p class="text-gray-400 italic">Belum ada catatan penyebab...</p>' !!}
                        </div>
                    </div>

                    <!-- Solusi -->
                    <div class="bg-white rounded-xl shadow border border-green-200 overflow-hidden flex flex-col">
                        <div class="bg-green-50 py-3 px-5 border-b border-green-100 flex items-center gap-2">
                            <h2 class="text-lg font-extrabold text-green-800 uppercase tracking-wider">Solusi</h2>
                        </div>
                        <div class="p-6 prose prose-lg prose-green max-w-none flex-grow overflow-y-auto" style="font-size: 1.15rem; line-height: 1.6;">
                            {!! $note->solusi ?? '<p class="text-gray-400 italic">Belum ada usulan solusi...</p>' !!}
                        </div>
                    </div>

                </div>

                <!-- Action Plan (Optional, collapsable or below) -->
                @if($note->ap_deskripsi || $note->ap_nama_kegiatan || $note->ap_peserta || $note->ap_waktu || $note->ap_dana)
                    <div class="bg-white rounded-xl shadow border border-blue-200 overflow-hidden mb-8">
                        <div class="bg-blue-50 py-3 px-5 border-b border-blue-100">
                            <h2 class="text-lg font-extrabold text-blue-800 uppercase tracking-wider">Action Plan</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-max">
                                <thead>
                                    <tr class="bg-blue-100">
                                        <th class="py-3 px-4 border border-blue-200 font-bold text-blue-900 uppercase text-sm">Deskripsi</th>
                                        <th class="py-3 px-4 border border-blue-200 font-bold text-blue-900 uppercase text-sm">Nama Kegiatan</th>
                                        <th class="py-3 px-4 border border-blue-200 font-bold text-blue-900 uppercase text-sm">Peserta</th>
                                        <th class="py-3 px-4 border border-blue-200 font-bold text-blue-900 uppercase text-sm">Waktu</th>
                                        <th class="py-3 px-4 border border-blue-200 font-bold text-blue-900 uppercase text-sm">Dana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white">
                                        <td class="py-4 px-4 border border-blue-200 align-top">
                                            <div class="prose prose-sm max-w-none">{!! $note->ap_deskripsi ?: '-' !!}</div>
                                        </td>
                                        <td class="py-4 px-4 border border-blue-200 align-top">
                                            <div class="prose prose-sm max-w-none">{!! $note->ap_nama_kegiatan ?: '-' !!}</div>
                                        </td>
                                        <td class="py-4 px-4 border border-blue-200 align-top">
                                            <div class="prose prose-sm max-w-none">{!! $note->ap_peserta ?: '-' !!}</div>
                                        </td>
                                        <td class="py-4 px-4 border border-blue-200 align-top">
                                            <div class="prose prose-sm max-w-none">{!! $note->ap_waktu ?: '-' !!}</div>
                                        </td>
                                        <td class="py-4 px-4 border border-blue-200 align-top">
                                            <div class="prose prose-sm max-w-none">{!! $note->ap_dana ?: '-' !!}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @else
                <div class="flex-grow flex items-center justify-center flex-col text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-500">Belum Ada Catatan</h2>
                    <p class="text-lg">Grup ini belum menyimpan catatan untuk tema yang dipilih.</p>
                </div>
            @endif
        @else
            <div class="flex-grow flex items-center justify-center flex-col text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mb-6 opacity-50 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <h2 class="text-3xl font-bold text-blue-900 mb-2">Siap untuk Presentasi</h2>
                <p class="text-xl">Silakan pilih Tema dan Grup di atas untuk menampilkan hasil diskusi FGD.</p>
            </div>
        @endif
    </main>
</div>
