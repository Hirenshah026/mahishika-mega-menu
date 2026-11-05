<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_returns_groups_with_links_and_featured_project(): void
    {
        $this->seed();

        $this->getJson('/api/menu')
            ->assertOk()
            ->assertJsonCount(4, 'categories')
            ->assertJsonPath('categories.2.name', 'Tech')
            ->assertJsonPath('categories.2.color', '#32c1e2')
            ->assertJsonCount(6, 'categories.2.sub_categories')
            ->assertJsonCount(1, 'categories.2.posts');
    }

    public function test_hidden_categories_are_not_in_the_menu(): void
    {
        $this->seed();
        Category::where('name', 'Video')->update(['is_active' => false]);

        $this->getJson('/api/menu')
            ->assertJsonCount(3, 'categories')
            ->assertJsonMissing(['name' => 'Video']);
    }
}
