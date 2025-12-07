@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Statistics</h3>
                </div>
                <div class="card-body">
                    <!-- Payment Dashboard -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalTransactions ?? 0 }}</h3>
                                    <p>Completed Transactions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pendingPayments ?? 0 }}</h3>
                                    <p>Pending Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $activeSubscriptions ?? 0 }}</h3>
                                    <p>Active Subscriptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-subscription"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Transactions</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Gateway</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentTransactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction->id }}</td>
                                                        <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                                        <td>{{ ucfirst($transaction->payment_gateway) }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                                                {{ ucfirst($transaction->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $transaction->created_at->format('M j, g:i A') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No recent transactions</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Quick Stats -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Quick Stats</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <i class="fas fa-chart-line text-primary mr-2"></i>
                                            <strong>{{ \App\Models\Transaction::where('status', 'completed')->whereDate('created_at', today())->count() }}</strong> transactions today
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-chart-bar text-success mr-2"></i>
                                            <strong>${{ number_format(\App\Models\Transaction::where('status', 'completed')->whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])->sum('amount'), 2) }}</strong> this week
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-chart-pie text-info mr-2"></i>
                                            <strong>${{ number_format(\App\Models\Transaction::where('status', 'completed')->whereMonth('created_at', today()->month)->sum('amount'), 2) }}</strong> this month
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-credit-card text-warning mr-2"></i>
                                            <strong>{{ \App\Models\Transaction::where('payment_gateway', 'stripe')->where('status', 'completed')->count() }}</strong> Stripe transactions
                                        </li>
                                        <li>
                                            <i class="fas fa-cash-register text-success mr-2"></i>
                                            <strong>{{ \App\Models\Transaction::where('payment_gateway', 'paystack')->where('status', 'completed')->count() }}</strong> Paystack transactions
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Payment Gateway Usage -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Payment Gateway Usage</h3>
                                </div>
                                <div class="card-body">
                                    @php
                                        $gatewayStats = \App\Models\Transaction::select('payment_gateway', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(amount) as total'))
                                            ->where('status', 'completed')
                                            ->groupBy('payment_gateway')
                                            ->get();
                                    @endphp
                                    
                                    @forelse($gatewayStats as $stat)
                                        <div class="mb-3">
                                            <strong>{{ ucfirst($stat->payment_gateway) }}</strong>
                                            <div class="progress mt-1" style="height: 10px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($stat->count / max(1, $totalTransactions)) * 100 }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $stat->count }} transactions • ${{ number_format($stat->total, 2) }}</small>
                                        </div>
                                    @empty
                                        <p class="text-muted">No payment gateway usage data available</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue by Month -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Revenue Trends (Last 6 Months)</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Transactions</th>
                                                    <th>Total Revenue</th>
                                                    <th>Success Rate</th>
                                                    <th>Average Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for($i = 5; $i >= 0; $i--)
                                                    @php
                                                        $month = now()->subMonths($i);
                                                        $startOfMonth = $month->startOfMonth();
                                                        $endOfMonth = $month->endOfMonth();
                                                        
                                                        $monthlyTransactions = \App\Models\Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                                                        $monthlyCompleted = \App\Models\Transaction::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                                                        $monthlyRevenue = \App\Models\Transaction::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount');
                                                        $successRate = $monthlyTransactions > 0 ? round(($monthlyCompleted / $monthlyTransactions) * 100, 2) : 0;
                                                        $avgValue = $monthlyCompleted > 0 ? round($monthlyRevenue / $monthlyCompleted, 2) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $month->format('F Y') }}</td>
                                                        <td>{{ $monthlyTransactions }}</td>
                                                        <td>${{ number_format($monthlyRevenue, 2) }}</td>
                                                        <td>{{ $successRate }}%</td>
                                                        <td>${{ number_format($avgValue, 2) }}</td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Navigation -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Manage Payments</h4>
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-primary">
                                            <i class="fas fa-receipt mr-2"></i> All Transactions
                                        </a>
                                        <a href="{{ route('admin.subscriptions.active') }}" class="btn btn-success">
                                            <i class="fas fa-subscript mr-2"></i> Active Subscriptions
                                        </a>
                                        <a href="{{ route('admin.subscriptions.all') }}" class="btn btn-info">
                                            <i class="fas fa-list mr-2"></i> All Subscriptions
                                        </a>
                                        <a href="{{ route('admin.payments.premium') }}" class="btn btn-warning">
                                            <i class="fas fa-bullhorn mr-2"></i> Premium Payments
                                        </a>
                                        <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-dark">
                                            <i class="fas fa-credit-card mr-2"></i> Payment Gateways
                                        </a>
                                        <a href="{{ route('admin.subscription-packages.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-box-open mr-2"></i> Subscription Packages
                                        </a>
                                    </div>
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