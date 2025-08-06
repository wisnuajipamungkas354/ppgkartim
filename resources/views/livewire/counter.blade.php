<div class="p-2">
    <h1>{{ $result }}</h1>
    
    <form wire:submit="submit">
        <input type="text" class="p-2" placeholder="masukkan id" wire:model="input" autofocus/>
    </form>
 
    <button class="p-2 text-5xl text-white bg-amber-500" wire:click="decrement">-</button>
</div>