<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // Criar Roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrador - acesso a tudo incluindo API Tester'],
            ['name' => 'user', 'description' => 'Usuário - acesso ao blog com CRUD dos próprios posts e comentários'],
        ];

        $roleIds = [];
        foreach ($roles as $role) {
            $roleIds[$role['name']] = DB::connection('rbac_db')->table('roles')->insertGetId(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Criar Permissions
        $permissions = [
            // Posts permissions
            ['name' => 'create', 'resource' => 'posts', 'description' => 'Criar posts'],
            ['name' => 'view', 'resource' => 'posts', 'description' => 'Visualizar posts'],
            ['name' => 'edit_own', 'resource' => 'posts', 'description' => 'Editar seus próprios posts'],
            ['name' => 'delete_any', 'resource' => 'posts', 'description' => 'Deletar qualquer post'],
            ['name' => 'delete_own', 'resource' => 'posts', 'description' => 'Deletar seus próprios posts'],

            // Comments permissions
            ['name' => 'create', 'resource' => 'comments', 'description' => 'Criar comentários'],
            ['name' => 'view', 'resource' => 'comments', 'description' => 'Visualizar comentários'],
            ['name' => 'edit_own', 'resource' => 'comments', 'description' => 'Editar seus próprios comentários'],
            ['name' => 'delete_any', 'resource' => 'comments', 'description' => 'Deletar qualquer comentário'],
            ['name' => 'delete_own', 'resource' => 'comments', 'description' => 'Deletar seus próprios comentários'],

            // API Tester permissions
            ['name' => 'access', 'resource' => 'api_tester', 'description' => 'Acessar API Tester'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permission) {
            $id = DB::connection('rbac_db')->table('permissions')->insertGetId(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $key = $permission['name'] . '_' . $permission['resource'];
            $permissionIds[$key] = $id;
        }

        // Atribuir permissions às roles
        $rolePermissions = [
            'admin' => [
                'create_posts', 'view_posts', 'edit_own_posts', 'delete_any_posts',
                'create_comments', 'view_comments', 'edit_own_comments', 'delete_any_comments',
                'access_api_tester',
            ],
            'user' => [
                'create_posts', 'view_posts', 'edit_own_posts', 'delete_own_posts',
                'create_comments', 'view_comments', 'edit_own_comments', 'delete_own_comments',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            foreach ($permissions as $permission) {
                DB::connection('rbac_db')->table('role_permissions')->insert([
                    'role_id' => $roleIds[$roleName],
                    'permission_id' => $permissionIds[$permission],
                ]);
            }
        }

        // Atribuir roles aos usuários
        DB::connection('rbac_db')->table('user_roles')->insert([
            ['user_id' => 1, 'role_id' => $roleIds['admin']],
            ['user_id' => 2, 'role_id' => $roleIds['user']],
            ['user_id' => 3, 'role_id' => $roleIds['user']],
        ]);
    }
}
