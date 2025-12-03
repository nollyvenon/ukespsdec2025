@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Payment Gateway</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-gateways.update', $paymentGateway) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $paymentGateway->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $paymentGateway->slug) }}" required>
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', $paymentGateway->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="credentials">Credentials (JSON)</label>
                            <textarea name="credentials" id="credentials" class="form-control" rows="5" required>{{ old('credentials') ? json_encode(old('credentials')) : json_encode($paymentGateway->credentials ?: []) }}</textarea>
                            <small class="form-text text-muted">Enter credentials as JSON, e.g., {"api_key": "your_api_key", "secret": "your_secret"}</small>
                            @error('credentials')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="supported_currencies">Supported Currencies</label>
                            <input type="text" name="supported_currencies" id="supported_currencies" class="form-control" value="{{ old('supported_currencies') ? implode(',', old('supported_currencies')) : ($paymentGateway->supported_currencies ? implode(',', $paymentGateway->supported_currencies) : '') }}">
                            <small class="form-text text-muted">Comma-separated currency codes (e.g., USD,EUR,GBP)</small>
                            @error('supported_currencies')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="supported_countries">Supported Countries</label>
                            <input type="text" name="supported_countries" id="supported_countries" class="form-control" value="{{ old('supported_countries') ? implode(',', old('supported_countries')) : ($paymentGateway->supported_countries ? implode(',', $paymentGateway->supported_countries) : '') }}">
                            <small class="form-text text-muted">Comma-separated country codes (e.g., US,GB,CA)</small>
                            @error('supported_countries')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaction_fee_percent">Transaction Fee Percentage</label>
                            <input type="number" name="transaction_fee_percent" id="transaction_fee_percent" class="form-control" value="{{ old('transaction_fee_percent', $paymentGateway->transaction_fee_percent) }}" step="0.01" min="0" max="100">
                            <small class="form-text text-muted">Percentage fee charged per transaction (0-100)</small>
                            @error('transaction_fee_percent')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaction_fee_fixed">Fixed Transaction Fee</label>
                            <input type="number" name="transaction_fee_fixed" id="transaction_fee_fixed" class="form-control" value="{{ old('transaction_fee_fixed', $paymentGateway->transaction_fee_fixed) }}" step="0.01" min="0">
                            <small class="form-text text-muted">Fixed fee charged per transaction</small>
                            @error('transaction_fee_fixed')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $paymentGateway->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Payment Gateway</button>
                        <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection