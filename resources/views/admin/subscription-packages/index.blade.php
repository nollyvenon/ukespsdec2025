@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Subscription Packages</h3>
                    <a href="{{ route('admin.subscription-packages.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Create Package
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Role Type</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $package->role_type)) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst($package->type) }}</span>
                                </td>
                                <td>${{ number_format($package->price, 2) }}</td>
                                <td>{{ $package->duration_days ? $package->duration_days . ' days' : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $package->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $package->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.subscription-packages.edit', $package) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.subscription-packages.delete', $package) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $packages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection