<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'shopping_cart';

    public function getCart(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    public function addItem(int $variantId, int $quantity = 1): bool
    {
        $variant = ProductVariant::with('product')->find($variantId);

        if (!$variant || !$variant->isInStock() || $variant->stock_quantity < $quantity) {
            return false;
        }

        $cart = $this->getCart();
        $itemKey = "variant_{$variantId}";

        if (isset($cart[$itemKey])) {
            $newQuantity = $cart[$itemKey]['quantity'] + $quantity;
            
            if ($newQuantity > $variant->stock_quantity) {
                return false;
            }
            
            $cart[$itemKey]['quantity'] = $newQuantity;
        } else {
            $cart[$itemKey] = [
                'variant_id' => $variantId,
                'product_id' => $variant->product_id,
                'product_name' => $variant->product->name,
                'size' => $variant->size,
                'color' => $variant->color,
                'price' => $variant->final_price,
                'quantity' => $quantity,
                'image' => $variant->product->primaryImage?->image_path,
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    public function updateItem(string $itemKey, int $quantity): bool
    {
        $cart = $this->getCart();

        if (!isset($cart[$itemKey])) {
            return false;
        }

        $variant = ProductVariant::find($cart[$itemKey]['variant_id']);

        if (!$variant || $quantity > $variant->stock_quantity || $quantity < 1) {
            return false;
        }

        $cart[$itemKey]['quantity'] = $quantity;
        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    public function removeItem(string $itemKey): void
    {
        $cart = $this->getCart();
        unset($cart[$itemKey]);
        Session::put(self::CART_SESSION_KEY, $cart);
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
