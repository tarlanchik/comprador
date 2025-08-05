<?php

namespace App\Livewire\Admin;

//use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('admin.layouts.admin')]
    public function render()
    {
       //Log::info('Dashboard rendered');
        return view('livewire.admin.dashboard');
    }
}
