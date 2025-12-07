@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Blog Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-blog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Posts</span>
                                    <span class="info-box-number">{{ $posts->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-comments"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Comments</span>
                                    <span class="info-box-number">{{ $comments->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tags"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Categories</span>
                                    <span class="info-box-number">{{ $categories->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Authors</span>
                                    <span class="info-box-number">12</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Posts</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('blog.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Create Post
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Author</th>
                                                    <th>Category</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Comments</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($posts as $post)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ Str::limit($post->title, 30) }}</td>
                                                    <td>{{ $post->author->name ?? 'N/A' }}</td>
                                                    <td>{{ $post->category->name ?? 'Uncategorized' }}</td>
                                                    <td>{{ $post->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($post->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $post->comments->count() }}</td>
                                                    <td>
                                                        <a href="{{ route('blog.show', ['category' => $post->category->slug ?? 'uncategorized', 'slug' => $post->slug]) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('blog.edit', $post->slug) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $posts->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories and Recent Comments -->
                        <div class="col-md-4">
                            <!-- Categories -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Categories</h3>
                                </div>
                                <div class="card-body">
                                    @forelse($categories as $category)
                                        <span class="badge bg-secondary m-1 px-3 py-2">
                                            {{ $category->name }} ({{ $category->posts->count() }})
                                        </span>
                                    @empty
                                        <p>No categories created yet.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Recent Comments -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Comments</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-unstyled">
                                        @foreach($comments as $comment)
                                        <li class="border-bottom p-3">
                                            <div class="d-flex">
                                                <div>
                                                    <strong>{{ $comment->author->name ?? 'Anonymous' }}</strong>
                                                    <p class="text-sm text-muted mb-0">{{ Str::limit($comment->content, 50) }}</p>
                                                    <small class="text-muted">{{ $comment->created_at->format('M j, Y g:i A') }}</small>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection