@extends('admin.layout')
@section('title', 'Categories')

@section('content')
<div class="page-header">
    <div>
        <h1>Categories</h1>
        <p>Top-level items in the mega menu.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.categories.create') }}"><i class="bi bi-plus-lg"></i> Add category</a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Order</th><th>Name</th><th>Links</th><th>Posts</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td><span class="badge badge-soft-muted">#{{ $category->sort_order }}</span></td>
                    <td>
                        <strong>{{ $category->name }}</strong><br>
                        <small class="text-muted">/{{ $category->slug }}</small>
                    </td>
                    <td><span class="badge badge-soft-primary">{{ $category->sub_categories_count }} links</span></td>
                    <td><span class="badge badge-soft-teal">{{ $category->posts_count }} posts</span></td>
                    <td>
                        <span class="badge {{ $category->is_active ? 'badge-soft-success' : 'badge-soft-muted' }}">
                            {{ $category->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-light" href="{{ route('admin.categories.edit', $category) }}"><i class="bi bi-pencil"></i> Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Delete this category and all its links?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger" aria-label="Delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
