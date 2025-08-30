<div class="flex flex-col items-center justify-center min-h-screen p-4 bg-gray-100">
    <div class="relative flex flex-col justify-center w-full max-w-2xl p-8 bg-white rounded-lg shadow-xl" id="presensi-container">
        
        <button id="fullscreen-btn" class="absolute p-2 text-gray-500 transition duration-300 top-4 right-4 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75v4.5m0-4.5h-4.5m4.5 0L15 9m5.25 11.25v-4.5m0 4.5h-4.5m4.5 0L15 15" />
            </svg>
        </button>

        <div class="mb-6 text-center">
            <h2 class="text-3xl font-extrabold text-gray-800">Presensi Acara</h2>
            <p class="mt-2 text-lg text-gray-600">{{ $event->name }}</p>
        </div>

        <div id="real-time-clock" class="mb-6 font-mono text-4xl text-center text-gray-700">
            </div>

        <div class="mb-8">
            <input type="text" wire:model.live="rfid_tag" autofocus id="rfid-input"
                class="w-full p-4 text-xl tracking-widest text-center text-gray-800 placeholder-gray-400 transition duration-300 border-2 border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-blue-500"
                placeholder="Scan RFID..." autocomplete="false"/>
        </div>

        @if($lastParticipant)
            <div class="p-6 mb-8 border border-green-200 rounded-lg shadow-md bg-green-50">
                <p class="text-sm font-medium text-gray-500">Terakhir Hadir:</p>
                <p class="mt-1 text-2xl font-bold text-green-700">
                    {{ $lastParticipant->data_json['nama'] ?? '-' }}
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    <span class="font-semibold">UID:</span> {{ $lastParticipant->rfid_tag ?? '-' }}
                </p>
            </div>
        @endif        
        
        @if(session('success'))
            <div class="p-4 mb-4 font-semibold  @if(session('over_time')) text-warning-700 bg-warning-100 @else text-green-700 bg-green-100 @endif  rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 font-semibold text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<script>
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('real-time-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }

    function redirect() {
        const now = Date.now();
        const endTime = Date.parse('{{ $event->end_time }}');
        if(now < endTime) {
            window.location.href = '{{ route("events") }}';
        }
    }
    // Perbarui jam setiap detik
    setInterval(updateTime, 1000);
    setInterval(redirect, 1000);

    // Jalankan pertama kali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateTime);

    // Dapatkan elemen input RFID dan container
    const rfidInput = document.getElementById('rfid-input');
    const container = document.getElementById('presensi-container');
    const fullscreenBtn = document.getElementById('fullscreen-btn');

    // Fungsi untuk mengembalikan fokus
    function regainFocus() {
        // Gunakan timeout kecil untuk memastikan browser selesai memproses perubahan
        setTimeout(() => {
            rfidInput.focus();
        }, 100);
    }

    // Logika Tombol Fullscreen
    fullscreenBtn.addEventListener('click', () => {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            container.requestFullscreen().catch(err => {
                alert(`Error saat mencoba mode fullscreen: ${err.message}`);
            });
        }
    });

    // PENTING: Mendengarkan perubahan status fullscreen
    document.addEventListener('fullscreenchange', regainFocus);
    document.addEventListener('mozfullscreenchange', regainFocus);
    document.addEventListener('webkitfullscreenchange', regainFocus);
    document.addEventListener('msfullscreenchange', regainFocus);

    // Pastikan input selalu fokus saat halaman dibuka atau di-reload
    document.addEventListener('DOMContentLoaded', () => {
        container.requestFullscreen().catch(err => {
            console.error(`Error saat mencoba mode fullscreen otomatis: ${err.message}`);
        });
        regainFocus();
    });
</script>