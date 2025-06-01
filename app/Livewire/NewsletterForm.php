<?php

namespace App\Livewire;

use Livewire\Component;

class NewsletterForm extends Component
{
    public $email = '';
    public $success = '';

    public function subscribe()
    {
        $this->validate([
            'email' => 'required|email|max:255'
        ]);

        // Here you would typically save to a "newsletters" table, send to Mailchimp, etc.
        // For demo: just pretend, or log/save as you wish.

        $this->success = 'Subscribed successfully!';

        // Reset email after success
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.newsletter-form');
    }
}
