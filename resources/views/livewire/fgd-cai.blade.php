<div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    <!-- Header/Navbar -->
    <header class="bg-white border-b py-3 px-4 md:py-4 md:px-12 flex items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-cai.webp') }}" alt="Logo CAI" class="h-8 md:h-10 w-auto object-contain">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-blue-900">Notulis FGD</h1>
                <p class="text-xs md:text-sm text-gray-500">Cinta Alam Indonesia — Focus Group Discussion</p>
            </div>
        </div>
        <div>
            <a href="{{ route('fgd-cai.presentasi') }}" target="_blank" class="flex items-center gap-1 md:gap-2 px-3 md:px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs md:text-sm font-bold rounded-lg shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="hidden sm:inline">Mode Presentasi</span>
                <span class="sm:hidden">Presentasi</span>
            </a>
        </div>
    </header>

    <style>
        /* Custom styles to match the Vercel reference */
        .fi-section-header {
            background-color: #eef2ff !important; /* bg-indigo-50 */
            padding: 0.75rem 1.25rem !important;
            border-bottom: 1px solid #e0e7ff; /* border-indigo-100 */
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }
        .fi-section-header-heading {
            color: #3730a3 !important; /* text-indigo-800 */
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .fi-section {
            box-shadow: none !important;
            border: 1px solid #f3f4f6 !important;
        }
    </style>

    <!-- Main Content -->
    <main class="flex-grow p-2 md:p-8 flex flex-col items-center">
        @if($isSubmitted)
            <div class="w-full max-w-5xl bg-green-50 border border-green-200 text-green-800 rounded-xl p-6 mb-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-xl md:text-2xl font-bold mb-2">Alhamdulillah Jazakumullohu Khoiro</h2>
                <p class="mb-1">Catatan FGD berhasil disimpan, <button wire:click="$set('isSubmitted', false)" class="font-bold underline hover:text-green-900 focus:outline-none">klik disini untuk melihat hasil catatannya</button></p>
                <p class="mb-4">Semoga Alloh paring manfaat lancar dan barokah</p>
                <button wire:click="$set('isSubmitted', false)" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm">
                    Tutup
                </button>
            </div>
        @else
            <!-- Card Pilih Grup & Sesi -->
            <div class="w-full max-w-5xl bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 mb-8">
                <h2 class="text-sm font-semibold text-gray-600 mb-4 uppercase tracking-wide">Pilih Grup & Sesi FGD</h2>
                
                @if(count($sessions) > 1)
                <div class="mb-4">
                    <select wire:model.live="activeSessionId" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Pilih Sesi FGD --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <select wire:model.live="activeGroupId" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-3" @if(empty($groups)) disabled @endif>
                            <option value="">-- Pilih Grup --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('activeGroupId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <select wire:model.live="activeThemeId" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-3" @if(empty($themes)) disabled @endif>
                            <option value="">Tema belum dipilih</option>
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->title }}</option>
                            @endforeach
                        </select>
                        @error('activeThemeId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if(empty($themes) && $activeSessionId)
                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg flex items-center gap-2 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium"><strong>Tema sesi FGD belum dibuat oleh admin.</strong> Silakan coba lagi nanti atau hubungi panitia.</span>
                </div>
                @endif
            </div>

            <!-- Empty State Notulis -->
            @if(!$activeGroupId || !$activeThemeId)
                <div class="flex flex-col items-center justify-center text-gray-400 mt-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-md">Silakan pilih grup dan tema untuk mulai mengisi notulis</p>
                </div>
            @else
                <div class="w-full max-w-5xl bg-white rounded-xl shadow-sm border border-gray-200 p-3 md:p-8">
                    <h3 class="text-xl font-bold text-blue-900 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Form Notulis — {{ $activeGroupName }}
                    </h3>
                    
                    <form wire:submit.prevent="submitFgdNote">

                        <!-- Filament Form -->
                        <div class="mb-8">
                            {{ $this->form }}
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end border-t border-gray-200 pt-6 mt-6">
                            <button type="submit" class="px-8 py-3 bg-blue-800 text-white font-bold rounded-lg hover:bg-blue-900 transition shadow-md flex items-center gap-2">
                                <span wire:loading.remove wire:target="submitFgdNote">Simpan Notulis {{ $activeGroupName }}</span>
                                <span wire:loading wire:target="submitFgdNote">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endif
    </main>
    
    <!-- Footer -->
    <footer class="py-6 text-center text-gray-400 text-sm mt-auto">
        &copy; 2026 Cinta Alam Indonesia
    </footer>
</div>
