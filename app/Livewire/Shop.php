<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Handles the shop functionality, including item display and purchase.
 */
class Shop extends Component
{
    public $items;
    public $successMessage;
    public $errorMessage;

    public function mount()
    {
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = Item::whereHas('shopItem')->with(['shopItem', 'translations'])->get();
    }

    public function buyItem($itemId)
    {
        $this->resetErrorBag();

        $user = auth()->user();
        $item = Item::findOrFail($itemId);
        $shopItem = $item->shopItem;

        if ($shopItem->quantity !== null && $shopItem->quantity <= 0) {
            $this->dispatch('notify', 'error', __('messages.no_item'));
            return;
        }

        $userItem = $user->inventory()->where('item_id', $item->id)->first();

        if ($shopItem->currency == 'coins') {
            if ($user->profile->coins < $shopItem->price) {
                $this->dispatch('notify', 'error', __('messages.insufficient'));
                return;
            }
            $user->profile->coins -= $shopItem->price;
        } elseif ($shopItem->currency == 'premium_points') {
            if ($user->profile->premium_points < $shopItem->price) {
                $this->dispatch('notify', 'error', __('messages.insufficient'));
                return;
            }
            $user->profile->premium_points -= $shopItem->price;
        }

        if ($userItem) {
            if ($item->max_quantity === null || $userItem->quantity < $item->max_quantity) {
                $userItem->increment('quantity');
            } else {
                $this->dispatch('notify', 'error', __('messages.max_items'));
                return;
            }
        } else {
            $user->inventory()->create([
                'item_id' => $item->id,
                'quantity' => 1,
            ]);
        }

        if ($shopItem->stock !== null) {
            $shopItem->decrement('stock');
        }

        $transaction = new \App\Models\Transaction();
        $transaction->user_id = $user->id;
        $transaction->item_id = $item->id;
        $transaction->quantity = 1;
        $transaction->price = $shopItem->price;
        $transaction->currency = $shopItem->currency;
        $transaction->save();

        $user->profile->save();

        $this->dispatch('notify', 'success', __('messages.buy_success'));

        $this->loadItems();
    }


    public function render()
    {
        return view('livewire.shop')->layout('layouts.app');
    }
}
