@extends('admin.layout')
@section('title', 'Sub-categories')

@section('content')
<div class="page-header">
    <div>
        <h1>Sub-categories</h1>
        <p>Links shown inside each mega menu panel.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.sub-categories.create') }}"><i class="bi bi-plus-lg"></i> Add sub-category</a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Category</th><th>Order</th><th>Name</th><th>URL</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse ($subCategories as $sub)
                <tr>
                    <td><span class="badge badge-soft-primary">{{ $sub->category->name }}</span></td>
                    <td><span class="badge badge-soft-muted">#{{ $sub->sort_order }}</span></td>
                    <td>
                        <strong>{{ $sub->name }}</strong><br>
                        <small class="text-muted">{{ $sub->description }}</small>
                    </td>
                    <td><code>{{ $sub->url }}</code></td>
                    <td>
                        <span class="badge {{ $sub->is_active ? 'badge-soft-success' : 'badge-soft-muted' }}">
                            {{ $sub->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-light" href="{{ route('admin.sub-categories.edit', $sub) }}"><i class="bi bi-pencil"></i> Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('admin.sub-categories.destroy', $sub) }}" data-confirm="Delete this link?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger" aria-label="Delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No sub-categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
