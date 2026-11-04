@extends('admin.layout')
@section('title', 'Portfolio / Articles')

@section('content')
<div class="page-header">
    <div>
        <h1>Portfolio / Articles</h1>
        <p>Cards featured on the right side of a mega menu panel.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.posts.create') }}"><i class="bi bi-plus-lg"></i> Add post</a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Post</th><th>Category</th><th>Type</th><th>Featured</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if ($post->image_url)
                                <img src="{{ $post->previewUrl() }}"
                                     alt="" width="64" height="44" class="rounded object-fit-cover bg-light">
                            @endif
                            <div>
                                <strong>{{ $post->title }}</strong><br>
                                <small class="text-muted">{{ $post->excerpt }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-soft-primary">{{ $post->category->name }}</span></td>
                    <td><span class="badge badge-soft-teal">{{ ucfirst($post->type) }}</span></td>
                    <td>
                        <span class="badge {{ $post->is_featured ? 'badge-soft-success' : 'badge-soft-muted' }}">
                            {{ $post->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-light" href="{{ route('admin.posts.edit', $post) }}"><i class="bi bi-pencil"></i> Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('admin.posts.destroy', $post) }}" data-confirm="Delete this post?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger" aria-label="Delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">No posts yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
