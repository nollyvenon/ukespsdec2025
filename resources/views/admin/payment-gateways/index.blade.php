@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Gateways</h3>
                    <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-primary float-right">Add Payment Gateway</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Fee (%)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentGateways as $gateway)
                            <tr>
                                <td>{{ $gateway->id }}</td>
                                <td>{{ $gateway->name }}</td>
                                <td>{{ $gateway->slug }}</td>
                                <td>
                                    <span class="badge {{ $gateway->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $gateway->transaction_fee_percent }}%</td>
                                <td>
                                    <a href="{{ route('admin.payment-gateways.edit', $gateway) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('admin.payment-gateways.toggle', $gateway) }}" class="btn btn-sm btn-{{ $gateway->is_active ? 'warning' : 'success' }}">
                                        {{ $gateway->is_active ? 'Deactivate' : 'Activate' }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $paymentGateways->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection