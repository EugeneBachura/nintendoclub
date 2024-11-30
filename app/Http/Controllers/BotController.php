<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\UserDesignSetting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * Handles interactions with the bot for user management and data retrieval.
 */
class BotController extends Controller
{
    /**
     * Checks if a user is registered and retrieves their roles.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRegistration(Request $request)
    {
        $discordId = $request->input('discord_id');
        $user = User::where('discord_id', $discordId)->first();

        if ($user) {
            $roles = $user->getRoleNames();

            return response()->json([
                'isRegistered' => true,
                'roles' => $roles,
            ]);
        }

        return response()->json([
            'isRegistered' => false,
            'roles' => [],
        ]);
    }

    /**
     * Bans a user by assigning them the 'ban' role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function banUser(Request $request)
    {
        $discordId = $request->input('discord_id');
        $user = User::where('discord_id', $discordId)->first();

        if ($user) {
            if (!Role::where('name', 'ban')->exists()) {
                Role::create(['name' => 'ban']);
            }

            $user->assignRole('ban');

            return response()->json([
                'success' => true,
                'message' => 'User has been banned successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found on the site.',
        ]);
    }

    /**
     * Unbans a user by removing their 'ban' role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbanUser(Request $request)
    {
        $discordId = $request->input('discord_id');
        $user = User::where('discord_id', $discordId)->first();

        if ($user) {
            if (!Role::where('name', 'ban')->exists()) {
                Role::create(['name' => 'ban']);
            }

            $user->removeRole('ban');

            return response()->json([
                'success' => true,
                'message' => 'User has been unbanned successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found on the site.',
        ]);
    }

    /**
     * Retrieves users who own a specific item.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersWithItem(Request $request)
    {
        $itemName = $request->input('name');
        $item = Item::where('name', $itemName)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => "Item '{$itemName}' not found.",
            ]);
        }

        try {
            $userItems = $item->userItems;

            $userDiscordIds = $userItems->map(function ($userItem) {
                if (!$userItem->user->hasRole('ban')) {
                    return $userItem->user->discord_id;
                }
            })->unique();

            $countUsers = count($userDiscordIds);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error {$th}",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "'{$countUsers}' users founded with '{$itemName}'.",
            'discord_ids' => $userDiscordIds,
        ]);
    }

    /**
     * Retrieves users with a specific nickname color.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNicknameColor(Request $request)
    {
        $color = $request->input('color');

        try {
            $settings = UserDesignSetting::where('nickname_color', $color)->get();

            $discordIds = $settings->map(function ($setting) {
                return $setting->user->discord_id ?? null;
            })->filter();

            $countUsers = count($discordIds);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error {$th}",
                'discord_ids' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "'{$countUsers}' users founded with '{$color}'.",
            'discord_ids' => $discordIds,
        ]);
    }
}
