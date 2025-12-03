@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Subscription Package</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-packages.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Package Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_type">Role Type</label>
                                    <select name="role_type" id="role_type" class="form-control" required>
                                        <option value="">Select Role Type</option>
                                        <option value="student" {{ old('role_type') == 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="job_seeker" {{ old('role_type') == 'job_seeker' ? 'selected' : '' }}>Job Seeker</option>
                                        <option value="recruiter" {{ old('role_type') == 'recruiter' ? 'selected' : '' }}>Recruiter</option>
                                        <option value="university_manager" {{ old('role_type') == 'university_manager' ? 'selected' : '' }}>University Manager</option>
                                        <option value="event_hoster" {{ old('role_type') == 'event_hoster' ? 'selected' : '' }}>Event Host</option>
                                    </select>
                                    @error('role_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Package Type</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="one_time" {{ old('type') == 'one_time' ? 'selected' : '' }}>One-Time</option>
                                        <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ old('type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price ($)</label>
                                    <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_days">Duration (Days)</label>
                                    <input type="number" name="duration_days" id="duration_days" class="form-control" value="{{ old('duration_days') }}">
                                    <small class="form-text text-muted">Number of days the subscription is valid (leave blank for recurring)</small>
                                    @error('duration_days')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">Sort Order</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('sort_order')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Features</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="access_to_all_courses" id="feature1">
                                        <label class="form-check-label" for="feature1">Access to all courses</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="event_registration" id="feature2">
                                        <label class="form-check-label" for="feature2">Event registration</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="job_applications" id="feature3">
                                        <label class="form-check-label" for="feature3">Job applications</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="post_jobs" id="feature4">
                                        <label class="form-check-label" for="feature4">Post jobs (recruiters)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="create_courses" id="feature5">
                                        <label class="form-check-label" for="feature5">Create courses (universities)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="host_events" id="feature6">
                                        <label class="form-check-label" for="feature6">Host events</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="premium_support" id="feature7">
                                        <label class="form-check-label" for="feature7">Premium support</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="analytics_dashboard" id="feature8">
                                        <label class="form-check-label" for="feature8">Analytics dashboard</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="promoted_content" id="feature9">
                                        <label class="form-check-label" for="feature9">Promoted content</label>
                                    </div>
                                </div>
                            </div>
                            @error('features')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Package</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Package</button>
                        <a href="{{ route('admin.subscription-packages.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection