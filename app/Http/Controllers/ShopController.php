<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

/**
 * Provides operations for shop items and transactions.
 */
class ShopController extends Controller
{
    /**
     * Display a list of items available in the shop.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $items = Item::whereHas('shopItem')->with(['shopItem', 'translations'])->get();
        return view('shop.index', compact('items'));
    }

    /**
     * Purchase an item from the shop.
     *
     * @param Request $request
     * @param int $itemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buy(Request $request, $itemId)
    {
        $user = auth()->user();
        $item = Item::findOrFail($itemId);
        $shopItem = $item->shopItem;

        if ($shopItem->quantity !== null && $shopItem->quantity <= 0) {
            return redirect()->back()->with('error', __('messages.no_item'));
        }

        $userItem = $user->inventory()->where('item_id', $item->id)->first();

        if ($shopItem->currency == 'coins') {
            $currentCoins = $user->profile->coins;

            if ($currentCoins < $shopItem->price) {
                return redirect()->back()->withErrors(__('messages.insufficient'));
            }

            $user->profile->coins -= $shopItem->price;
        }

        if ($shopItem->currency == 'premium_points') {
            $currentPoints = $user->profile->premium_points;

            if ($currentPoints < $shopItem->price) {
                return redirect()->back()->withErrors(__('messages.insufficient'));
            }

            $user->profile->premium_points -= $shopItem->price;
        }

        if ($userItem) {
            if ($item->max_quantity === null || $userItem->quantity < $item->max_quantity) {
                $userItem->increment('quantity');
            } else {
                return redirect()->back()->withErrors(__('messages.max_items'));
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
        $user->profile->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->item_id = $item->id;
        $transaction->quantity = 1;
        $transaction->price = $shopItem->price;
        if ($shopItem->currency == 'premium_points') {
            $transaction->currency = 'premium_points';
        }
        $transaction->save();

        return redirect()->back()->with('success', __('messages.buy_success'));
    }

    /**
     * Display the user's transaction history.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $transactions = auth()->user()->transactions()->with('item.translations')->get();
        return view('shop.history', compact('transactions'));
    }
}
