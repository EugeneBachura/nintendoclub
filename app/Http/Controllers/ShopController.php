<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $items = Item::whereHas('shopItem')->with(['shopItem', 'translations'])->get();
        return view('shop.index', compact('items'));
    }

    public function buy(Request $request, $itemId)
    {
        $user = auth()->user();
        $item = Item::findOrFail($itemId);
        $shopItem = $item->shopItem;

        // Проверка наличия товара в магазине
        if ($shopItem->quantity !== null && $shopItem->quantity <= 0) {
            return redirect()->back()->with('error', __('messages.no_item'));
        }

        // Поиск товара в инвентаре пользователя
        $userItem = $user->inventory()->where('item_id', $item->id)->first();

        // Оплата монетами
        if ($shopItem->currency == 'coins') {
            $currentCoins = $user->profile->coins; // Предполагаем, что у пользователя есть профиль с монетами

            if ($currentCoins < $shopItem->price) {
                return redirect()->back()->withErrors(__('messages.insufficient'));
            }

            $user->profile->coins -= $shopItem->price;
        };

        // Оплата премиум пунктами
        if ($shopItem->currency == 'premium_points') {
            $currentPoints = $user->profile->premium_points; // Предполагаем, что у пользователя есть профиль с премиум пунктами

            if ($currentPoints < $shopItem->price) {
                return redirect()->back()->withErrors(__('messages.insufficient'));
            }

            $user->profile->premium_points -= $shopItem->price;
        };

        if ($userItem) {
            // Увеличиваем количество, если не превышает max_quantity или max_quantity равно null
            if ($item->max_quantity === null || $userItem->quantity < $item->max_quantity) {
                $userItem->increment('quantity');
            } else {
                return redirect()->back()->withErrors(__('messages.max_items'));
            }
        } else {
            // Если товара нет в инвентаре, добавляем его
            $user->inventory()->create([
                'item_id' => $item->id,
                'quantity' => 1
            ]);
        }

        // Обновление количества товара в магазине
        if ($shopItem->stock !== null) {
            $shopItem->decrement('stock');
        }
        $user->profile->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->item_id = $item->id;
        $transaction->quantity = 1;
        $transaction->price = $shopItem->price;
        $transaction->save();

        return redirect()->back()->with('success', __('messages.buy_success'));
    }



    public function history()
    {
        $transactions = auth()->user()->transactions()->with('item.translations')->get();
        return view('shop.history', compact('transactions'));
    }
}