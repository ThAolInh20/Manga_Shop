@extends('admin.layout')

@section('title', 'Sửa nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Sửa nhà cung cấp</h2>

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3"><label>Tên</label><input type="text" name="name" class="form-control" value="{{ $supplier->name }}"></div>
        <div class="mb-3"><label>Địa chỉ</label><input type="text" name="address" class="form-control" value="{{ $supplier->address }}"></div>
        <div class="mb-3"><label>SĐT</label><input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}"></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $supplier->email }}"></div>
        <div class="mb-3"><label>Mã số thuế</label><input type="text" name="tax_code" class="form-control" value="{{ $supplier->tax_code }}"></div>
        <div class="mb-3">
            <label>Hợp đồng (ảnh/pdf)</label>
            <input type="file" name="contract" class="form-control" accept="image/*,.pdf">
            @if($supplier->contract)
                <p class="mt-2">Hợp đồng hiện tại: <a href="{{ asset('storage/' . $supplier->contract) }}" target="_blank">Xem</a></p>
            @endif
        </div>
        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
