@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-graduate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Students</span>
                                    <span class="info-box-number">{{ $students->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Course Enrollments</span>
                                    <span class="info-box-number">{{ $enrollments->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-briefcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Job Applications</span>
                                    <span class="info-box-number">{{ $jobApplications->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Event Registrations</span>
                                    <span class="info-box-number">1,234</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Students</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Courses Enrolled</th>
                                                    <th>Jobs Applied</th>
                                                    <th>Events Registered</th>
                                                    <th>Join Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($students as $student)
                                                <tr>
                                                    <td>{{ $student->id }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->email }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $student->role === 'student' ? 'info' : ($student->role === 'job_seeker' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $student->role)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $student->courseEnrollments->count() }}</td>
                                                    <td>{{ $student->jobApplications->count() }}</td>
                                                    <td>{{ $student->eventRegistrations->count() }}</td>
                                                    <td>{{ $student->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $student) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('admin.users.edit', $student) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $students->links() }}
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