@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">CV Management</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Title</th>
                                    <th>File</th>
                                    <th>Location</th>
                                    <th>Public</th>
                                    <th>Featured</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cvs as $cv)
                                    <tr>
                                        <td>{{ $cv->id }}</td>
                                        <td>{{ $cv->user->name }}<br><small>{{ $cv->user->email }}</small></td>
                                        <td>{{ Str::limit($cv->original_name, 30) }}</td>
                                        <td>{{ strtoupper($cv->file_type) }} ({{ number_format($cv->file_size / 1024, 2) }}KB)</td>
                                        <td>{{ $cv->location ?: 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.cvs.toggle-public', $cv) }}" class="badge badge-{{ $cv->is_public ? 'success' : 'secondary' }}">
                                                {{ $cv->is_public ? 'Yes' : 'No' }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $cv->is_featured ? 'warning' : 'secondary' }}">
                                                {{ $cv->is_featured ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $cv->status === 'active' ? 'success' : ($cv->status === 'archived' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($cv->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $cv->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <a href="{{ route('cv.download', $cv) }}" class="btn btn-sm btn-primary" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.cvs.show', $cv) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.cvs.destroy', $cv) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this CV?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No CVs found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $cvs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection