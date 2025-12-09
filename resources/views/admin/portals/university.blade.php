@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">University Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">University Courses</span>
                                    <span class="info-box-number">{{ $universityCourses->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-graduation-cap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Enrollments</span>
                                    <span class="info-box-number">{{ \App\Models\AffiliatedCourseEnrollment::count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-school"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Universities</span>
                                    <span class="info-box-number">{{ isset($universities) ? $universities->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-tie"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">University Managers</span>
                                    <span class="info-box-number">{{ $universityManagers->total() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- University Courses -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">University Courses</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('affiliated-courses.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Create University Course
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
                                                    <th>University</th>
                                                    <th>Level</th>
                                                    <th>Duration</th>
                                                    <th>Fee</th>
                                                    <th>Students</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($universityCourses as $course)
                                                <tr>
                                                    <td>{{ $course->id }}</td>
                                                    <td>{{ $course->title }}</td>
                                                    <td>{{ $course->university ? $course->university->name : 'N/A' }}</td>
                                                    <td>{{ ucfirst($course->level) }}</td>
                                                    <td>{{ $course->duration }} weeks</td>
                                                    <td>
                                                        @if($course->fee > 0)
                                                            ${{ number_format($course->fee, 2) }}
                                                        @else
                                                            Free
                                                        @endif
                                                    </td>
                                                    <td>{{ $course->affiliatedCourseEnrollments->count() }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $course->status === 'published' || $course->status === 'ongoing' ? 'success' : ($course->status === 'draft' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($course->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('affiliated-courses.show', $course) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('affiliated-courses.edit', $course) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $universityCourses->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- University Managers -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">University Managers</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>University</th>
                                                    <th>Created At</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($universityManagers as $manager)
                                                <tr>
                                                    <td>{{ $manager->id }}</td>
                                                    <td>{{ $manager->name }}</td>
                                                    <td>{{ $manager->email }}</td>
                                                    <td>{{ $manager->profile ? ($manager->profile->university_name ?? ($manager->profile->university ? $manager->profile->university->name : 'N/A')) : 'N/A' }}</td>
                                                    <td>{{ $manager->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Active</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $manager) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('admin.users.edit', $manager) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $universityManagers->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Universities Overview -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Universities Overview</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if(isset($universities) && $universities->count() > 0)
                                            @foreach($universities as $uni)
                                            <div class="col-md-4">
                                                <div class="card card-outline card-info">
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{ $uni->name }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <p><strong>University:</strong> {{ $uni->name }}</p>
                                                        @if($uni->description)
                                                            <p><strong>Description:</strong> {{ Str::limit($uni->description, 100) }}</p>
                                                        @endif
                                                        <p><strong>Courses:</strong> {{ \App\Models\AffiliatedCourse::where('university_id', $uni->id)->count() }}</p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <a href="{{ route('affiliated-courses.index') }}?university={{ urlencode($uni->name) }}" class="btn btn-sm btn-primary">View Courses</a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <p class="text-center text-muted">No universities with affiliated courses found.</p>
                                            </div>
                                        @endif
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