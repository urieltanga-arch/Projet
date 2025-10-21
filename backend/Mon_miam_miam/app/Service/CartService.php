<?php

namespace App\Services;

use App\Models\Plat;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'restaurant_cart';

    public function getCart(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    public function addItem(int $platId, int $quantity = 1): array
    {
        $plat = Plat::findOrFail($platId);
        
        if (!$plat->is_available) {
            throw new \Exception('Ce plat n\'est plus disponible.');
        }

        $cart = $this->getCart();
        
        if (isset($cart[$platId])) {
            $cart[$platId]['quantity'] += $quantity;
        } else {
            $cart[$platId] = [
                'plat_id' => $plat->id,
                'name' => $plat->name,
                'price' => $plat->price,
                'points' => $plat->points ?? 0,
                'image_url' => $plat->image_url,
                'quantity' => $quantity,
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        
        return $cart;
    }

    public function updateQuantity(int $platId, int $quantity): array
    {
        $cart = $this->getCart();
        
        if ($quantity <= 0) {
            return $this->removeItem($platId);
        }
        
        if (isset($cart[$platId])) {
            $cart[$platId]['quantity'] = $quantity;
            Session::put(self::CART_SESSION_KEY, $cart);
        }
        
        return $cart;
    }

    public function removeItem(int $platId): array
    {
        $cart = $this->getCart();
        unset($cart[$platId]);
        Session::put(self::CART_SESSION_KEY, $cart);
        
        return $cart;
    }

    public function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    public function getTotalPoints(): int
    {
        $cart = $this->getCart();
        $points = 0;
        
        foreach ($cart as $item) {
            $points += $item['points'] * $item['quantity'];
        }
        
        return $points;
    }

    public function getItemCount(): int
    {
        $cart = $this->getCart();
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }

    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }
}