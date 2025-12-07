@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Courses Portal</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Courses</span>
                                    <span class="info-box-number">{{ $courses->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-graduate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Students Enrolled</span>
                                    <span class="info-box-number">{{ \App\Models\CourseEnrollment::count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Premium Courses</span>
                                    <span class="info-box-number">{{ $courses->filter(fn($course) => $course->is_premium)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Instructors</span>
                                    <span class="info-box-number">{{ \App\Models\User::where('role', 'instructor')->orWhere('role', 'university_manager')->distinct('id')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courses Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Courses</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('courses.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Create Course
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
                                                    <th>Instructor</th>
                                                    <th>Level</th>
                                                    <th>Duration</th>
                                                    <th>Enrollments</th>
                                                    <th>Status</th>
                                                    <th>Premium</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($courses as $course)
                                                <tr>
                                                    <td>{{ $course->id }}</td>
                                                    <td>{{ $course->title }}</td>
                                                    <td>{{ $course->instructor->name ?? 'N/A' }}</td>
                                                    <td>{{ ucfirst($course->level) }}</td>
                                                    <td>{{ $course->duration }} weeks</td>
                                                    <td>{{ $course->enrollments->count() }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $course->course_status === 'published' || $course->course_status === 'ongoing' ? 'success' : ($course->course_status === 'draft' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($course->course_status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $course->is_premium ? 'warning' : 'secondary' }}">
                                                            {{ $course->is_premium ? 'Premium' : 'Regular' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $courses->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Enrollments -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Course Enrollments</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Student</th>
                                                    <th>Course</th>
                                                    <th>Enrollment Date</th>
                                                    <th>Status</th>
                                                    <th>Progress</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($enrollments as $enrollment)
                                                <tr>
                                                    <td>{{ $enrollment->student->name ?? 'N/A' }}</td>
                                                    <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                                                    <td>{{ $enrollment->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Active</span>
                                                    </td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                                0%
                                                            </div>
                                                        </div>
                                                        0%
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-info">View Course</a>
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