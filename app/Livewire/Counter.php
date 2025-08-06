<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $input = "";
    public $result;

    public function submit()
    {
        if($this->input == "0042919829") {
            $this->result = "Halo Arfan!";
        } else {
            $this->result = "Halo mas wisnu!";
        }
        $this->reset('input');
    }
 
    public function render()
    {
        return view('livewire.counter');
    }
}
