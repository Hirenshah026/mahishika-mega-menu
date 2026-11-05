@extends('admin.layout')
@section('title', $category->exists ? 'Edit category' : 'New category')

@section('content')
<div class="page-header"><h1>{{ $category->exists ? 'Edit category' : 'New category' }}</h1></div>

<form method="POST" class="panel p-4" style="max-width: 640px"
      action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if ($category->exists) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="slug">Slug (leave empty to auto-create)</label>
        <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <input class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $category->description) }}">
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <label class="form-label" for="color">Dot color</label>
            <input type="color" class="form-control form-control-color w-100 @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $category->color) }}">
            @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-sm-8">
            <label class="form-label" for="url">"Explore hub" link</label>
            <input class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $category->url) }}">
            @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="sort_order">Order</label>
        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
        <label class="form-check-label" for="is_active">Show in menu</label>
    </div>

    <div>
        <button class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Save</button>
        <a class="btn btn-link" href="{{ route('admin.categories.index') }}">Cancel</a>
    </div>
</form>
@endsection
