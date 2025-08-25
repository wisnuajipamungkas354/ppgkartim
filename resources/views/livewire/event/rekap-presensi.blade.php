<div class="p-5 md:p-10 lg:p-16">
  <header class="flex flex-col justify-between gap-5 md:flex-row">
    <div>
      <h3 class="text-lg font-bold md:text-xl lg:text-2xl">{{ $event->name }}</h3>
      <p class="mt-2">{{ Carbon\Carbon::parse($event->date)->format('d-m-Y') }} | {{ Carbon\Carbon::parse($event->start_time)->format('H:i') }} s/d {{ Carbon\Carbon::parse($event->end_time)->format('H:i') }} | {{ $event->place }}</p>
    </div>
    <div>
      <x-filament::button color="success" href="{{ $this->shareOnWhatsApp() }}" tag="a" target="_blank" >Share WA</x-filament::button>
      <x-filament::button color="danger" href="/event/{{ $event->id }}/rekap/download" tag="a" >Export PDF</x-filament::button>
    </div>
  </header>
  <div class="my-5">
    @livewire(\App\Livewire\Event\Widget\RekapPresensiStat::class, ['event' => $event])
  </div>
  <div>
    {{ $this->table }}
  </div>
</div>