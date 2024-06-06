<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class CartCount extends Component
{

    public $count = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }


    #[On('cartUpdated')]
    public function updateCartCount()
    {

        $this->count = array_sum(array_column(session()->get('cart', []), 'quantity'));
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}
