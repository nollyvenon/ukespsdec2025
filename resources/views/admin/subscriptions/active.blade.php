@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Active Subscriptions</h3>
                </div>
                <div class="card-body">
                    <!-- Subscription Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $subscriptions->where('status', 'active')->count() }}</h3>
                                    <p>Active Subscriptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-subscript"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>${{ number_format($subscriptions->where('status', 'active')->sum('amount'), 2) }}</h3>
                                    <p>Monthly Revenue</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $subscriptions->where('status', 'cancelled')->count() }}</h3>
                                    <p>Cancelled Subscriptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $subscriptions->where('status', 'expired')->count() }}</h3>
                                    <p>Expired Subscriptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Filters -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.subscriptions.active') }}" class="form-inline">
                                <div class="form-group mb-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by user, email..." value="{{ request('search') }}">
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select name="role_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="student" {{ request('role_type') == 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="recruiter" {{ request('role_type') == 'recruiter' ? 'selected' : '' }}>Recruiter</option>
                                        <option value="university_manager" {{ request('role_type') == 'university_manager' ? 'selected' : '' }}>University Manager</option>
                                        <option value="event_hoster" {{ request('role_type') == 'event_hoster' ? 'selected' : '' }}>Event Host</option>
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select name="type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ request('type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        <option value="one_time" {{ request('type') == 'one_time' ? 'selected' : '' }}>One-Time</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.subscriptions.active') }}" class="btn btn-secondary mb-2">
                                    Clear
                                </a>
                                <a href="{{ route('admin.subscriptions.all') }}" class="btn btn-info mb-2">
                                    All Subscriptions
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Active Subscriptions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Role Type</th>
                                    <th>Amount</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->id }}</td>
                                        <td>
                                            <div>{{ $subscription->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $subscription->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $subscription->package_name }}</td>
                                        <td>{{ ucfirst($subscription->type) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subscription->role_type === 'student' ? 'info' : ($subscription->role_type === 'recruiter' ? 'warning' : 'primary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $subscription->role_type)) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($subscription->amount, 2) }}</td>
                                        <td>{{ $subscription->start_date->format('M j, Y') }}</td>
                                        <td>{{ $subscription->end_date ? $subscription->end_date->format('M j, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'expired' ? 'danger' : ($subscription->status === 'cancelled' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No active subscriptions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection