<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Load the default mega menu content from database/data/menu.json.
     */
    public function run(): void
    {
        // Admin login (change the password after first login in a real project)
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mahishika.com',
            'password' => 'Admin@123',
        ]);

        $data = json_decode(file_get_contents(database_path('data/menu.json')), true);

        foreach ($data['categories'] as $index => $item) {
            $category = Category::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'description' => $item['description'],
                'sort_order' => $index + 1,
            ]);

            foreach ($item['sub_categories'] as $subIndex => $sub) {
                $category->subCategories()->create($sub + ['sort_order' => $subIndex + 1]);
            }

            foreach ($item['posts'] as $post) {
                $category->posts()->create($post);
            }
        }
    }
}
