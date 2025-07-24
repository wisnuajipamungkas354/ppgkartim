<x-filament-panels::page>
  <div class="space-y-4">
    <h2 class="text-xl font-bold">Pilih Peran Anda</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($roles as $role)
            <div class="p-4 border rounded-lg shadow bg-white dark:bg-gray-800">
                <div class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $role->name }}
                </div>

                {{-- <form wire:submit.prevent="switchRole({{ $role->id }})"> --}}
                    <x-filament::button class="mt-2" wire:click="switchRole({{ $role->id }})">Gunakan Role Ini</x-filament::button>
                {{-- </form> --}}
            </div>
        @endforeach
    </div>
  </div>
</x-filament-panels::page>
