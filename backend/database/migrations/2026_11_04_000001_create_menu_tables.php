<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Top-level menu entries: Services, Industries, Work, Approach, About
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('slug', 80)->unique();
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Links shown inside a category's mega menu panel
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->string('description', 160)->nullable();
            $table->string('url', 255)->default('#');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Portfolio items / articles featured in a panel
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['portfolio', 'article']);
            $table->string('title', 120);
            $table->string('excerpt', 255)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->string('url', 255)->default('#');
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        // "Book a Consultation" form submissions
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150);
            $table->string('company', 120)->nullable();
            $table->string('service', 80)->nullable();
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('categories');
    }
};
