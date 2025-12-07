@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recruitment Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Recruiters</span>
                                    <span class="info-box-number">{{ $recruiters->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-briefcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Job Listings</span>
                                    <span class="info-box-number">{{ $jobListings->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Applications</span>
                                    <span class="info-box-number">{{ $applications->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Avg. Response</span>
                                    <span class="info-box-number">2.3 days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Job Listings -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Job Listings</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Company</th>
                                                <th>Date</th>
                                                <th>Applications</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobListings as $job)
                                            <tr>
                                                <td>{{ $job->title }}</td>
                                                <td>{{ $job->poster->name ?? 'N/A' }}</td>
                                                <td>{{ $job->created_at->format('M j, Y') }}</td>
                                                <td>
                                                    {{ $job->applications->count() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Applications -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Job Applications</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Candidate</th>
                                                <th>Job</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $application)
                                            <tr>
                                                <td>{{ $application->applicant->name ?? 'N/A' }}</td>
                                                <td>{{ $application->job->title ?? 'N/A' }}</td>
                                                <td>{{ $application->created_at->format('M j, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'accepted' ? 'success' : 'danger') }}">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recruiters List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recruiters</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Company</th>
                                                    <th>Job Posts</th>
                                                    <th>Reg. Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recruiters as $recruiter)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $recruiter->name }}</td>
                                                    <td>{{ $recruiter->email }}</td>
                                                    <td>{{ $recruiter->profile->company_name ?? $recruiter->name }}</td>
                                                    <td>
                                                        {{ $recruiter->postedJobs->count() }}
                                                    </td>
                                                    <td>{{ $recruiter->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $recruiter) }}" class="btn btn-sm btn-primary">View</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pagination">
                                        {{ $recruiters->links() }}
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