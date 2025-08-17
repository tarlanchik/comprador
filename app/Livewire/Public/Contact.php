<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:255',
        'subject' => 'required|min:5|max:255',
        'message' => 'required|min:10|max:1000',
    ];

    public function submitContact(): void
    {
        $this->validate();

        // Here you would typically send an email or save to database
        // For now, we'll just show a success message

        session()->flash('success', 'Thank you for your message! We will get back to you soon.');

        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.public.contact')
            ->layout('layouts.public')
            ->title('Contact Us');
    }
}
