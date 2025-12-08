@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Universities</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Universities</span>
                                    <span class="info-box-number">{{ $universities->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-graduation-cap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">University Courses</span>
                                    <span class="info-box-number">{{ \App\Models\AffiliatedCourse::count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-tie"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Manager</span>
                                    <span class="info-box-number">{{ \App\Models\User::where('role', 'university_manager')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Students</span>
                                    <span class="info-box-number">{{ \App\Models\AffiliatedCourseEnrollment::count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Website</th>
                                    <th>Courses</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universities as $university)
                                <tr>
                                    <td>{{ $university->id }}</td>
                                    <td>{{ $university->name }}</td>
                                    <td>{{ $university->location ?? 'N/A' }}</td>
                                    <td>
                                        @if($university->website)
                                            <a href="{{ $university->website }}" target="_blank">{{ parse_url($university->website, PHP_URL_HOST) ?? $university->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $university->affiliatedCourses->count() }}</td>
                                    <td>
                                        <span class="badge bg-{{ $university->is_active ? 'success' : 'secondary' }}">
                                            {{ $university->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.universities.show', $university) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.universities.courses', $university) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-book"></i> Courses
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No universities found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $universities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection