@extends('admin.layout')
@section('title', $post->exists ? 'Edit post' : 'New post')

@section('content')
<div class="page-header"><h1>{{ $post->exists ? 'Edit post' : 'New post' }}</h1></div>

<form method="POST" class="panel p-4" style="max-width: 640px" enctype="multipart/form-data"
      action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
    @csrf
    @if ($post->exists) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label" for="category_id">Show in category</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="type">Type</label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
            @foreach (\App\Models\Post::TYPES as $type)
                <option value="{{ $type }}" @selected(old('type', $post->type) === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="title">Title</label>
        <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}">
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="excerpt">Excerpt</label>
        <input class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" value="{{ old('excerpt', $post->excerpt) }}">
        @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <span class="form-label d-block">Image</span>
        <input type="file" class="visually-hidden" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        <label class="upload-box @error('image') is-invalid @enderror" for="image">
            <img id="image-preview" src="{{ $post->previewUrl() }}" alt="" @unless ($post->image_url) hidden @endunless>
            <span class="upload-hint" @if ($post->image_url) hidden @endif>
                <i class="bi bi-cloud-arrow-up"></i>
                <strong>Click to upload an image</strong>
                <small>JPG, PNG or WEBP · max 2 MB</small>
            </span>
            <span class="upload-change" @unless ($post->image_url) hidden @endunless><i class="bi bi-arrow-repeat"></i> Change image</span>
        </label>
        @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="url">Link URL</label>
        <input class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $post->url) }}">
        @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
        <label class="form-check-label" for="is_featured">Feature in mega menu</label>
    </div>

    <div>
        <button class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Save</button>
        <a class="btn btn-link" href="{{ route('admin.posts.index') }}">Cancel</a>
    </div>
</form>

<script>
    // Show the chosen image right away, before saving
    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById('image-preview');
        preview.src = URL.createObjectURL(file);
        preview.hidden = false;
        document.querySelector('.upload-hint').hidden = true;
        document.querySelector('.upload-change').hidden = false;
    });
</script>
@endsection
