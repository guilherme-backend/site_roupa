<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'shopping_cart';
    const SHIPPING_SESSION_KEY = 'selected_shipping';

    public function getCart(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    public function setShipping(array $shippingData): void
    {
        Session::put(self::SHIPPING_SESSION_KEY, $shippingData);
    }

    public function getShipping(): ?array
    {
        return Session::get(self::SHIPPING_SESSION_KEY);
    }

    public function clearShipping(): void
    {
        Session::forget(self::SHIPPING_SESSION_KEY);
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
                'image' => $variant->product->main_image ?? $variant->product->primaryImage?->image_path,
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
        $this->clearShipping();
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $shipping = $this->getShipping();
        if ($shipping && isset($shipping['price'])) {
            $total += (float) $shipping['price'];
        }

        return $total;
    }

    public function getSubtotal(): float
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

    public function validateStock(): array
    {
        $cart = $this->getCart();
        $outOfStockItems = [];
        $changed = false;

        foreach ($cart as $key => $item) {
            $variant = ProductVariant::find($item['variant_id']);
            
            if (!$variant || !$variant->is_available || $variant->stock_quantity <= 0) {
                $outOfStockItems[] = $item['product_name'] . " (Esgotado)";
                unset($cart[$key]);
                $changed = true;
            } elseif ($item['quantity'] > $variant->stock_quantity) {
                $outOfStockItems[] = $item['product_name'] . " (Quantidade ajustada para o estoque disponível)";
                $cart[$key]['quantity'] = $variant->stock_quantity;
                $changed = true;
            }
        }

        if ($changed) {
            Session::put(self::CART_SESSION_KEY, $cart);
        }

        return $outOfStockItems;
    }
}
