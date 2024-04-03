<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $userItems = auth()->user()->items()->with('item.translations')->get();
        $userNickname = auth()->user()->profile->nickname();
        return view('inventory.index', compact('userItems', 'userNickname'));
    }
}
