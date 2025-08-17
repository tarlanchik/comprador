<?php

namespace App\Livewire\Public;

use Livewire\Component;

class About extends Component
{
    public function render()
    {
        return view('livewire.public.about')->title('About Comprador shop')->layout('layouts.public');
    }
}
