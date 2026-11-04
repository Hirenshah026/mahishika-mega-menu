<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_sent_to_login(): void
    {
        $this->get('/admin/categories')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Welcome back');
    }

    public function test_admin_can_login_and_logout(): void
    {
        $this->post('/admin/login', ['email' => 'admin@mahishika.com', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->post('/admin/login', ['email' => 'admin@mahishika.com', 'password' => 'Admin@123'])
            ->assertRedirect('/admin/categories');

        $this->get('/admin/categories')->assertOk()->assertSee('Logout');

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_admin_can_upload_post_image(): void
    {
        Storage::fake('public');
        $this->actingAs(User::first());

        $this->post('/admin/posts', [
            'category_id' => Category::first()->id,
            'type' => 'portfolio',
            'title' => 'New project',
            'url' => '#work',
            'image' => UploadedFile::fake()->image('project.jpg', 600, 400),
        ])->assertRedirect('/admin/posts');

        $post = Post::where('title', 'New project')->first();
        $this->assertStringContainsString('/storage/posts/', $post->image_url);
        Storage::disk('public')->assertExists('posts/'.basename($post->image_url));

        // A non-image file is rejected
        $this->post('/admin/posts', [
            'category_id' => Category::first()->id,
            'type' => 'portfolio',
            'title' => 'Bad file',
            'url' => '#',
            'image' => UploadedFile::fake()->create('virus.pdf', 10),
        ])->assertSessionHasErrors('image');

        // Every admin page renders while logged in
        foreach (['/admin/posts', '/admin/posts/create', "/admin/posts/{$post->id}/edit", '/admin/sub-categories', '/admin/categories/create'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
