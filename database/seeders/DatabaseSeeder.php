<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the RolePermissionSeeder first to set up roles and permissions
        $this->call(RolePermissionSeeder::class);

        // Run the CategorySeeder
        $this->call(CategorySeeder::class);

        // Then run the SubCategorySeeder
        $this->call(SubCategorySeeder::class);

        // Finally, run the ProductSeeder
        // $this->call(ProductSeeder::class);
    }
}
