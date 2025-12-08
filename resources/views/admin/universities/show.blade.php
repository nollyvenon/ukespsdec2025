@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Courses from {{ $university->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">University</span>
                                    <span class="info-box-number">{{ $university->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Courses</span>
                                    <span class="info-box-number">{{ $courses->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Students</span>
                                    <span class="info-box-number">{{ $totalStudents }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Revenue</span>
                                    <span class="info-box-number">${{ number_format($totalRevenue, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Universities
                        </a>
                        <a href="{{ route('affiliated-courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i> Add New Course
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Level</th>
                                    <th>Duration</th>
                                    <th>Fee</th>
                                    <th>Enrollments</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                <tr>
                                    <td>{{ $course->id }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ ucfirst($course->level) }}</td>
                                    <td>{{ $course->duration }} weeks</td>
                                    <td>
                                        @if($course->fee > 0)
                                            ${{ number_format($course->fee, 2) }}
                                        @else
                                            Free
                                        @endif
                                    </td>
                                    <td>{{ $course->enrollments->count() }}</td>
                                    <td>
                                        <span class="badge bg-{{ $course->status === 'published' || $course->status === 'ongoing' ? 'success' : ($course->status === 'draft' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('affiliated-courses.show', $course) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('affiliated-courses.edit', $course) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No courses found for {{ $university->name }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection