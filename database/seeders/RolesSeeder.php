<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Создание ролей
        $roleUser = Role::create(['name' => 'user']);
        $roleModerator = Role::create(['name' => 'moderator']);
        $roleEditor = Role::create(['name' => 'editor']);
        $roleReviewEditor = Role::create(['name' => 'review_editor']);
        $roleAdmin = Role::create(['name' => 'administrator']);

        // Создание разрешений
        $permissions = [
            'edit news',
            'delete news',
            'approve news',
            'publish news',
            'unpublish news',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Назначение разрешений ролям
        $roleAdmin->givePermissionTo(Permission::all());

        // Редакторы могут редактировать и публиковать статьи
        $roleEditor->givePermissionTo(['publish news', 'edit news', 'unpublish news']);

        // Модераторы могут удалять статьи и управлять пользователями
        $roleModerator->givePermissionTo(['delete comments', 'manage users']);

        // Редакторы проверки могут утверждать статьи
        $roleReviewEditor->givePermissionTo(['edit news', 'approve news', 'publish news', 'unpublish news', 'delete news']);
    }
}
