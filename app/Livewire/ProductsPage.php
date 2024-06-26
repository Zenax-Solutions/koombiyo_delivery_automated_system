<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class ProductsPage extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $selectedSize = [];
    public $selectedQuantity = [];
    public $cart = [];
    public $cartData;
    public $totalPrice = 0;
    public $branch;


    public function mount(Request $request)
    {
        $this->branch = Branch::where('slug', $request->branch)->firstOrFail();

        $this->cart = session()->get('cart', []);

        $this->calculateTotal();
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        $variations = $product->variations;

        $defaultSize = $variations[0] ?? 'S';
        $size = $this->selectedSize[$productId] ?? $defaultSize;
        $quantity = $this->selectedQuantity[$productId] ?? 1;

        $this->cart[$productId] = [
            'product_id' => $productId,
            'size' => $size,
            'quantity' => $quantity,
        ];

        session()->put('cart', $this->cart);

        $this->selectedSize = [];
        $this->selectedQuantity = [];

        $this->calculateTotal();
        $this->dispatch('cartUpdated');
        toastr()->success('Product added to cart successfully!');
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        session()->put('cart', $this->cart);
        $this->calculateTotal();
        $this->dispatch('cartUpdated');
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $this->totalPrice += $product->price * $item['quantity'];
            }
        }
    }



    public function render()
    {
        $productsQuery = '';

        if ($this->search !== '') {
            $productsQuery = Product::where('branch_id', $this->branch->id)->where('name', 'like', '%' . $this->search . '%')
                ->paginate(10);
        } else {
            $productsQuery = Product::where('branch_id', $this->branch->id)
                ->paginate(10);
        }

        $this->cartData = Session::get('cart');

        return view('livewire.products-page', compact('productsQuery'));
    }
}
