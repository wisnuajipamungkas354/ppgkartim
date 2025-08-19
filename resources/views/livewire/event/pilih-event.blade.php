<div class="container px-4 py-8 mx-auto md:px-8 lg:px-12">
    <div class="max-w-4xl mx-auto">

        <div class="mb-8 text-center">
            <h2 class="mb-2 text-3xl font-bold text-gray-800 dark:text-gray-100">Pilih Event Presensi</h2>
            <p class="text-gray-600 dark:text-gray-400">Silakan pilih event yang ingin anda hadiri.</p>
        </div>

        <div class="mb-8">
            <input type="text" wire:model.debounce.500ms="search"
                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Cari nama event...">
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($events as $event)
                <a href="{{ route('events.presensi', ['event' => $event->id]) }}"
                   class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg dark:bg-gray-800 rounded-xl hover:shadow-2xl group">
        
                    <div class="w-full aspect-[3/4] overflow-hidden bg-gray-100 dark:bg-gray-700 cursor-pointer"
                         onclick="Livewire.emit('showPoster', '{{ $event->poster_image ? asset('storage/' . $event->poster_image) : null }}')">
                        @if($event->poster_image)
                            <img src="{{ asset('storage/' . $event->poster_image) }}"
                                 alt="Poster"
                                 class="object-cover object-center w-full h-full transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center w-full h-full text-lg text-gray-400">
                                No Image
                            </div>
                        @endif
                    </div>
        
                    <div class="absolute inset-x-0 bottom-0 p-4 text-white bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="mb-1 text-xl font-bold group-hover:underline text-shadow-md">
                            {{ $event->name }}
                        </h3>
                        <p class="text-sm text-gray-300 text-shadow">
                            {{ $event->place }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400 text-shadow">
                            {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                        </p>
                    </div>
        
                    <div class="absolute inset-0 transition-opacity duration-300 bg-blue-600 opacity-0 group-hover:opacity-20"></div>
                </a>
            @empty
                <p class="py-8 text-center text-gray-500 col-span-full dark:text-gray-400">Tidak ada event ditemukan.</p>
            @endforelse
        </div>
    </div>

    <div
        x-data="{ show: false, imgSrc: '' }"
        x-on:show-poster.window="imgSrc = $event.detail; show = true"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="relative max-w-full max-h-full p-4">
            <img :src="imgSrc" alt="Preview" class="max-h-[90vh] max-w-full rounded-lg shadow-2xl" />
            <button @click="show = false"
                    class="absolute p-2 text-white transition bg-red-600 rounded-full shadow-lg -top-3 -right-3 md:top-4 md:right-4 hover:bg-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>