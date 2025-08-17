<?php

namespace App\Livewire\Public;

use App\Models\Goods;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Cart extends Component
{
    public $cartItems = [];
    public int $total = 0;

    protected $listeners = ['add-to-cart' => 'addToCart'];

    public function mount(): void
    {
        $this->loadCart();
    }

    public function loadCart(): void
    {
        $cart = Session::get('cart', []);
        $this->cartItems = [];
        $this->total = 0;

        foreach ($cart as $productId => $item) {
            $product = Goods::with(['images', 'category'])->find($productId);
            if ($product) {
                $this->cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity']
                ];
                $this->total += $product->price * $item['quantity'];
            }
        }
    }

    public function addToCart($productId, $quantity = 1): void
    {
        $product = Goods::find($productId);

        if (!$product || $product->count <= 0) {
            session()->flash('error', 'Product is not available.');
            return;
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($newQuantity <= $product->count) {
                $cart[$productId]['quantity'] = $newQuantity;
            } else {
                session()->flash('error', 'Not enough items in stock.');
                return;
            }
        } else {
            if ($quantity <= $product->count) {
                $cart[$productId] = [
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            } else {
                session()->flash('error', 'Not enough items in stock.');
                return;
            }
        }

        Session::put('cart', $cart);
        $this->loadCart();

        $this->dispatch('cart-updated');
        session()->flash('success', 'Product added to cart!');
    }

    public function updateQuantity($productId, $quantity): void
    {
        $product = Goods::find($productId);
        if (!$product) {
            return;
        }

        $cart = Session::get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } elseif ($quantity <= $product->count) {
            $cart[$productId]['quantity'] = $quantity;
        } else {
            session()->flash('error', 'Not enough items in stock.');
            return;
        }

        Session::put('cart', $cart);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeFromCart($productId): void
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Item removed from cart.');
    }

    public function clearCart(): void
    {
        Session::forget('cart');
        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Cart cleared.');
    }

    public function getCartCountProperty()
    {
        return array_sum(array_column(Session::get('cart', []), 'quantity'));
    }

    public function render()
    {
        return view('livewire.public.cart')
            ->layout('layouts.public');
    }
}
