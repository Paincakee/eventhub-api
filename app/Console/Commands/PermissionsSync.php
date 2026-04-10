<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use Spatie\Permission\Models\Role;

class PermissionsSync extends \Encore\BaseKit\Console\Commands\PermissionsSync
{
    /**
     * Give admin all permissions.
     */
    public function buildSuperAdmin(): void
    {
        $roleEnum = config('base-kit.user_roles');

        $role = Role::firstOrCreate([
            'name' => $roleEnum::from('super_admin')->value,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($this->generatePermissions(config('base-kit.permissions')));
    }

    /**
     * Give admin all permissions.
     */
    public function buildAdmin(): void
    {
        $roleEnum = config('base-kit.user_roles');

        $role = Role::firstOrCreate([
            'name' => $roleEnum::from('admin')->value,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($this->generatePermissions(config('base-kit.permissions')));
    }

    /**
     * Give User specific permissions.
     */
    public function buildUser(): void
    {
        $permissions = $this->buildPermissionsFromConfig([
            'users' => ['view'],
        ]);

        $this->syncRolePermissions(UserRole::USER, $permissions);
    }

    /**
     * Build the permissions array based on a configuration.
     *
     * @param array<string, bool|string[]> $config
     *
     * @return array<string, string[]>
     */
    private function buildPermissionsFromConfig(array $config): array
    {
        /** @var array<string, string[]> $rawPermissions */
        $rawPermissions = config('base-kit.permissions', []);
        $permissions = [];

        foreach ($rawPermissions as $resource => $actions) {
            $resourceConfig = $config[$resource] ?? false;

            if ($resourceConfig === true) {
                $permissions[$resource] = $actions;
            } elseif (is_array($resourceConfig)) {
                $permissions[$resource] = $resourceConfig;
            } else {
                $permissions[$resource] = [];
            }
        }

        return $permissions;
    }

    /**
     * Sync permissions for a given role.
     *
     * @param UserRole $userRole
     * @param array<string, string[]> $permissions
     */
    private function syncRolePermissions(UserRole $userRole, array $permissions): void
    {
        $role = Role::firstOrCreate([
            'name' => $userRole->value,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($this->generatePermissions($permissions));
    }
}
