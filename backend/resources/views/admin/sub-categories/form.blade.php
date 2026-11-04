@extends('admin.layout')
@section('title', $subCategory->exists ? 'Edit sub-category' : 'New sub-category')

@section('content')
<div class="page-header"><h1>{{ $subCategory->exists ? 'Edit sub-category' : 'New sub-category' }}</h1></div>

<form method="POST" class="panel p-4" style="max-width: 640px"
      action="{{ $subCategory->exists ? route('admin.sub-categories.update', $subCategory) : route('admin.sub-categories.store') }}">
    @csrf
    @if ($subCategory->exists) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label" for="category_id">Category</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $subCategory->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $subCategory->name) }}">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="description">Short description</label>
        <input class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $subCategory->description) }}">
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="url">URL</label>
        <input class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $subCategory->url) }}">
        @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="sort_order">Order</label>
        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $subCategory->sort_order ?? 0) }}">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $subCategory->is_active))>
        <label class="form-check-label" for="is_active">Show in menu</label>
    </div>

    <div>
        <button class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Save</button>
        <a class="btn btn-link" href="{{ route('admin.sub-categories.index') }}">Cancel</a>
    </div>
</form>
@endsection
