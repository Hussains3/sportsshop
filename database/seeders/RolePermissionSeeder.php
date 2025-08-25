<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for different modules
        $permissions = [
            // Dashboard permissions
            'view-dashboard',
            'view-low-stock',
            
            // Category permissions
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            
            // SubCategory permissions
            'view-subcategories',
            'create-subcategories',
            'edit-subcategories',
            'delete-subcategories',
            
            // Product permissions
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Batch permissions
            'view-batches',
            'create-batches',
            'edit-batches',
            'delete-batches',
            
            // POS permissions
            'access-pos',
            'create-sales',
            'view-sales',
            'view-receipts',
            
            // Report permissions
            'view-reports',
            'view-sales-reports',
            'view-inventory-reports',
            'view-financial-reports',
            'export-reports',
            
            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-roles',
            'manage-permissions',
            
            // Profile permissions
            'edit-profile',
            'delete-profile',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - has all permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Manager role - has most permissions except user management
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerPermissions = [
            'view-dashboard',
            'view-low-stock',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-subcategories', 'create-subcategories', 'edit-subcategories', 'delete-subcategories',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-batches', 'create-batches', 'edit-batches', 'delete-batches',
            'access-pos', 'create-sales', 'view-sales', 'view-receipts',
            'view-reports', 'view-sales-reports', 'view-inventory-reports', 'view-financial-reports', 'export-reports',
            'edit-profile',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Staff role - has basic permissions for daily operations
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffPermissions = [
            'view-dashboard',
            'view-categories', 'view-subcategories',
            'view-products',
            'view-batches',
            'access-pos', 'create-sales', 'view-sales', 'view-receipts',
            'view-reports', 'view-sales-reports', 'view-inventory-reports',
            'edit-profile',
        ];
        $staffRole->givePermissionTo($staffPermissions);

        // Create default admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sportsshop.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
            ]
        );
        $adminUser->assignRole('Admin');

        // Create default manager user if it doesn't exist
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@sportsshop.com'],
            [
                'name' => 'Store Manager',
                'password' => bcrypt('manager123'),
            ]
        );
        $managerUser->assignRole('Manager');

        // Create default staff user if it doesn't exist
        $staffUser = User::firstOrCreate(
            ['email' => 'staff@sportsshop.com'],
            [
                'name' => 'Staff Member',
                'password' => bcrypt('staff123'),
            ]
        );
        $staffUser->assignRole('Staff');
    }
}
