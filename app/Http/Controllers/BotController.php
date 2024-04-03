<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\UserDesignSetting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class BotController extends Controller
{
    public function checkRegistration(Request $request)
    {
        $discordId = $request->input('discord_id');
        $user = User::where('discord_id', $discordId)->first();

        if ($user) {
            $roles = $user->getRoleNames(); // Возвращает коллекцию с названиями ролей

            return response()->json([
                'isRegistered' => true,
                'roles' => $roles
            ]);
        }

        return response()->json([
            'isRegistered' => false,
            'roles' => []
        ]);
    }

    public function banUser(Request $request)
    {
        $discordId = $request->input('discord_id');

        // Находим пользователя по discord_id
        $user = User::where('discord_id', $discordId)->first();

        if ($user) {
            // Проверяем, существует ли роль 'ban'
            if (!Role::where('name', 'ban')->exists()) {
                // Создаем роль 'ban', если она не существует
                Role::create(['name' => 'ban']);
            }

            // Назначаем пользователю роль 'ban'
            $user->assignRole('ban');

            return response()->json([
                'success' => true,
                'message' => 'User has been banned successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found on the site.'
        ]);
    }

    public function unbanUser(Request $request)
    {
        $discordId = $request->input('discord_id');

        // Находим пользователя по discord_id
        $user = User::where('discord_id', $discordId)->first();

        if ($user) {
            // Проверяем, существует ли роль 'ban'
            if (!Role::where('name', 'ban')->exists()) {
                // Создаем роль 'ban', если она не существует
                Role::create(['name' => 'ban']);
            }

            // Убираем пользователю роль 'ban'
            $user->removeRole('ban');

            return response()->json([
                'success' => true,
                'message' => 'User has been unbanned successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found on the site.'
        ]);
    }

    public function getUsersWithItem(Request $request)
    {
        $itemName = $request->input('name'); // Имя предмета, который мы ищем

        // Находим предмет по имени
        $item = Item::where('name', $itemName)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => "Item '{$itemName}' not found."
            ]);
        }

        try {
            // Получаем пользователей, у которых есть этот предмет
            $userItems = $item->userItems;

            $userDiscordIds = $userItems->map(function ($userItem) {
                if (!$userItem->user->hasRole('ban')) {
                    return $userItem->user->discord_id; // Предполагаем наличие свойства discord_id
                }
            })->unique();

            $countUsers = count($userDiscordIds);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error {$th}"
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => "'{$countUsers}' users founded with '{$itemName}'.",
            'discord_ids' => $userDiscordIds
        ]);
    }

    public function getUserNicknameColor(Request $request)
    {
        $color = $request->input('color');

        try {
            // Находим настройки дизайна с указанным цветом
            $settings = UserDesignSetting::where('nickname_color', $color)->get();

            // Извлекаем discord_id связанных пользователей
            $discordIds = $settings->map(function ($setting) {
                return $setting->user->discord_id ?? null;
            })->filter();

            $countUsers = count($discordIds);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error {$th}",
                'discord_ids' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "'{$countUsers}' users founded with '{$color}'.",
            'discord_ids' => $discordIds
        ]);
    }
}
