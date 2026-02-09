<x-filament-panels::page>
  <div class="space-y-4">
    <h2 class="text-xl font-bold">Pilih Peran Anda</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($roles as $role)
            <?php 
                if($role->name !== 'ph_ppg') {
                    $stateRole = ucwords(str_replace('_', ' ', $role->name));
                } else {
                    $stateRole = 'PH PPG';
                }
            ?>
            <div class="p-4 bg-white border rounded-lg shadow dark:bg-gray-800">
                <div class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $stateRole }}
                </div>
                @if($activeRole == $role->name)
                    <x-filament::button class="mt-2" color="success">Sedang Aktif</x-filament::button>
                @else
                    <x-filament::button class="mt-2" wire:click="switchRole({{ $role->id }})">  
                        Gunakan Role Ini
                    </x-filament::button>
                @endif
            </div>
        @endforeach
    </div>
  </div>
</x-filament-panels::page>
