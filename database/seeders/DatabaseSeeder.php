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
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'password' => bcrypt('admin123'),
        ]);

        // Create admin user
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@app.com',
                'password' => bcrypt('admin123'),
            ]);
        }

                // Run the CategorySeeder first
        $this->call(CategorySeeder::class);

        // Then run the SubCategorySeeder
        $this->call(SubCategorySeeder::class);

        // Finally, run the ProductSeeder
        $this->call(ProductSeeder::class);
    }
}
