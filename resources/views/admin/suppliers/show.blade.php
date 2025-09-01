@extends('layouts.admin')

@section('title', 'Chi tiết nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết nhà cung cấp</h2>

    <p><strong>Tên:</strong> {{ $supplier->name }}</p>
    <p><strong>Địa chỉ:</strong> {{ $supplier->address }}</p>
    <p><strong>SĐT:</strong> {{ $supplier->phone }}</p>
    <p><strong>Email:</strong> {{ $supplier->email }}</p>
    <p><strong>Mã số thuế:</strong> {{ $supplier->tax_code }}</p>
    <p><strong>Hợp đồng:</strong> 
        @if($supplier->contract)
            <a href="{{ asset('storage/' . $supplier->contract) }}" target="_blank">Xem</a>
        @else
            Không có
        @endif
    </p>

    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection
    