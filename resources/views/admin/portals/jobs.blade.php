@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jobs Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-briefcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Jobs</span>
                                    <span class="info-box-number">{{ $jobListings->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Job Applications</span>
                                    <span class="info-box-number">{{ $applications->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Recruiters</span>
                                    <span class="info-box-number">{{ \App\Models\User::where('role', 'recruiter')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Premium Jobs</span>
                                    <span class="info-box-number">{{ $jobListings->where('is_premium', true)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Listings Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Job Listings</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('jobs.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Create Job
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Title</th>
                                                    <th>Company</th>
                                                    <th>Type</th>
                                                    <th>Location</th>
                                                    <th>Applications</th>
                                                    <th>Status</th>
                                                    <th>Featured</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($jobListings as $job)
                                                <tr>
                                                    <td>{{ $job->id }}</td>
                                                    <td>{{ $job->title }}</td>
                                                    <td>{{ $job->poster->name ?? 'N/A' }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</td>
                                                    <td>{{ $job->location }}</td>
                                                    <td>{{ $job->applications->count() }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $job->job_status === 'published' ? 'success' : ($job->job_status === 'draft' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($job->job_status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $job->is_premium ? 'warning' : 'secondary' }}">
                                                            {{ $job->is_premium ? 'Featured' : 'Regular' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $jobListings->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Applications -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Job Applications</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Candidate</th>
                                                    <th>Job Title</th>
                                                    <th>Company</th>
                                                    <th>Applied On</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($applications as $application)
                                                <tr>
                                                    <td>{{ $application->applicant->name ?? 'N/A' }}</td>
                                                    <td>{{ $application->job->title ?? 'N/A' }}</td>
                                                    <td>{{ $application->job->poster->name ?? 'N/A' }}</td>
                                                    <td>{{ $application->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'accepted' ? 'success' : 'danger') }}">
                                                            {{ ucfirst($application->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('jobs.show', $application->job) }}" class="btn btn-sm btn-info">View Job</a>
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