@extends('layouts.admin')

@section('title', 'Sửa nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Sửa nhà cung cấp</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')

        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $supplier->address) }}">
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>SĐT</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Mã số thuế</label>
            <input type="text" name="tax_code" class="form-control" value="{{ old('tax_code', $supplier->tax_code) }}">
            @error('tax_code')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Link hợp đồng</label>
            <input type="url" name="link_contract" class="form-control" 
                   placeholder="Nhập link hợp đồng" value="{{ old('link_contract', $supplier->link_contract) }}">
            @error('link_contract')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            @if($supplier->link_contract)
                <p class="mt-2">Link hiện tại: <a href="{{ $supplier->link_contract }}" target="_blank">Xem</a></p>
            @endif
        </div>

        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
