@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Premium Content Payments</h3>
                </div>
                <div class="card-body">
                    <!-- Premium Payments Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-green elevation-1">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Premium Job Posts</span>
                                    <span class="info-box-number">{{ $transactions->where('type', 'premium_job_post')->count() }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-green" style="width: {{ $transactions->count() > 0 ? round($transactions->where('type', 'premium_job_post')->count() / $transactions->count() * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">
                                        ${{ number_format($transactions->where('type', 'premium_job_post')->sum('amount'), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-blue elevation-1">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Course Promotions</span>
                                    <span class="info-box-number">{{ $transactions->where('type', 'premium_course_promotion')->count() }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-blue" style="width: {{ $transactions->count() > 0 ? round($transactions->where('type', 'premium_course_promotion')->count() / $transactions->count() * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">
                                        ${{ number_format($transactions->where('type', 'premium_course_promotion')->sum('amount'), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-orange elevation-1">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Event Promotions</span>
                                    <span class="info-box-number">{{ $transactions->where('type', 'premium_event_promotion')->count() }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-orange" style="width: {{ $transactions->count() > 0 ? round($transactions->where('type', 'premium_event_promotion')->count() / $transactions->count() * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">
                                        ${{ number_format($transactions->where('type', 'premium_event_promotion')->sum('amount'), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-purple elevation-1">
                                    <i class="fas fa-university"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">University Services</span>
                                    <span class="info-box-number">{{ $transactions->where('type', 'university_admission_service')->count() }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-purple" style="width: {{ $transactions->count() > 0 ? round($transactions->where('type', 'university_admission_service')->count() / $transactions->count() * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">
                                        ${{ number_format($transactions->where('type', 'university_admission_service')->sum('amount'), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Filter -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.payments.premium') }}" class="form-inline">
                                <div class="form-group mb-2">
                                    <input type="text" name="search" class="form-control" placeholder="Search by user or package..." value="{{ request('search') }}">
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select name="type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="premium_job_post" {{ request('type') == 'premium_job_post' ? 'selected' : '' }}>Premium Job Post</option>
                                        <option value="premium_course_promotion" {{ request('type') == 'premium_course_promotion' ? 'selected' : '' }}>Course Promotion</option>
                                        <option value="premium_event_promotion" {{ request('type') == 'premium_event_promotion' ? 'selected' : '' }}>Event Promotion</option>
                                        <option value="ad_payment" {{ request('type') == 'ad_payment' ? 'selected' : '' }}>Ad Payment</option>
                                        <option value="university_admission_service" {{ request('type') == 'university_admission_service' ? 'selected' : '' }}>University Service</option>
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.payments.premium') }}" class="btn btn-secondary mb-2">
                                    Clear
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Premium Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Content Type</th>
                                    <th scope="col">Package/Service</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payment Gateway</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $transaction->user->name ?? 'N/A' }}</span>
                                                <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($transaction->type == 'premium_job_post')
                                                <span class="badge bg-primary">Premium Job Post</span>
                                            @elseif($transaction->type == 'premium_course_promotion')
                                                <span class="badge bg-info">Course Promotion</span>
                                            @elseif($transaction->type == 'premium_event_promotion')
                                                <span class="badge bg-warning">Event Promotion</span>
                                            @elseif($transaction->type == 'ad_payment')
                                                <span class="badge bg-success">Ad Payment</span>
                                            @elseif($transaction->type == 'university_admission_service')
                                                <span class="badge bg-purple">University Service</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-truncate" style="max-width: 150px;" title="{{ $transaction->package_name ?: 'N/A' }}">{{ $transaction->package_name ?: 'N/A' }}</td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ ucfirst($transaction->payment_gateway) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
                                        <td>
                                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info btn-block">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No premium payments found</td>
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