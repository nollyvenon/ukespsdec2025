@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Events Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Events</span>
                                    <span class="info-box-number">{{ $events->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-ticket-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Registrations</span>
                                    <span class="info-box-number">{{ $registrations->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Participants</span>
                                    <span class="info-box-number">1,234</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Success Rate</span>
                                    <span class="info-box-number">89%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Events -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Events</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Create Event
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
                                                    <th>Date</th>
                                                    <th>Location</th>
                                                    <th>Registrations</th>
                                                    <th>Status</th>
                                                    <th>Premium</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($events as $event)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $event->title }}</td>
                                                    <td>{{ $event->start_date->format('M j, Y g:i A') }}</td>
                                                    <td>{{ $event->location }}</td>
                                                    <td>{{ $event->eventRegistrations->count() }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $event->event_status === 'published' ? 'success' : ($event->event_status === 'draft' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($event->event_status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $event->is_premium ? 'warning' : 'secondary' }}">
                                                            {{ $event->is_premium ? 'Premium' : 'Regular' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $events->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Registrations -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Registrations</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Event</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Payment Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($registrations as $registration)
                                                <tr>
                                                    <td>{{ $registration->user->name ?? 'N/A' }}</td>
                                                    <td>{{ $registration->event->title ?? 'N/A' }}</td>
                                                    <td>{{ $registration->created_at->format('M j, Y g:i A') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Confirmed</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $registration->payment_status === 'completed' ? 'success' : ($registration->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($registration->payment_status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-info">Details</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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