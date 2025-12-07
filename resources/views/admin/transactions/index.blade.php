@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Transactions</h3>
                </div>
                <div class="card-body">
                    <!-- Payment Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>${{ number_format($transactions->sum('amount'), 2) }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $transactions->where('status', 'completed')->count() }}</h3>
                                    <p>Successful Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $transactions->where('status', 'pending')->count() }}</h3>
                                    <p>Pending Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $transactions->where('status', 'failed')->count() }}</h3>
                                    <p>Failed Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Filter -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.transactions.index') }}" class="form-inline">
                                <div class="form-group mb-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by user, email..." value="{{ request('search') }}">
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select name="type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="premium_job_post" {{ request('type') == 'premium_job_post' ? 'selected' : '' }}>Premium Job Post</option>
                                        <option value="premium_course_promotion" {{ request('type') == 'premium_course_promotion' ? 'selected' : '' }}>Course Promotion</option>
                                        <option value="premium_event_promotion" {{ request('type') == 'premium_event_promotion' ? 'selected' : '' }}>Event Promotion</option>
                                        <option value="university_admission_service" {{ request('type') == 'university_admission_service' ? 'selected' : '' }}>University Service</option>
                                        <option value="ad_payment" {{ request('type') == 'ad_payment' ? 'selected' : '' }}>Ad Payment</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary mb-2">
                                    Clear
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Payment Gateway</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>
                                            <div>{{ $transaction->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type === 'premium_job_post' ? 'primary' : ($transaction->type === 'ad_payment' ? 'info' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ ucfirst($transaction->payment_gateway) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
                                        <td>
                                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection