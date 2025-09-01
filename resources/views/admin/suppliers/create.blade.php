@extends('layouts.admin')

@section('title', 'Thêm nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Thêm nhà cung cấp</h2>

    <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3"><label>Tên</label><input type="text" name="name" class="form-control"></div>
        <div class="mb-3"><label>Địa chỉ</label><input type="text" name="address" class="form-control"></div>
        <div class="mb-3"><label>SĐT</label><input type="text" name="phone" class="form-control"></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control"></div>
        <div class="mb-3"><label>Mã số thuế</label><input type="text" name="tax_code" class="form-control"></div>
        <div class="mb-3"><label>Hợp đồng (ảnh/pdf)</label><input type="file" name="contract" class="form-control" accept="image/*,.pdf"></div>
        <button class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
